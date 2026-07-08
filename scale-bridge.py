from flask import Flask, jsonify
from serial.tools import list_ports
import serial
import re
import time

app = Flask(__name__)

ser = None

# -------------------------
# Remember last weight
# -------------------------
last_weight = "0"
last_raw = ""
last_update = 0

# -------------------------
# AUTO DETECT SERIAL DEVICE
# -------------------------
ports = list_ports.comports()

print("Number of ports:", len(ports))
print("Detected Ports:")

for p in ports:

    print(f"{p.device} - {p.description}")

    try:

        ser = serial.Serial(
            port=p.device,
            baudrate=9600,
            timeout=1
        )

        print(f"Connected to {p.device}")

        time.sleep(2)

        break

    except Exception as e:

        print(f"Failed to connect to {p.device}: {e}")

# -------------------------
# WEIGHT ENDPOINT
# -------------------------
@app.route('/weight')
def weight():

    global last_weight
    global last_raw
    global last_update

    if ser is None:
        return jsonify({
            "error": "No serial device found"
        })

    try:

        line = ser.readline().decode(
            'utf-8',
            errors='ignore'
        ).strip()

        if line:

            print("RAW:", line)

            last_raw = line

            match = re.search(
                r'[-+]?\d*\.?\d+',
                line
            )

            if match:

                last_weight = match.group()
                last_update = time.time()

        # Keep displaying last weight for 3 seconds
        if time.time() - last_update <= 3:
            value = last_weight
        else:
            value = "0"

        return jsonify({
            "weight": value,
            "raw": last_raw
        })

    except Exception as e:

        return jsonify({
            "error": str(e)
        })

# -------------------------
# START SERVER
# -------------------------
if __name__ == '__main__':
    app.run(
        host='0.0.0.0',
        port=5000,
        debug=False
    )