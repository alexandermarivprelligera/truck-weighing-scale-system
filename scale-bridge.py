from flask import Flask, jsonify
from serial.tools import list_ports
import serial
import threading
import re
import time

app = Flask(__name__)

ser = None

current_weight = "0"
last_weight = "0"
last_raw = ""
lock = threading.Lock()


# ----------------------------
# CONNECT TO SERIAL
# ----------------------------
def connect_serial():
    global ser

    while ser is None:
        ports = list_ports.comports()
        print("\nSearching serial ports...")
        for p in ports:
            print(f"{p.device} - {p.description}")
            try:
                ser = serial.Serial(
                    p.device,
                    baudrate=9600,
                    timeout=1
                )
                print(f"Connected to {p.device}")
                time.sleep(2)
                return
            except Exception as e:
                print(e)
        print("Retrying in 3 seconds...\n")
        time.sleep(3)


# ----------------------------
# CONTINUOUS SERIAL READER
# ----------------------------
def serial_reader():

    global current_weight
    global last_weight
    global last_raw
    global ser

    while True:
        if ser is None:
            connect_serial()
        try:
            if ser.in_waiting:
                line = ser.readline().decode(
                    "utf-8",
                    errors="ignore"
                ).strip()
                
                if line:
                    last_raw = line
                    print("RAW:", line)
                    match = re.search(
                        r'[-+]?\d*\.?\d+',
                        line
                    )

                    if match:
                        weight = match.group()
                        with lock:
                            current_weight = weight
                            last_weight = weight

        except Exception as e:
            print("Serial Lost:", e)
            try:
                ser.close()
            except:
                pass
            ser = None
            time.sleep(2)


# ----------------------------
# WEIGHT ENDPOINT
# ----------------------------
@app.route("/weight")
def weight():
    with lock:
        return jsonify({
            "weight": last_weight,
            "live": current_weight,
            "raw": last_raw

        })


# ----------------------------
# START THREAD
# ----------------------------
threading.Thread(
    target=serial_reader,
    daemon=True
).start()


# ----------------------------
# START SERVER
# ----------------------------
if __name__ == "__main__":
    app.run(
        host="0.0.0.0",
        port=5000,
        debug=False
    )