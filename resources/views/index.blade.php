@extends('layout')

@section('title', 'Transactions')

@section('content')

    <div class="space-y-4 mb-6">
        <a href="/dashboard" class="bg-gray-500 text-white px-4 py-2 rounded">
            ← Back
        </a>
    </div>

<h2 class="text-lg font-semibold mb-4">Transactions</h2>

<div class="overflow-x-auto space-y-4">

<form method="GET" action="/transactions" class="flex gap-3 items-center">
    <select name="company" class="border p-2 rounded">
        <option value="">All Clients</option>
        @foreach($clients as $client)
            <option value="{{ $client->company }}" {{ request('company') == $client->company ? 'selected' : '' }}>
                {{ $client->company }}
            </option>
        @endforeach
    </select>

    <!--input type="date" name="date" value="{{ request('date') }}" class="border p-2 rounded"-->

    <input type="date" name="start_date" value="{{ request('start_date') }}" class="border p-2 rounded">
    <span>to</span>
    <input type="date" name="end_date" value="{{ request('end_date') }}" class="border p-2 rounded">

    <button class="bg-blue-600 text-white px-3 py-2 rounded  hover:bg-blue-800 transition">
        Filter
    </button>

    <a href="/transactions?month=1"
        class="bg-green-600 text-white px-3 py-2 rounded  hover:bg-green-800 transition">
        This Month
    </a>

    <a href="/transactions/print?company={{ request('company') }}&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}"
        target="_blank"
        class="bg-orange-600 text-white px-3 py-2 rounded  hover:bg-orange-800 transition">
        Print Filtered
    </a>

    <a href="/transactions/download-filtered?company={{ request('company') }}&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}"
        class="bg-purple-600 text-white px-3 py-2 rounded  hover:bg-purple-800 transition">
        Download PDF
    </a>
</form>


<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-200 text-sm">
            <th class="p-2">Transaction No</th>
            <th class="p-2">Plate No</th>
            <th class="p-2">Driver Name</th>
            <!--th-- class="p-2">Representative Name</!--th-->
            <th class="p-2">Company</th>
            <th class="p-2">Address</th>
            <th class="p-2">Material</th>
            <!--th class="p-2">Gross Wt.</!--th>
            <th-- class="p-2">Tare Wt.</th-->
            <th class="p-2">Net Wt.</th>
            <th class="p-2">Clerk</th>
            <!--th class="p-2">Approved</!--th-->
            <th class="p-2">Action</th>
        </tr>
    </thead>

    <tbody>
    @forelse($transactions as $t)
    <tr class="border-t text-center text-sm">

        <td class="p-2">{{ $t->transaction_no }}</td>
        <td class="p-2">{{ $t->plate_number }}</td>
        <td class="p-2">{{ $t->driver_name }}</td>
        <!--td-- class="p-2">{{ $t->representative_name }}</!--td-->
        <td class="p-2">{{ $t->company }}</td>
        <td class="p-2">{{ $t->address }}</td>
        <td class="p-2">{{ $t->material }}</td>
        <!--td class="p-2">{{ $t->gross_weight }}</!--td>
        <td-- class="p-2">{{ $t->tare_weight }}</td-->
        <td class="p-2 font-semibold">{{ $t->net_weight }}</td>
        <td class="p-2">{{ $t->clerk }}</td>
        <!--td class="p-2">{{ $t->approved_by }}</!--td-->

        <td class="p-2">
            <a href="/print/{{ $t->id }}" target="_blank"
            class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
            Print
            </a>
        </td>
    </tr>
        @empty
        <tr>
            <td colspan="12" class="p-4 text-center text-gray-500">
                No transactions yet.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
</div>

@endsection