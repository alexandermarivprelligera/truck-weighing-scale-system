<!DOCTYPE html>
<html>
<head>
    <title>Filtered Transactions Report</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            font-size:12px;
        }

        h2{
            text-align:center;
            margin-bottom:5px;
        }

        h4{
            text-align:center;
            margin-top:0;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table, th, td{
            border:1px solid black;
        }

        th, td{
            padding:6px;
            text-align:center;
        }

        th{
            background:#f0f0f0;
        }

        .header{
            margin-bottom:20px;
        }
    </style>
</head>

<body onload="window.print()">

<div class="header">

<div class="receipt">
<div style="text-align:center; margin-bottom:10px;">
    <img src="{{ asset('images/logo.png') }}" width="120">
    <h3>TRANSACTION REPORT</h3>
</div>


<h4>
    @if(request('company'))
    Client:
    <strong>{{ request('company') }}</strong>
    @endif

    @if(request('start_date'))
    Date:
    <strong>
    {{ request('start_date') }}
    @if(request('end_date') != request('start_date'))
    to {{ request('end_date') }}
    @endif
    </strong>
    @endif
</h4>
</div>

<table>
    <thead>
        <tr>
            <th>Transaction No</th>
            <th>Plate No.</th>
            <th>Driver</th>
            <!--th>Company</!--th-->
            <th>Address</th>
            <th>Material</th>
            <th>Net Weight</th>
            <th>Clerk</th>
        </tr>
    </thead>

    <tbody>
        @foreach($transactions as $t)
        <tr>
            <td>{{ $t->transaction_no }}</td>
            <td>{{ $t->plate_number }}</td>
            <td>{{ $t->driver_name }}</td>
            <!--td>{{ $t->company }}</!--td-->
            <td>{{ $t->address }}</td>
            <td>{{ $t->material }}</td>
            <td>{{ number_format($t->net_weight,2) }}</td>
            <td>{{ $t->clerk }}</td>
        </tr>
        @endforeach
    </tbody>

    <!--tfoot>
        <tr>
            <td colspan="6" align="right">
            <strong>Total Net Weight</strong>
            </td>

            <td colspan="2">
            <strong>{{ number_format($transactions->sum('net_weight'),2) }}</strong>
            </td>
        </tr>
    </!--tfoot-->

</table>

</body>
</html>