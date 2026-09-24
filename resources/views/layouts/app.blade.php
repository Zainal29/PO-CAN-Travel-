<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ auth()->user()?->role === 'admin' ? 'Admin' : 'Profil' }} — PO CAN Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans antialiased text-slate-800">
    @if(auth()->user()?->role === 'admin')
        @include('layouts.navigation')
    @else
        <nav class="border-b border-slate-200 bg-white"><div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4 sm:px-6"><a href="{{ route('customer.dashboard') }}" class="font-bold text-slate-950">PO CAN Travel</a><a href="{{ route('customer.dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-slate-950">Kembali ke dashboard</a></div></nav>
    @endif
    @if(isset($header))<header class="border-b border-slate-200 bg-white"><div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">{{ $header }}</div></header>@endif
    <main>{{ $slot }}</main>
    @stack('scripts')
</body>
</html>
