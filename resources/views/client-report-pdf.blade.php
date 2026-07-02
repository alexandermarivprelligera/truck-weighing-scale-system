<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">

    <title>Client Report</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            display:flex;
            align-items:center;
            gap:50px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .logo {
            height: 70px;
        }

        .title {
            text-align:center;
            font-size: 22px;
            font-weight: bold;
        }

        /*.subtitle {
            font-size: 14px;
            color: #555;
        }*/

        .client-info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        table th {
            background: #e5e5e5;
        }

        @page {
            margin: 20px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" class="logo">
        <div class="title">
            Truck Weighing Scale System
        </div>
        
    </div>

    <!-- CLIENT INFO -->
    <div class="client-info">
        <h1 class="subtitle">
            Client Transaction Report
        </h1>
        <h2>
            Branch Name: {{ $client->company }}
        </h2>
        <p>
            Address: {{ $client->address }}
        </p>
        <p>
            Total Transactions:
            {{ $client->transactions->count() }}
        </p>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>Transaction No</th>
                <th>Plate No</th>
                <th>Driver</th>
                <th>Net Weight</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>

        @foreach($client->transactions as $t)

            <tr>
                <td>{{ $t->transaction_no }}</td>
                <td>{{ $t->plate_number }}</td>
                <td>{{ $t->driver_name }}</td>
                <td>{{ $t->net_weight }}</td>
                <td>
                    {{ $t->created_at->format('Y-m-d H:i') }}
                </td>
            </tr>

        @endforeach

        </tbody>
    </table>

    <script>
        window.onload = function () {
            window.print();
        }
    </script>

</body>
</html>