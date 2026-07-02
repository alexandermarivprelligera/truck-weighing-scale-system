<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Truck Weighing Scale System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--Tab Icon-->
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">

    <!-- Tailwind (CDN for now) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!--Icons-->
    <!--script src="https://unpkg.com/feather-icons"></!--script-->

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100">

<div class="min-h-screen">

    <!-- Sidebar -->
    <!--aside-- class="group w-20 hover:w-64 transition-all duration-50 bg-gray-900 text-white overflow-hidden min-h-screen">
    <div class="p-4">
    <div class="flex justify-center group-hover:justify-start">

    <img src="{{ asset('images/icon.png') }}" class="w-10 h-10">
        <span class="hidden group-hover:block ml-3 text-xl font-bold whitespace-inherit">
            Truck Weighing Scale System
        </span>
</div>

        <nav class="space-y-3 mt-10">
            <a href="/dashboard"
            class="flex items-center gap-3 p-2 rounded transition {{ request()->is('dashboard') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
            <i data-feather="home"></i>
            <span class="menu-text hidden group-hover:block whitespace-nowrap">Dashboard</span>
            </a>

            <a href="/transactions"
            class="flex items-center gap-3 p-2 rounded transition {{ request()->is('transactions*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
            <i data-feather="clipboard"></i>
            <span class="menu-text hidden group-hover:block whitespace-nowrap">Transactions</span>
            </a>

            <a href="/transactions/create"
            class="flex items-center gap-3 p-2 rounded transition {{ request()->is('transactions/create') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
            <i data-feather="plus-circle"></i>
            <span class="menu-text hidden group-hover:block whitespace-nowrap">New Transaction</span>
            </a>

            <a href="/clients"
            class="flex items-center gap-3 p-2 rounded transition {{ request()->is('clients') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
            <i data-feather="users"></i>
            <span class="menu-text hidden group-hover:block whitespace-nowrap">Clients</span>
            </a>

            <a href="/settings"
            class="flex items-center gap-3 p-2 rounded transition {{ request()->is('settings') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
            <i data-feather="settings"></i>
            <span class="menu-text hidden group-hover:block whitespace-nowrap">Settings</span>
            </a>
        </nav>
    </aside-->

    <!-- Main Content -->
    <div class="flex flex-col min-h-screen">

        <!-- Header -->
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <a href='/dashboard'><img src="/images/logo.png" style="width: 155px; height: auto;" alt="Company Logo"></a>
            <h1 class="text-3xl font-bold">
                TRUCK WEIGHING SCALE SYSTEM
            </h1>

            <!--div>
                <span class="text-sm text-gray-600">Operator</span>
            </div-->

            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">
                    {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-red-500 text-white px-3 py-1 rounded">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>

</div>

<script>
    //feather.replace();

/*    const sidebar = document.getElementById('sidebar');
    sidebar.addEventListener('mouseenter', () => {
        document.querySelectorAll('.menu-text')
        .forEach(item => item.classList.remove('hidden'));
        title.style.display = 'block';

    });
    sidebar.addEventListener('mouseleave', () => {
        document.querySelectorAll('.menu-text')
        .forEach(item => item.classList.add('hidden'));
        title.style.display = 'none';

    });*/
</script>

</body>
</html>