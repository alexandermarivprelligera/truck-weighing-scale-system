"""Serial-to-REST bridge for a NUTEX CAS CI-3000A weighbridge indicator.

The indicator sends weight records over a serial connection.  This service
continuously receives those records, extracts the first signed number from
each record, and makes the most recently received weight available to the
main system over HTTP.

Configuration is supplied through environment variables:

* ``SCALE_PORT`` - serial port to use, e.g. ``COM3``.  If omitted, the first
  available serial port is used.
* ``SCALE_BAUDRATE`` - serial speed; defaults to ``9600``.
* ``SCALE_STALE_AFTER_SECONDS`` - age after which a reading is considered
  stale; defaults to ``3`` seconds.
* ``SCALE_API_PORT`` - HTTP port; defaults to ``5000``.

Endpoints:

* ``GET /weight`` returns the latest usable weight and its source record.
* ``GET /health`` reports whether the serial connection is available.
"""

import os
import re
import threading
import time
from typing import Any

import serial
from flask import Flask, jsonify
from serial.tools import list_ports


app = Flask(__name__)

# CI-3000A output commonly contains a signed decimal weight.  Keeping this
# parser permissive also supports the indicator's different output templates.
WEIGHT_PATTERN = re.compile(r"[-+]?\d*\.?\d+")


class ScaleBridge:
    """Owns the serial connection and the latest processed indicator record.

    A background reader is used so an HTTP request never has to wait for the
    next weighing record.  A lock protects the shared state because Flask and
    the reader run in separate threads.
    """

    def __init__(self, port: str | None, baudrate: int, stale_after: float):
        self.port = port
        self.baudrate = baudrate
        self.stale_after = stale_after
        self.serial_connection: serial.Serial | None = None
        self.last_weight: str | None = None
        self.last_raw = ""
        self.last_update: float | None = None
        self.last_error: str | None = None
        self._lock = threading.Lock()
        self._reader: threading.Thread | None = None

    def connect(self) -> bool:
        """Open the configured port, or auto-select the first available port."""
        candidates = [self.port] if self.port else [p.device for p in list_ports.comports()]
        if not candidates:
            self.last_error = "No serial device found"
            return False

        for device in candidates:
            try:
                self.serial_connection = serial.Serial(
                    port=device,
                    baudrate=self.baudrate,
                    timeout=1,
                )
                self.port = device
                self.last_error = None
                self.serial_connection.reset_input_buffer()
                print(f"Connected to weighbridge indicator on {device}")
                return True
            except serial.SerialException as error:
                self.last_error = f"Could not open {device}: {error}"
                print(self.last_error)

        return False

    def start(self) -> None:
        """Connect to the indicator and begin receiving records in the background."""
        if self.connect():
            self._reader = threading.Thread(target=self._read_forever, daemon=True)
            self._reader.start()

    def _read_forever(self) -> None:
        """Read complete newline-delimited records, reconnecting on failure."""
        while True:
            while self.serial_connection and self.serial_connection.is_open:
                try:
                    raw = self.serial_connection.readline().decode("utf-8", errors="ignore").strip()
                    if raw:
                        self.process_record(raw)
                except (serial.SerialException, OSError) as error:
                    with self._lock:
                        self.last_error = f"Serial read failed: {error}"
                    print(self.last_error)
                    break

            time.sleep(2)
            print("Attempting to reconnect to weighbridge indicator...")
            if self.connect():
                print("Reconnected successfully.")
            else:
                time.sleep(3)

    def process_record(self, raw: str) -> None:
        """Store a raw indicator record and its parsed numeric weight, if present."""
        match = WEIGHT_PATTERN.search(raw)
        with self._lock:
            self.last_raw = raw
            if match:
                self.last_weight = match.group()
                self.last_update = time.time()
        print(f"RAW: {raw}")

    def status(self) -> dict[str, Any]:
        """Return a JSON-safe snapshot of the current reading and connection state."""
        with self._lock:
            connected = bool(self.serial_connection and self.serial_connection.is_open)
            age = time.time() - self.last_update if self.last_update else None
            is_fresh = age is not None and age <= self.stale_after
            return {
                "weight": self.last_weight if is_fresh else "0",
                "raw": self.last_raw,
                "unit": "kg",  # Change if the indicator has been configured for another unit.
                "fresh": is_fresh,
                "age_seconds": round(age, 3) if age is not None else None,
                "connected": connected,
                "port": self.port,
                "error": self.last_error,
            }


bridge = ScaleBridge(
    port=os.getenv("SCALE_PORT"),
    baudrate=int(os.getenv("SCALE_BAUDRATE", "9600")),
    stale_after=float(os.getenv("SCALE_STALE_AFTER_SECONDS", "3")),
)


@app.get("/weight")
def weight():
    """Return the latest indicator reading.

    ``weight`` becomes ``"0"`` when no valid record has arrived within the
    configured stale interval.  ``raw`` remains available for diagnostics.
    """
    payload = bridge.status()
    status_code = 200 if payload["connected"] else 503
    return jsonify(payload), status_code


@app.get("/health")
def health():
    """Report whether the API can currently communicate with the indicator."""
    payload = bridge.status()
    return jsonify({"connected": payload["connected"], "error": payload["error"]}), (
        200 if payload["connected"] else 503
    )


if __name__ == "__main__":
    bridge.start()
    app.run(host="0.0.0.0", port=int(os.getenv("SCALE_API_PORT", "5000")), debug=False)
