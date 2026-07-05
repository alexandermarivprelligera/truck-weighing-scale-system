@extends('layout')

@section('title', 'Client Registry')

@section('content')

    <div class="space-y-4 mb-6">
        <a href="/dashboard" class="bg-gray-500 text-white px-4 py-2 rounded">
            ← Back
        </a>
    </div>

<h2 class="text-xl font-bold mb-4">Client Registry</h2>

<!-- Register Client -->
<form method="POST" action="/clients" class="space-y-4 mb-6">
    @csrf
    
    <div class="grid grid-cols-3 gap-4">
        <input type="text" name="branch_code" placeholder="Branch Code" class="w-full p-2 border rounded">
        
        <input type="text" name="company" placeholder="Branch Name" class="w-full p-2 border rounded">

        <input type="text" name="address" placeholder="Address" class="w-full p-2 border rounded">

        <input type="text" name="tin_number" placeholder="TIN Number" class="w-full p-2 border rounded">

        <input type="text" name="contact_person" placeholder="Contact Person" class="w-full p-2 border rounded">

        <input type="text" name="contact_number" placeholder="Contact Number" class="w-full p-2 border rounded">

        <input type="text" name="mayor" placeholder="Mayor/Owner" class="w-full p-2 border rounded">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
        Register Client
    </button>
</form>

<!--Search Filter-->
<form method="GET" action="/clients" class="flex gap-2 mb-4">

    <input type="text"
        name="search"
        placeholder="Search company..."
        value="{{ request('search') }}"
        class="border p-2 rounded w-full">

    <button type="submit"
        class="bg-green-600 text-white px-4 rounded">

        Search

    </button>

</form>

<!-- Client List -->
<!--div class="bg-white rounded shadow overflow-hidden">

    <table class="w-full">

        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 text-left">Company</th>
                <th class="p-2 text-left">Address</th>
                <th class="p-2 text-left">Action</th>
            </tr>
        </thead>

        <tbody>
        @forelse($clients as $client)

            <tr class="border-t">
                <td class="p-2">{{ $client->company }}</td>
                <td class="p-2">{{ $client->address }}</td>

                <td class="p-2">
                
                <div class="flex gap-2">
                    <a href="/clients/{{ $client->id }}" class="bg-green-500 text-white px-4 py-2 rounded">
                        View
                    </a>
                    <a href="/clients/{{ $client->id }}/print" target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded">
                        Print
                    </a>
                    <a href="/clients/{{ $client->id }}/download" class="bg-purple-500 text-white px-4 py-2 rounded">
                        Download
                    </a>
                    <form action="/clients/{{ $client->id }}" method="POST" onsubmit="return confirm('Delete this client?')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 text-white px-4 py-2 rounded">
                            Delete
                        </button>
                    </form>
                </div>
                </td>
            </tr>

        @empty

            <tr>
                <td colspan="3" class="p-4 text-center text-gray-500">
                    No clients registered.
                </td>
            </tr>

        @endforelse
        </tbody>

    </table>

</!--div-->

<!-- Client Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($clients as $client)
    <div class="bg-white rounded-xl shadow-lg border p-5 hover:shadow-xl transition">

        <!-- Company -->
        <div class="border-b pb-3 mb-3">
            <h2 class="text-xl font-bold text-blue-700">
                {{ $client->company }}
            </h2>
        </div>

        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="font-semibold">Branch Code</span>
                <span>{{ $client->branch_code ?? 'N/A' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-semibold">TIN</span>
                <span>{{ $client->tin_number ?? 'N/A' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-semibold">Address</span><br>
                <span>{{ $client->address ?? 'N/A' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-semibold">Mayor</span>
                <span>{{ $client->mayor ?? 'N/A' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-semibold">Contact Person</span>
                <span>{{ $client->contact_person ?? 'N/A' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-semibold">Contact Number</span>
                <span>{{ $client->contact_number ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="border-t mt-4 pt-4 flex flex-wrap gap-2">
            <!--a-- href="/clients/{{ $client->id }}"
                class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                View
            </!--a-->

            <!--a href="/clients/{{ $client->id }}/print"
                target="_blank"
                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm">
                Print
            </!--a-->

            <!--a href="/clients/{{ $client->id }}/download"
                class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-sm">
                Download
            </!--a-->

            <form action="/clients/{{ $client->id }}"
                method="POST"
                onsubmit="return confirm('Delete this client?')">
                @csrf
                @method('DELETE')
                <button
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm">
                    Delete
                </button>
            </form>
        </div>

    </div>

    @empty

    <div class="col-span-full text-center text-gray-500 bg-white rounded shadow p-8">
        No clients registered.
    </div>

    @endforelse

    </div>

@endsection