# Weighbridge serial API

`scale-bridge.py` is a small REST service between the NUTEX CAS CI-3000A
weighbridge indicator and the main application. It is not a terminal emulator:
instead of displaying serial records like Tera Term, it continuously receives
them, extracts the weight, and supplies JSON to API clients.

## Requirements

- Python 3.10 or later
- `pip install flask pyserial`
- A CI-3000A serial connection configured to send newline-terminated records

Set the indicator and computer serial settings to the same values (normally
9600 baud, 8 data bits, no parity, 1 stop bit unless the indicator is configured
differently).

## Run

In PowerShell, select the indicator port explicitly when possible:

```powershell
$env:SCALE_PORT = 'COM3'
$env:SCALE_BAUDRATE = '9600'
python .\scale-bridge.py
```

Without `SCALE_PORT`, the service tries the first detected serial port. This is
less reliable when the computer has multiple serial devices.

Optional settings are `SCALE_STALE_AFTER_SECONDS` (default `3`) and
`SCALE_API_PORT` (default `5000`).

## Electron startup

Starting the desktop application through `start-system.bat` also starts the
scale bridge alongside MariaDB and Laravel. During development, Electron runs
the current `scale-bridge.py` with `python`; set `PYTHON_EXECUTABLE` if Python
is not available on `PATH`. In the packaged application, Electron runs the
bundled `scale-bridge.exe`.

## API

`GET http://localhost:5000/weight` returns the latest processed reading:

```json
{
  "weight": "1250.5",
  "raw": "ST,GS,+1250.5 kg",
  "unit": "kg",
  "fresh": true,
  "age_seconds": 0.12,
  "connected": true,
  "port": "COM3",
  "error": null
}
```

If no valid reading has arrived during the stale interval, `weight` is `"0"`
and `fresh` is `false`. If the serial port cannot be opened, `/weight` and
`/health` return HTTP `503`; otherwise they return `200`.

`GET http://localhost:5000/health` is a lightweight connection check.

## Processing flow

```text
CI-3000A -> serial record -> background reader -> numeric parser -> latest reading -> REST JSON
```

The parser extracts the first signed integer or decimal number in each record.
Check actual CI-3000A output in the console (`RAW: ...`) and tighten
`WEIGHT_PATTERN` if the records include another number before the weight.
