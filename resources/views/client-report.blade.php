@extends('layout')

@section('content')

    <div class="space-y-4 mb-6">
        <a href="/dashboard" class="bg-gray-500 text-white px-4 py-2 rounded">
            ← Back
        </a>
    </div>

<h2 class="text-2xl font-bold mb-4">
Client List
</h2>

<form method="POST" action="/client-report/save">

@csrf

<div class="overflow-x-auto">
    <table class="w-full border">
        <thead>
            <tr class="bg-gray-200">
                <th>Branch Code</th>
                <th>Branch Name</th>
                <th>Address</th>
                <th>Mayor</th>
                <th>TIN</th>
                <th>Contact Person</th>
                <th>Contact Number</th>
            </tr>
        </thead>

        <tbody>
            @foreach($clients as $client)
            <tr>

                <td>
                    <input type="text" name="clients[{{$client->id}}][branch_code]" value="{{$client->branch_code}}" class="border p-1 w-full">
                </td>

                <td>
                    <input type="text" name="clients[{{$client->id}}][company]" value="{{$client->company}}" class="border p-1 w-full">
                </td>

                <td>
                    <input type="text" name="clients[{{$client->id}}][address]" value="{{$client->address}}" class="border p-1 w-full">
                </td>

                <td>
                    <input type="text" name="clients[{{$client->id}}][mayor]" value="{{$client->mayor}}" class="border p-1 w-full">
                </td>

                <td>
                    <input type="text" name="clients[{{$client->id}}][tin_number]" value="{{$client->tin_number}}" class="border p-1 w-full">
                </td>

                <td>
                    <input type="text" name="clients[{{$client->id}}][contact_person]" value="{{$client->contact_person}}" class="border p-1 w-full">
                </td>

                <td>
                    <input type="text" name="clients[{{$client->id}}][contact_number]" value="{{$client->contact_number}}" class="border p-1 w-full">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
    Save Changes
</button>

</form>
@endsection