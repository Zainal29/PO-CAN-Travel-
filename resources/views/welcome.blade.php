<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PO CAN Travel — platform pemesanan tiket bus.">
    <title>PO CAN Travel — Pemesanan Tiket Bus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-900 antialiased">
    <header class="relative z-20 bg-slate-950 text-white">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 sm:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-400 text-base font-black text-slate-950">C</span><span><span class="block text-lg font-bold leading-none">PO CAN Travel</span><span class="mt-1 block text-[10px] font-semibold tracking-[0.18em] text-slate-400">BUS TICKET BOOKING</span></span></a>
            <div class="flex items-center gap-2 text-sm font-semibold">
                <a href="{{ route('customer.trips.index') }}" class="hidden rounded-lg px-3 py-2 text-slate-300 hover:text-white sm:block">Jadwal perjalanan</a>
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('customer.dashboard') }}" class="rounded-lg bg-white px-4 py-2.5 text-slate-950 hover:bg-slate-100">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-slate-200 hover:text-white">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-amber-400 px-4 py-2.5 text-slate-950 hover:bg-amber-300">Daftar</a>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        <section class="overflow-hidden bg-slate-950 pb-16 text-white sm:pb-20">
            <div class="mx-auto grid max-w-7xl gap-12 px-5 pt-10 sm:px-8 lg:grid-cols-[1.05fr_.95fr] lg:items-center">
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold tracking-[0.16em] text-amber-300">PESAN TIKET BUS SECARA ONLINE</p>
                    <h1 class="mt-4 text-4xl font-black leading-[1.08] sm:text-5xl">Pilih perjalanan yang sesuai. Pesan kursi tanpa antre.</h1>
                    <p class="mt-5 max-w-xl text-base leading-7 text-slate-300">Cari jadwal berdasarkan kota dan tanggal, pilih kursi, lalu kelola pembayaran serta pesanan Anda dalam satu akun.</p>
                    <form method="GET" action="{{ route('customer.trips.index') }}" class="mt-8 rounded-xl bg-white p-4 text-slate-900 shadow-2xl shadow-black/30">
                        <div class="grid gap-3 md:grid-cols-2"><div><label for="origin_city" class="mb-1.5 block text-xs font-bold text-slate-600">KOTA ASAL</label><input id="origin_city" name="origin_city" required placeholder="Contoh: Jepara" class="w-full rounded-lg border-slate-200 text-sm focus:border-amber-500 focus:ring-amber-500"></div><div><label for="destination_city" class="mb-1.5 block text-xs font-bold text-slate-600">KOTA TUJUAN</label><input id="destination_city" name="destination_city" required placeholder="Contoh: Semarang" class="w-full rounded-lg border-slate-200 text-sm focus:border-amber-500 focus:ring-amber-500"></div><div><label for="departure_date" class="mb-1.5 block text-xs font-bold text-slate-600">TANGGAL BERANGKAT</label><input id="departure_date" name="departure_date" type="date" min="{{ now()->toDateString() }}" required class="w-full rounded-lg border-slate-200 text-sm focus:border-amber-500 focus:ring-amber-500"></div><div class="flex items-end"><button class="w-full rounded-lg bg-amber-400 px-5 py-2.5 text-sm font-bold text-slate-950 hover:bg-amber-300">Cari perjalanan</button></div></div>
                    </form>
                </div>
                <div class="relative mx-auto w-full max-w-xl">
                    <div class="absolute -inset-12 rounded-full bg-amber-400/10 blur-3xl"></div>
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-slate-900 p-5 shadow-2xl">
                        <div class="flex items-center justify-between border-b border-slate-700 pb-4"><span class="text-sm font-semibold text-slate-300">Jadwal keberangkatan</span><span class="rounded bg-emerald-400/15 px-2 py-1 text-xs font-bold text-emerald-300">Tersedia</span></div>
                        <div class="py-7"><div class="grid grid-cols-[1fr_auto_1fr] items-center gap-3"><div><p class="text-3xl font-black">08.00</p><p class="mt-1 text-sm text-slate-400">Terminal asal</p></div><div class="flex flex-col items-center gap-2"><span class="h-2 w-2 rounded-full bg-amber-400"></span><span class="h-px w-16 bg-slate-600"></span><span class="h-2 w-2 rounded-full border-2 border-amber-400"></span></div><div class="text-right"><p class="text-3xl font-black">11.00</p><p class="mt-1 text-sm text-slate-400">Terminal tujuan</p></div></div></div>
                        <div class="grid grid-cols-3 gap-2 border-t border-slate-700 pt-4 text-center text-xs"><div><p class="font-bold text-white">Kursi</p><p class="mt-1 text-slate-400">Pilih sendiri</p></div><div class="border-x border-slate-700"><p class="font-bold text-white">Pembayaran</p><p class="mt-1 text-slate-400">Unggah bukti</p></div><div><p class="font-bold text-white">Order</p><p class="mt-1 text-slate-400">Pantau status</p></div></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-b border-slate-200 bg-slate-50"><div class="mx-auto grid max-w-7xl gap-6 px-5 py-7 sm:grid-cols-3 sm:px-8"><div class="flex gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-sm font-black text-amber-700">1</span><div><h2 class="font-bold">Pilih jadwal</h2><p class="mt-1 text-sm text-slate-600">Temukan rute dan waktu yang Anda butuhkan.</p></div></div><div class="flex gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-sm font-black text-amber-700">2</span><div><h2 class="font-bold">Pilih kursi</h2><p class="mt-1 text-sm text-slate-600">Kursi yang sudah dipesan tidak dapat dipilih lagi.</p></div></div><div class="flex gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-sm font-black text-amber-700">3</span><div><h2 class="font-bold">Selesaikan pembayaran</h2><p class="mt-1 text-sm text-slate-600">Kirim bukti pembayaran lalu pantau statusnya.</p></div></div></div></section>

        <section class="mx-auto max-w-7xl px-5 py-16 sm:px-8"><div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"><div><p class="text-sm font-bold tracking-wide text-amber-700">JADWAL TERDEKAT</p><h2 class="mt-2 text-3xl font-black">Perjalanan yang dapat dipesan</h2></div><a href="{{ route('customer.trips.index') }}" class="w-fit text-sm font-bold text-slate-900 underline decoration-amber-400 decoration-2 underline-offset-4">Lihat semua jadwal</a></div>
            <div class="mt-8 grid gap-5 lg:grid-cols-3">@forelse($featuredRoutes as $travelRoute)<article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><span class="rounded bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">{{ strtoupper(str_replace('_', ' ', $travelRoute->bus->bus_type)) }}</span><span class="text-xs font-semibold text-emerald-700">{{ $travelRoute->available_seats }} kursi tersedia</span></div><h3 class="mt-5 text-xl font-black">{{ $travelRoute->origin_city }} <span class="text-amber-600">→</span> {{ $travelRoute->destination_city }}</h3><p class="mt-2 text-sm text-slate-500">{{ $travelRoute->bus->bus_name }}</p><div class="mt-5 grid grid-cols-2 border-y border-slate-100 py-4"><div><p class="text-xs font-bold text-slate-400">BERANGKAT</p><p class="mt-1 font-bold">{{ \Carbon\Carbon::parse($travelRoute->departure_time)->format('H:i') }}</p></div><div><p class="text-xs font-bold text-slate-400">TANGGAL</p><p class="mt-1 font-bold">{{ $travelRoute->departure_date->format('d M Y') }}</p></div></div><div class="mt-5 flex items-end justify-between"><div><p class="text-xs text-slate-500">Mulai dari</p><p class="text-lg font-black">Rp {{ number_format($travelRoute->price, 0, ',', '.') }}</p></div><a href="{{ route('customer.trips.show', $travelRoute) }}" class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-bold text-white hover:bg-slate-700">Detail</a></div></article>@empty<div class="lg:col-span-3 rounded-2xl border border-dashed border-slate-300 p-8 text-center"><h3 class="font-bold">Jadwal belum tersedia</h3><p class="mt-2 text-sm text-slate-600">Admin dapat menambahkan perjalanan melalui panel admin.</p></div>@endforelse</div>
        </section>

        <section class="bg-slate-950 py-16 text-white"><div class="mx-auto max-w-7xl px-5 sm:px-8"><div class="max-w-2xl"><p class="text-sm font-bold tracking-wide text-amber-300">ARMADA</p><h2 class="mt-2 text-3xl font-black">Bus yang digunakan untuk perjalanan Anda</h2><p class="mt-3 leading-7 text-slate-300">Informasi tipe bus, kapasitas, dan fasilitas tersedia di setiap detail perjalanan.</p></div><div class="mt-8 grid gap-4 md:grid-cols-3">@forelse($fleet as $bus)<article class="border border-slate-700 bg-slate-900 p-5"><div class="flex items-center justify-between"><span class="text-xs font-bold tracking-wide text-amber-300">{{ $bus->bus_code }}</span><span class="text-xs text-slate-400">{{ $bus->total_seats }} kursi</span></div><h3 class="mt-5 text-xl font-bold">{{ $bus->bus_name }}</h3><p class="mt-2 text-sm text-slate-400">{{ ucfirst(str_replace('_', ' ', $bus->bus_type)) }} · {{ $bus->plate_number }}</p><div class="mt-5 border-t border-slate-700 pt-4 text-sm text-slate-300">{{ !empty($bus->facilities) ? implode(' · ', array_slice($bus->facilities, 0, 3)) : 'Informasi fasilitas tersedia pada detail perjalanan.' }}</div></article>@empty<div class="border border-slate-700 p-5 text-sm text-slate-400 md:col-span-3">Data armada akan tampil setelah ditambahkan admin.</div>@endforelse</div></div></section>
    </main>
    <footer class="bg-slate-900 text-slate-400"><div class="mx-auto flex max-w-7xl flex-col gap-3 px-5 py-8 text-sm sm:flex-row sm:items-center sm:justify-between sm:px-8"><div><p class="font-bold text-white">PO CAN Travel</p><p class="mt-1">Platform pemesanan tiket bus.</p></div><div class="flex gap-5"><a href="{{ route('customer.trips.index') }}" class="hover:text-white">Jadwal perjalanan</a><a href="{{ route('login') }}" class="hover:text-white">Masuk</a></div></div></footer>
</body>
</html>
