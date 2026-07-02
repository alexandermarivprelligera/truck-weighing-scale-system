@extends('layout')

@section('content')

    <div class="space-y-4 mb-6">
        <a href="/clients" class="bg-gray-500 text-white px-4 py-2 rounded">
            ← Back
        </a>
    </div>

<h2 class="text-2xl font-bold mb-4">
Client Details
</h2>

<div class="bg-white p-6 rounded shadow">

    <p>
    <strong>Branch Code:</strong>
    {{ $client->branch_code }}
    </p>

    <p>
    <strong>Branch Name:</strong>
    {{ $client->company }}
    </p>

    <p>
    <strong>TIN:</strong>
    {{ $client->tin_number }}
    </p>

    <p>
    <strong>Address:</strong>
    {{ $client->address }}
    </p>

    <p>
    <strong>Mayor:</strong>
    {{ $client->mayor }}
    </p>

    <p>
    <strong>Contact Person:</strong>
    {{ $client->contact_person }}
    </p>

    <p>
    <strong>Contact Number:</strong>
    {{ $client->contact_number }}
    </p>
</div>

<h3 class="text-xl font-bold mt-6 mb-3">
Transaction History
</h3>

<table class="w-full border">
    <thead>
        <tr class="bg-gray-200">
            <th>Transaction No</th>
            <th>Plate</th>
            <th>Driver</th>
            <th>Net Weight</th>
        </tr>
    </thead>

    <tbody class="text-center">
        @foreach($transactions as $transaction)
            <tr>
            <td>
            {{ $transaction->transaction_no }}
            </td>

            <td>
            {{ $transaction->plate_number }}
            </td>

            <td>
            {{ $transaction->driver_name }}
            </td>

            <td>
            {{ $transaction->net_weight }}
            </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection