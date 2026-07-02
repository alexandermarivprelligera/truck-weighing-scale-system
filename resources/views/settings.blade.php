@extends('layout')

@section('title', 'Settings')

@section('content')

<h2 class="text-lg font-semibold mb-4">Settings</h2>

<p class="text-gray-600">System configuration here.</p>

<button onclick="toggleDark()" class="px-3 py-1 bg-gray-800 text-white rounded">
    Toggle Theme
</button>

<script>
function toggleDark() {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme',
        document.documentElement.classList.contains('dark') ? 'dark' : 'light'
    );
}

if (localStorage.getItem('theme') === 'dark') {
    document.documentElement.classList.add('dark');
}
</script>

<body class="bg-white dark:bg-gray-900 text-black dark:text-white">

@endsection