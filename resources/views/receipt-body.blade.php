<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
</head>
<body onload="window.print()" style="font-family: monospace;">

<!--style>
@media print {

    body {
        width: 58mm;
        font-size: 11px;
        font-family: monospace;
    }

    .receipt {
        width: 58mm;
    }
}
</!--style-->

<div class="receipt">
<div style="text-align:center; margin-bottom:10px;">
    <img src="{{ asset('images/logo.png') }}" width="120">
    <h3>Truck Weighing Receipt</h3>
</div>

<hr>

<p>Transaction #: {{ $transaction->transaction_no }}</p>
<p>Plate #: {{ $transaction->plate_number }}</p>
<!--p>Driver: {{ $transaction->driver_name }}</!--p>
<p>Representative: {{$transaction->representative_name}}</p-->
<p>Material: {{ $transaction->material }}</p>
<p>Company: {{ $transaction->company }}</p>
<p>Address: {{ $transaction->address }}</p>

<hr>

<p>Gross: {{ $transaction->gross_weight }} kg</p>
<p>{{ $transaction->gross_time }}</p>

<p>Tare: {{ $transaction->tare_weight }} kg</p>
<p>{{ $transaction->tare_time }}</p>

<p>Net: {{ $transaction->net_weight }} kg</p>
<p>{{ $transaction->net_time }}</p>

<hr>

<div class="grid grid-cols-2 gap-4">
<p>Clerk: {{ $transaction->clerk }}</p>
<!--p>Approved by: {{ $transaction->approved_by }}</!--p-->
<p>Driver: {{ $transaction->driver_name}}</p>
</div>

</div>

</body>
</html>