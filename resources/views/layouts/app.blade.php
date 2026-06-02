<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TCG Manager') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased" style="font-family: 'Inter', sans-serif;">

    @include('layouts.navigation')

    {{-- Flash messages: driven by controller return back()->with('success'/'error', '...') --}}
    @if(session('success'))
        <div class="max-w-screen-2xl mx-auto px-8 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-screen-2xl mx-auto px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="max-w-screen-2xl mx-auto px-8 py-8">
        {{ $slot }}
    </main>

    <footer class="border-t border-gray-200 mt-16 py-8 text-center text-sm text-gray-400">
        <p>TCG Manager</p>
    </footer>

</body>
</html>
