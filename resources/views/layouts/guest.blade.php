<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PO CAN Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <main class="grid min-h-screen lg:grid-cols-2">
        <section class="hidden bg-brand-950 p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 text-xl font-bold"><span class="flex h-10 w-10 items-center justify-center rounded-md bg-amber-400 text-brand-950">C</span>PO CAN Travel</a>
            <div class="max-w-md"><p class="text-sm font-semibold tracking-widest text-amber-300">PESAN TIKET BUS</p><h1 class="mt-4 text-4xl font-bold leading-tight">Perjalanan dimulai dari jadwal yang tepat.</h1><p class="mt-5 text-base leading-7 text-slate-300">Cari perjalanan, pilih kursi, dan pantau pesanan Anda secara sederhana.</p></div>
            <p class="text-sm text-slate-400">Bus ticket booking system</p>
        </section>
        <section class="flex items-center justify-center px-5 py-10 sm:px-8"><div class="w-full max-w-md"><a href="{{ url('/') }}" class="mb-10 flex items-center gap-2 font-bold text-brand-950 lg:hidden"><span class="flex h-8 w-8 items-center justify-center rounded-md bg-brand-950 text-sm text-amber-300">C</span>PO CAN Travel</a><div class="border border-slate-200 bg-white p-6 sm:p-8">{{ $slot }}</div></div></section>
    </main>
</body>
</html>
