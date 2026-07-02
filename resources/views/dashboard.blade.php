@extends('layout')

@section('title', 'Dashboard')

@section('content')

<h2 class="flex justify-center text-5xl font-semibold mb-6">DASHBOARD</h2>

<div class="bg-gray-900 text-green-400 rounded-xl shadow-lg p-6 text-center mb-8">
    <div id="clock" class="text-5xl font-bold"></div>
    <div id="date" class="text-lg mt-2"></div>
</div>

<div class="grid grid-cols-2 gap-6">
        <a href="/transactions/create" class="bg-blue-600 text-white p-8 rounded-xl shadow hover:scale-105 transition">
            <h2 class="text-2xl font-bold">
            New Transaction
            </h2>
            <p>Create a new weighing transaction</p>
        </a>

        <a href="/transactions" class="bg-green-600 text-white p-8 rounded-xl shadow hover:scale-105 transition">
            <h2 class="text-2xl font-bold">
            Transactions
            </h2>
            <p>View all transactions</p>
        </a>

        <a href="/clients" class="bg-purple-600 text-white p-8 rounded-xl shadow hover:scale-105 transition">
            <h2 class="text-2xl font-bold">
            Client Registry
            </h2>
            <p>Add clients and manage registered ones</p>
        </a>

        <a href="/client-report" class="bg-orange-600 text-white p-8 rounded-xl shadow hover:scale-105 transition">
            <h2 class="text-2xl font-bold">
            Client List
            </h2>
            <p>Edit and manage client details</p>
        </a>

</div>

<script>
function updateClock(){
    let now = new Date();
    let time = now.toLocaleTimeString();
    let date = now.toLocaleDateString('en-US',
            {
                weekday:'long',
                year:'numeric',
                month:'long',
                day:'numeric'
            }
        );
    document.getElementById('clock')
        .innerText = time;
    document.getElementById('date')
        .innerText = date;
}
updateClock();
setInterval(updateClock, 1000);
</script>


@endsection