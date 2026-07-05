from flask import Flask, jsonify
from serial.tools import list_ports
import serial
import re
import time

app = Flask(__name__)
ser = None

def connect_serial():

    global ser

    if ser is not None:
        try:
            if ser.is_open:
                return True
        except:
            pass

    ports = list(list_ports.comports())

    print("\nSearching serial ports...")

    # First choice: Prolific USB Serial
    for p in ports:

        print(f"{p.device} - {p.description}")

        if "Prolific" in p.description or "PL2303" in p.description:

            try:

                print(f"Connecting to {p.device}")

                ser = serial.Serial(
                    p.device,
                    baudrate=9600,
                    timeout=2
                )

                time.sleep(3)

                ser.reset_input_buffer()

                print("Connected.")

                return True

            except Exception as e:

                print(e)

    # Second choice: Any USB Serial
    for p in ports:

        try:

            ser = serial.Serial(
                p.device,
                baudrate=9600,
                timeout=2
            )

            time.sleep(3)

            ser.reset_input_buffer()

            print(f"Connected to {p.device}")

            return True

        except:

            pass

    ser = None
    return False

connect_serial()

# -------------------------
# WEIGHT ENDPOINT
# -------------------------
@app.route('/weight')
def weight():

    if ser is None:
        return jsonify({
            "error": "No serial device found"
        })

    try:

        line = ser.readline().decode(
            'utf-8',
            errors='ignore'
        ).strip()

        print("RAW:", line)

        # Extract first number found
        match = re.search(
            r'[-+]?\d*\.?\d+',
            line
        )

        value = match.group() if match else "0"

        return jsonify({
            "weight": value,
            "raw": line
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