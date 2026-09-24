<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PO CAN Travel — Platform pemesanan tiket bus antarkota resmi. Jadwal akurat, pilihan kursi langsung, dan e-ticket instan dengan QR code.">
    <title>{{ $settings['app_name'] ?? 'PO CAN Travel' }} — Pemesanan Tiket Bus Resmi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased selection:bg-blue-600 selection:text-white">

    {{-- 1. NAVBAR (Clean, putih, sticky, border tipis) --}}
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-xs">
        <nav class="site-shell flex h-16 items-center justify-between gap-4">
            {{-- Logo PO CAN Travel --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <div class="h-9 w-9 rounded-lg bg-blue-900 p-1 flex items-center justify-center shadow-xs">
                    <img src="{{ asset('storage/images/LOGO-CAN-TRAVEL.jpeg') }}" 
                         alt="Logo PO CAN Travel" 
                         class="h-full w-full object-contain rounded"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <span class="hidden h-full w-full items-center justify-center font-black text-white text-sm">C</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-base font-extrabold tracking-tight text-slate-900">
                        {{ $settings['app_name'] ?? 'PO CAN Travel' }}
                    </span>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                        Tiket Bus Antarkota
                    </span>
                </div>
            </a>

            {{-- Navigasi Sesuai Role --}}
            <div class="flex items-center gap-2 sm:gap-4 text-sm font-semibold">
                {{-- Jadwal Bus (selalu tampil) --}}
                <a href="{{ route('customer.trips.index') }}" 
                   class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">
                    Jadwal Bus
                </a>

                @auth
                    @if(auth()->user()->role === 'admin')
                        {{-- Admin: Kembali ke Admin (TIDAK BOLEH ke /customer/*) --}}
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Kembali ke Admin</span>
                        </a>
                    @else
                        {{-- Customer: Beranda + Pesanan + Profil --}}
                        <a href="{{ route('home') }}" 
                           class="hidden md:inline-flex rounded-lg px-3 py-2 text-slate-900 bg-slate-100 transition">
                            Beranda
                        </a>
                        <a href="{{ route('customer.orders.index') }}" 
                           class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">
                            Pesanan
                        </a>
                        <a href="{{ route('profile.edit') }}" 
                           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-xs">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Profil</span>
                        </a>
                    @endif
                @else
                    {{-- Guest: Masuk + Daftar --}}
                    <a href="{{ route('login') }}" 
                       class="rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" 
                       class="rounded-lg bg-blue-600 hover:bg-blue-700 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition">
                        Daftar
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        {{-- 2. HERO + SEARCH (Search menjadi focal point, visual foto bus relevan, biru-putih-slate) --}}
        <section class="relative border-b border-slate-200 bg-slate-900 text-white overflow-hidden">
            {{-- Background Bus Image dengan Overlay Biru Gelap --}}
            @php
                $heroBg = !empty($settings['hero_image']) ? asset('storage/' . $settings['hero_image']) : asset('images/hero-bus.jpg');
                $heroBadge = $settings['hero_badge'] ?? 'Tiket Resmi Bus Antarkota';
                $heroTitle = $settings['hero_title'] ?? 'Perjalanan Anda, dimulai dari jadwal yang tepat.';
                $heroSubtitle = $settings['hero_subtitle'] ?? 'Pesan tiket bus antarkota resmi PO CAN Travel dengan jadwal terkonfirmasi, kepastian nomor kursi pilihan sendiri, dan kemudahan e-ticket instan.';
            @endphp
            <div class="absolute inset-0 z-0">
                <img src="{{ $heroBg }}" 
                     alt="Armada Bus {{ $settings['app_name'] ?? 'PO CAN Travel' }}" 
                     class="w-full h-full object-cover object-center opacity-40">
                <div class="absolute inset-0 bg-slate-900/80"></div>
            </div>

            <div class="relative z-10 site-shell py-12 sm:py-16 lg:py-20">
                <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                    {{-- Kiri: Headline kuat, deskripsi singkat, feature kecil --}}
                    <div class="max-w-2xl space-y-5">
                        <div class="inline-flex items-center gap-2 rounded-md bg-blue-900/80 border border-blue-700/60 px-3 py-1 text-xs font-semibold text-blue-200">
                            <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                            <span>{{ $heroBadge }}</span>
                        </div>

                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                            {{ $heroTitle }}
                        </h1>

                        <p class="text-sm sm:text-base leading-relaxed text-slate-300 max-w-xl">
                            {{ $heroSubtitle }}
                        </p>

                        {{-- Feature Kecil --}}
                        <div class="flex flex-wrap items-center gap-4 pt-2 text-xs font-medium text-slate-200">
                            <div class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Pilihan Kursi Nyata</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>E-Ticket QR Resmi</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Armada Terawat</span>
                            </div>
                        </div>
                    </div>

                    {{-- Kanan: Search Card (Focal Point: Putih, border, shadow ringan, radius moderat) --}}
                    <div id="search-card" 
                         class="rounded-xl border border-slate-200 bg-white p-5 sm:p-7 shadow-md text-slate-900" 
                         x-data="cityAutocomplete()">
                        <div class="mb-5 border-b border-slate-100 pb-3">
                            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Cari perjalanan</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Tentukan rute bus dan tanggal keberangkatan Anda.</p>
                        </div>

                        {{-- Form Search: Route & Fields dari SearchTripRequest --}}
                        <form method="GET" action="{{ route('customer.trips.index') }}" class="space-y-4">
                            <div class="grid gap-3 sm:grid-cols-2">
                                {{-- Kota Asal --}}
                                <div class="relative" @click.outside="closeSuggestions('origin_city')">
                                    <label for="origin_city" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                        Kota Asal
                                    </label>
                                    <div class="relative">
                                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="10" r="3"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0z"/>
                                            </svg>
                                        </span>
                                        <input id="origin_city" 
                                               name="origin_city" 
                                               type="text" 
                                               required
                                               placeholder="Contoh: Jepara"
                                               autocomplete="off"
                                               x-on:input="searchCities('origin_city', $event.target.value)"
                                               x-on:focus="searchCities('origin_city', $event.target.value)"
                                               class="w-full rounded-lg border-slate-300 py-2.5 pl-9 pr-3 text-sm font-medium text-slate-900 focus:border-blue-600 focus:ring-blue-600">
                                    </div>
                                    <div x-cloak x-show="activeField === 'origin_city' && suggestions.length" 
                                         class="absolute inset-x-0 top-full z-20 mt-1 max-h-48 overflow-y-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                                        <template x-for="city in suggestions" :key="city">
                                            <button type="button" 
                                                    x-on:click="selectCity('origin_city', city)" 
                                                    class="block w-full px-3 py-2 text-left text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700" 
                                                    x-text="city"></button>
                                        </template>
                                    </div>
                                </div>

                                {{-- Kota Tujuan --}}
                                <div class="relative" @click.outside="closeSuggestions('destination_city')">
                                    <label for="destination_city" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                        Kota Tujuan
                                    </label>
                                    <div class="relative">
                                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </span>
                                        <input id="destination_city" 
                                               name="destination_city" 
                                               type="text" 
                                               required
                                               placeholder="Contoh: Semarang"
                                               autocomplete="off"
                                               x-on:input="searchCities('destination_city', $event.target.value)"
                                               x-on:focus="searchCities('destination_city', $event.target.value)"
                                               class="w-full rounded-lg border-slate-300 py-2.5 pl-9 pr-3 text-sm font-medium text-slate-900 focus:border-blue-600 focus:ring-blue-600">
                                    </div>
                                    <div x-cloak x-show="activeField === 'destination_city' && suggestions.length" 
                                         class="absolute inset-x-0 top-full z-20 mt-1 max-h-48 overflow-y-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                                        <template x-for="city in suggestions" :key="city">
                                            <button type="button" 
                                                    x-on:click="selectCity('destination_city', city)" 
                                                    class="block w-full px-3 py-2 text-left text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700" 
                                                    x-text="city"></button>
                                        </template>
                                    </div>
                                </div>

                                {{-- Tanggal Berangkat --}}
                                <div>
                                    <label for="departure_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                        Tanggal Berangkat
                                    </label>
                                    <input id="departure_date" 
                                           name="departure_date" 
                                           type="date" 
                                           required
                                           min="{{ \Carbon\Carbon::now('Asia/Jakarta')->toDateString() }}"
                                           value="{{ \Carbon\Carbon::now('Asia/Jakarta')->toDateString() }}"
                                           class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm font-medium text-slate-900 focus:border-blue-600 focus:ring-blue-600">
                                </div>

                                {{-- Penumpang --}}
                                <div>
                                    <label for="passengers" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                        Penumpang
                                    </label>
                                    <select id="passengers" 
                                            name="passengers" 
                                            class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm font-medium text-slate-900 focus:border-blue-600 focus:ring-blue-600">
                                        <option value="1">1 Penumpang</option>
                                        <option value="2">2 Penumpang</option>
                                        <option value="3">3 Penumpang</option>
                                        <option value="4">4 Penumpang</option>
                                        <option value="5">5 Penumpang</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Tipe Bus (Filter backend) --}}
                            <div>
                                <label for="bus_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Kelas Armada
                                </label>
                                <select id="bus_type" 
                                        name="bus_type" 
                                        class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm font-medium text-slate-900 focus:border-blue-600 focus:ring-blue-600">
                                    <option value="">Semua Kelas</option>
                                    <option value="economy">Economy</option>
                                    <option value="executive">Executive</option>
                                    <option value="vip">VIP</option>
                                    <option value="super_vip">Super VIP</option>
                                </select>
                            </div>

                            {{-- Tombol Cari Jadwal --}}
                            <button type="submit" 
                                    class="w-full flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-3 text-sm font-bold text-white shadow-xs transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35"/>
                                </svg>
                                <span>Cari Jadwal</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        {{-- 3. JADWAL BUS (Data database, card terasa seperti tiket dengan visual rute vertikal) --}}
        <section id="jadwal-bus" class="site-shell py-12 sm:py-16">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between border-b border-slate-200 pb-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        Jadwal Bus
                    </h2>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500">
                        Pilihan jadwal keberangkatan bus yang tersedia dan dapat dipesan hari ini.
                    </p>
                </div>
                <a href="{{ route('customer.trips.index') }}" 
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                    <span>Lihat Semua Jadwal</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Grid Tiket Bus --}}
            <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @php
                    $jakartaNow = \Carbon\Carbon::now('Asia/Jakarta');
                @endphp

                @forelse($featuredRoutes as $route)
                    @php
                        $depDateStr = $route->departure_date instanceof \Carbon\Carbon 
                            ? $route->departure_date->format('Y-m-d') 
                            : \Carbon\Carbon::parse($route->departure_date)->format('Y-m-d');
                        
                        $departureDt = \Carbon\Carbon::parse($depDateStr . ' ' . $route->departure_time, 'Asia/Jakarta');
                        
                        $arrivalDt = $route->estimated_arrival_time 
                            ? \Carbon\Carbon::parse($depDateStr . ' ' . $route->estimated_arrival_time, 'Asia/Jakarta')
                            : (clone $departureDt)->addHours(3);

                        if ($arrivalDt->lessThan($departureDt)) {
                            $arrivalDt->addDay();
                        }

                        // Konteks waktu keberangkatan sesuai instruksi:
                        // * Berangkat hari ini
                        // * Berangkat besok
                        // * Berangkat dalam X jam
                        // * Perjalanan telah dimulai
                        // * Keberangkatan telah lewat
                        if ($jakartaNow->greaterThan($arrivalDt)) {
                            $timeContext = 'Keberangkatan telah lewat';
                            $timeBadgeClass = 'bg-slate-100 text-slate-600 border border-slate-200';
                        } elseif ($jakartaNow->greaterThanOrEqualTo($departureDt) && $jakartaNow->lessThanOrEqualTo($arrivalDt)) {
                            $timeContext = 'Perjalanan telah dimulai';
                            $timeBadgeClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                        } elseif ($jakartaNow->diffInHours($departureDt, false) >= 1 && $jakartaNow->diffInHours($departureDt, false) <= 12) {
                            $diffHours = (int) $jakartaNow->diffInHours($departureDt, false);
                            $timeContext = "Berangkat dalam {$diffHours} jam";
                            $timeBadgeClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                        } elseif ($departureDt->isToday('Asia/Jakarta')) {
                            $timeContext = 'Berangkat hari ini';
                            $timeBadgeClass = 'bg-blue-50 text-blue-700 border border-blue-200';
                        } elseif ($departureDt->isTomorrow('Asia/Jakarta')) {
                            $timeContext = 'Berangkat besok';
                            $timeBadgeClass = 'bg-blue-50 text-blue-700 border border-blue-200';
                        } else {
                            $timeContext = 'Berangkat ' . $departureDt->locale('id')->isoFormat('D MMM Y');
                            $timeBadgeClass = 'bg-slate-100 text-slate-700 border border-slate-200';
                        }
                    @endphp

                    {{-- Card Tiket Bus Visual dengan Foto Armada (Gaya Traveloka / Tiket.com / RedBus) --}}
                    <article class="flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs transition duration-200 hover:-translate-y-1 hover:border-blue-400 hover:shadow-md group">
                        <div>
                            {{-- Foto Bus & Overlays --}}
                            <div class="relative h-44 w-full overflow-hidden bg-slate-900">
                                <img src="{{ $route->bus->image_url }}" 
                                     alt="{{ $route->bus->bus_name }}" 
                                     class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-105"
                                     loading="lazy"
                                     onerror="this.src='{{ asset('images/hero-bus.jpg') }}'">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                                
                                {{-- Badge Kelas Bus & Konteks Waktu --}}
                                <div class="absolute top-3 inset-x-3 flex items-center justify-between gap-2">
                                    <span class="rounded-lg bg-blue-600/90 backdrop-blur-xs px-2.5 py-1 text-[11px] font-extrabold uppercase tracking-wider text-white shadow-sm">
                                        {{ str_replace('_', ' ', $route->bus->bus_type) }}
                                    </span>
                                    <span class="rounded-lg bg-black/60 backdrop-blur-xs px-2 py-1 text-[11px] font-semibold text-white shadow-sm">
                                        {{ $timeContext }}
                                    </span>
                                </div>

                                {{-- Info Armada di Atas Foto --}}
                                <div class="absolute bottom-2.5 inset-x-3 flex items-center justify-between text-white">
                                    <span class="text-sm font-black drop-shadow-sm truncate pr-2">
                                        {{ $route->bus->bus_name }}
                                    </span>
                                    <span class="shrink-0 text-[10px] font-mono font-semibold bg-white/20 backdrop-blur-xs px-1.5 py-0.5 rounded text-slate-100">
                                        {{ $route->bus->bus_code }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-4 sm:p-5">
                                {{-- Baris Tanggal & Ketersediaan Kursi --}}
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3 text-xs">
                                    <span class="font-bold text-slate-700">
                                        {{ $route->departure_date->translatedFormat('d M Y') }}
                                    </span>
                                    <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700 border border-emerald-200">
                                        {{ $route->available_seats }} kursi tersedia
                                    </span>
                                </div>

                                {{-- Route Visual Vertikal (07:30 Jepara │ Semarang 09:30) --}}
                                <div class="py-3.5 space-y-1">
                                    {{-- Keberangkatan --}}
                                    <div class="flex items-start gap-3">
                                        <div class="w-14 shrink-0 text-right pt-0.5 font-mono text-base font-black text-slate-900">
                                            {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }}
                                        </div>
                                        <div class="relative flex flex-col items-center">
                                            <span class="h-2.5 w-2.5 rounded-full border-2 border-blue-600 bg-white shadow-xs"></span>
                                            <span class="h-9 w-0.5 bg-slate-300"></span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-sm font-bold text-slate-900 leading-tight">
                                                {{ $route->origin_city }}
                                            </h4>
                                            <p class="text-xs text-slate-500 truncate">
                                                {{ $route->origin_terminal }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Kedatangan --}}
                                    <div class="flex items-start gap-3">
                                        <div class="w-14 shrink-0 text-right pt-0.5 font-mono text-base font-black text-slate-600">
                                            {{ $route->estimated_arrival_time ? \Carbon\Carbon::parse($route->estimated_arrival_time)->format('H:i') : '--:--' }}
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="h-2.5 w-2.5 rounded-full bg-blue-600 shadow-xs"></span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-sm font-bold text-slate-900 leading-tight">
                                                {{ $route->destination_city }}
                                            </h4>
                                            <p class="text-xs text-slate-500 truncate">
                                                {{ $route->destination_terminal }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Fasilitas Mini Chips --}}
                                @if(!empty($route->bus->facilities) && is_array($route->bus->facilities))
                                    <div class="border-t border-slate-100 pt-2.5 flex flex-wrap gap-1.5">
                                        @foreach(array_slice($route->bus->facilities, 0, 3) as $facility)
                                            <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-600">
                                                {{ $facility }}
                                            </span>
                                        @endforeach
                                        @if(count($route->bus->facilities) > 3)
                                            <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-medium text-slate-500">
                                                +{{ count($route->bus->facilities) - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Harga & Tombol Lihat Detail --}}
                        <div class="border-t border-slate-100 bg-slate-50/60 p-4 flex items-center justify-between gap-3">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Tarif per kursi</span>
                                <span class="text-lg font-black text-blue-900 font-mono">
                                    Rp {{ number_format($route->price, 0, ',', '.') }}
                                </span>
                            </div>

                            <a href="{{ route('customer.trips.show', $route) }}" 
                               class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 px-4 py-2 text-xs font-bold text-white shadow-xs transition group-hover:shadow-sm">
                                <span>Pesan Tiket</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center">
                        <svg class="mx-auto h-9 w-9 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-bold text-slate-900">Belum ada jadwal bus tersedia saat ini</h3>
                        <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                            Silakan lakukan pencarian rute pada form di atas atau cek kembali nanti.
                        </p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- 4. CARA BOOKING (01 Cari → 02 Pilih Kursi → 03 Isi Data → 04 Bayar → 05 E-Ticket) --}}
        <section id="cara-booking" class="border-t border-slate-200 bg-white py-12 sm:py-16">
            <div class="site-shell">
                <div class="max-w-xl">
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        Cara Booking
                    </h2>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500">
                        Alur pemesanan tiket resmi PO CAN Travel dalam lima langkah mudah.
                    </p>
                </div>

                {{-- Alur Horizontal di Desktop, Responsif di Mobile --}}
                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    @php
                        $bookingSteps = [
                            ['num' => '01', 'name' => 'Cari', 'desc' => 'Tentukan rute asal, tujuan, dan tanggal perjalanan.'],
                            ['num' => '02', 'name' => 'Pilih Kursi', 'desc' => 'Pilih jadwal armada dan nomor kursi bus yang diinginkan.'],
                            ['num' => '03', 'name' => 'Isi Data', 'desc' => 'Lengkapi data identitas penumpang sesuai identitas resmi.'],
                            ['num' => '04', 'name' => 'Bayar', 'desc' => 'Selesaikan pembayaran simulasi yang telah diverifikasi.'],
                            ['num' => '05', 'name' => 'E-Ticket', 'desc' => 'Dapatkan e-ticket ber-QR code resmi untuk check-in terminal.'],
                        ];
                    @endphp

                    @foreach($bookingSteps as $index => $step)
                        <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-mono text-base font-extrabold text-blue-600">
                                        {{ $step['num'] }}
                                    </span>
                                    @if($index < 4)
                                        <span class="hidden lg:block text-slate-300 font-bold">→</span>
                                    @endif
                                </div>
                                <h3 class="text-base font-bold text-slate-900">
                                    {{ $step['name'] }}
                                </h3>
                                <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                                    {{ $step['desc'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- 5. BENEFIT / INFO (Pilih Kursi, Pembayaran, E-Ticket QR + Kebijakan) --}}
        <section id="benefit" class="border-t border-slate-200 bg-slate-50 py-12 sm:py-16">
            <div class="site-shell">
                <div class="max-w-xl">
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        Keunggulan Layanan
                    </h2>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500">
                        Fitur yang benar-benar tersedia untuk kenyamanan perjalanan Anda.
                    </p>
                </div>

                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    {{-- Benefit 1: Pilih Kursi --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 font-mono font-bold text-xs mb-4">
                            01
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Pilih Kursi</h3>
                        <p class="mt-2 text-xs leading-relaxed text-slate-600">
                            Denah kursi bus ditampilkan secara interaktif dan real-time. Anda bebas menentukan posisi nomor kursi favorit sebelum membayar tanpa penentuan acak.
                        </p>
                    </div>

                    {{-- Benefit 2: Pembayaran --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 font-mono font-bold text-xs mb-4">
                            02
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Pembayaran</h3>
                        <p class="mt-2 text-xs leading-relaxed text-slate-600">
                            Proses konfirmasi pembayaran terverifikasi langsung dengan sistem reservasi PO CAN Travel. Ringkasan tarif ditampilkan transparan tanpa biaya tersembunyi.
                        </p>
                    </div>

                    {{-- Benefit 3: E-Ticket QR --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 font-mono font-bold text-xs mb-4">
                            03
                        </div>
                        <h3 class="text-base font-bold text-slate-900">E-Ticket QR</h3>
                        <p class="mt-2 text-xs leading-relaxed text-slate-600">
                            E-Ticket digital terbit instan lengkap dengan kode QR resmi. Tunjukkan layar ponsel kepada petugas terminal atau kondektur untuk boarding cepat tanpa cetak fisik.
                        </p>
                    </div>
                </div>

                {{-- Kebijakan Layanan --}}
                <div id="kebijakan" class="mt-8 rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900">
                        Kebijakan Pembatalan & Perjalanan
                    </h3>
                    <p class="mt-1 text-xs text-slate-600 leading-relaxed">
                        {{ $settings['cancellation_policy'] ?? 'Pembatalan atau perubahan jadwal tiket dapat dilakukan maksimal 3 jam sebelum waktu keberangkatan yang tertera pada e-ticket.' }}
                        Penumpang disarankan tiba di terminal keberangkatan minimal 30 menit sebelum jadwal perjalanan bus.
                    </p>
                </div>
            </div>
        </section>

        {{-- 6. CTA (Sederhana: Siap berangkat? Cari jadwal bus dan pesan perjalanan Anda sekarang. Cari Jadwal) --}}
        <section class="border-t border-slate-800 bg-blue-900 text-white py-12">
            <div class="site-shell flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Siap berangkat?</h2>
                    <p class="mt-1 text-xs sm:text-sm text-blue-100">
                        Cari jadwal bus dan pesan perjalanan Anda sekarang.
                    </p>
                </div>
                <div>
                    <a href="{{ route('customer.trips.index') }}" 
                       class="inline-flex items-center justify-center rounded-lg bg-white px-5 py-2.5 text-xs font-bold text-blue-900 hover:bg-blue-50 transition shadow-xs">
                        Cari Jadwal
                    </a>
                </div>
            </div>
        </section>
    </main>

    {{-- 7. FOOTER (Dark navy/blue, compact dan profesional) --}}
    <footer id="kontak" class="border-t border-slate-800 bg-slate-900 text-slate-400">
        <div class="site-shell py-10">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4 text-xs">
                {{-- PO CAN Travel & Deskripsi --}}
                <div class="space-y-2">
                    <div class="flex items-center gap-2 font-bold text-white">
                        <span class="flex h-6 w-6 items-center justify-center rounded bg-blue-600 text-xs font-black text-white">C</span>
                        <span class="text-sm">{{ $settings['app_name'] ?? 'PO CAN Travel' }}</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400">
                        Layanan pemesanan tiket bus antarkota resmi dengan kepastian jadwal dan kenyamanan armada terdepan.
                    </p>
                    @if(!empty($settings['footer_address']))
                        <p class="text-[11px] text-slate-500 pt-1">
                            {{ $settings['footer_address'] }}
                        </p>
                    @endif
                </div>

                {{-- Jadwal & Cara Booking --}}
                <div class="space-y-2">
                    <p class="font-bold text-slate-200">Layanan</p>
                    <ul class="space-y-1.5">
                        <li>
                            <a href="{{ route('customer.trips.index') }}" class="hover:text-white transition">
                                Jadwal Bus
                            </a>
                        </li>
                        <li>
                            <a href="#cara-booking" class="hover:text-white transition">
                                Cara Booking
                            </a>
                        </li>
                        <li>
                            <a href="{{ auth()->check() && auth()->user()->role === 'admin' ? route('admin.dashboard') : route('customer.orders.index') }}" 
                               class="hover:text-white transition">
                                Pesanan
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Kebijakan --}}
                <div class="space-y-2">
                    <p class="font-bold text-slate-200">Kebijakan</p>
                    <ul class="space-y-1.5">
                        <li>
                            <a href="#kebijakan" class="hover:text-white transition">
                                Kebijakan Pembatalan
                            </a>
                        </li>
                        <li>
                            <a href="#kebijakan" class="hover:text-white transition">
                                Ketentuan Boarding Terminal
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Kontak --}}
                <div class="space-y-2">
                    <p class="font-bold text-slate-200">Kontak</p>
                    <ul class="space-y-1.5">
                        @if(!empty($settings['contact_email']))
                            <li>
                                <a href="mailto:{{ $settings['contact_email'] }}" class="hover:text-white transition">
                                    {{ $settings['contact_email'] }}
                                </a>
                            </li>
                        @endif
                        @if(!empty($settings['contact_phone']))
                            <li>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['contact_phone']) }}" class="hover:text-white transition">
                                    {{ $settings['contact_phone'] }}
                                </a>
                            </li>
                        @endif
                        <li class="text-slate-500">
                            Operasional: 06:00 – 21:00 WIB
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 border-t border-slate-800 pt-5 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $settings['app_name'] ?? 'PO CAN Travel' }}. Seluruh hak cipta dilindungi.</p>
                <p>Website Resmi Pemesanan Tiket Bus</p>
            </div>
        </div>
    </footer>

</body>
</html>