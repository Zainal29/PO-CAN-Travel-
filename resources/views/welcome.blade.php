<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PO CAN Travel — Platform pemesanan tiket bus antarkota resmi. Jadwal akurat, pilihan kursi langsung, dan e-ticket instan dengan QR code.">
    <title>{{ $settings['app_name'] ?? 'PO CAN Travel' }} — Pemesanan Tiket Bus Resmi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased selection:bg-blue-600 selection:text-white pb-16 sm:pb-0">

    {{-- 1. NAVBAR (Clean, putih, sticky, border tipis, responsive hamburger) --}}
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-xs" x-data="{ mobileMenuOpen: false }">
        <nav class="site-shell flex h-16 items-center justify-between gap-4">
            {{-- Logo PO CAN Travel --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl bg-white border border-slate-200/90 overflow-hidden shadow-xs flex items-center justify-center shrink-0">
                    <img src="{{ asset('storage/images/LOGO-CAN-TRAVEL.jpeg') }}" 
                         alt="Logo PO CAN Travel" 
                         class="h-full w-full object-cover scale-135"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <span class="hidden h-full w-full items-center justify-center font-black text-blue-900 text-base">C</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm sm:text-lg font-extrabold tracking-tight text-slate-900 leading-tight">
                        {{ $settings['app_name'] ?? 'PO CAN Travel' }}
                    </span>
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-blue-600">
                        Tiket Bus Antarkota
                    </span>
                </div>
            </a>

            {{-- Desktop Navigation (Hidden on Mobile) --}}
            <div class="hidden md:flex items-center gap-2 sm:gap-3 text-sm font-semibold">
                {{-- Jadwal Bus (selalu tampil) --}}
                <a href="{{ route('customer.trips.index') }}" 
                   class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">
                    Jadwal Bus
                </a>

                @auth
                    @if(auth()->user()->role === 'admin')
                        {{-- Admin: Kembali ke Admin --}}
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Kembali ke Admin</span>
                        </a>
                    @else
                        {{-- Customer: Beranda + Dashboard + Pesanan + Profil --}}
                        <a href="{{ route('customer.dashboard') }}" 
                           class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">
                            <span>Dashboard</span>
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

            {{-- Mobile Right Action: Hamburger Button --}}
            <div class="flex items-center gap-2 md:hidden">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center gap-1 rounded-lg bg-blue-600 hover:bg-blue-700 px-2.5 py-1.5 text-[11px] font-bold text-white shadow-2xs transition">
                            <span>Admin</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endif
                @endauth

                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        type="button" 
                        class="flex items-center justify-center h-10 w-10 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition shadow-2xs focus:outline-none"
                        aria-label="Toggle menu navigasi mobile">
                    <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </nav>

        {{-- Mobile Dropdown Drawer Menu --}}
        <div x-show="mobileMenuOpen" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             @click.away="mobileMenuOpen = false"
             class="md:hidden border-b border-slate-200 bg-white shadow-xl px-4 py-4 space-y-3">

            @auth
                {{-- User Badge Info --}}
                <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3 border border-slate-100">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white font-bold text-xs shadow-2xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-500 capitalize">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Pelanggan' }}</p>
                        </div>
                    </div>
                    <span class="rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700 uppercase">
                        {{ auth()->user()->role }}
                    </span>
                </div>

                @if(auth()->user()->role === 'admin')
                    <div class="space-y-1.5 pt-1">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center justify-center gap-2 w-full rounded-xl bg-blue-600 hover:bg-blue-700 py-2.5 px-3 text-xs font-bold text-white shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <span>Kembali ke Portal Admin</span>
                        </a>
                        <a href="{{ route('customer.trips.index') }}" 
                           class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35"/></svg>
                            <span>Jadwal Bus & Cari Tiket</span>
                        </a>
                    </div>
                @else
                    <div class="space-y-1 text-sm font-semibold">
                        <a href="{{ route('home') }}" 
                           class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-blue-700 bg-blue-50/70 font-bold transition">
                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Beranda (Halaman Utama)</span>
                        </a>
                        <a href="{{ route('customer.dashboard') }}" 
                           class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 transition">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>Dashboard Pelanggan</span>
                        </a>
                        <a href="{{ route('customer.trips.index') }}" 
                           class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 transition">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35"/></svg>
                            <span>Jadwal Bus & Cari Perjalanan</span>
                        </a>
                        <a href="{{ route('customer.orders.index') }}" 
                           class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 transition">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            <span>Pesanan Tiket Saya</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" 
                           class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 transition">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Profil Akun</span>
                        </a>
                    </div>
                @endif

                <div class="border-t border-slate-100 pt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-lg py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Keluar Akun</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="space-y-1 text-sm font-semibold">
                    <a href="{{ route('home') }}" 
                       class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-blue-700 bg-blue-50/70 font-bold transition">
                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('customer.trips.index') }}" 
                       class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 transition">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35"/></svg>
                        <span>Jadwal Bus & Cari Tiket</span>
                    </a>
                    <a href="#cara-booking" @click="mobileMenuOpen = false" 
                       class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 transition">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Panduan Cara Booking</span>
                    </a>
                    <a href="#benefit" @click="mobileMenuOpen = false" 
                       class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 transition">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Keunggulan Layanan</span>
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-2 border-t border-slate-100 pt-3">
                    <a href="{{ route('login') }}" 
                       class="flex items-center justify-center rounded-xl border border-slate-300 bg-white py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" 
                       class="flex items-center justify-center rounded-xl bg-blue-600 hover:bg-blue-700 py-2.5 text-xs font-bold text-white shadow-2xs transition">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
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

            <div class="relative z-10 site-shell py-10 sm:py-12 lg:py-14">
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
                                <div>
                                    <label for="origin_city" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                        Kota Asal
                                    </label>
                                    <div class="relative">
                                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="10" r="3"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0z"/>
                                            </svg>
                                        </span>
                                        <select id="origin_city" 
                                                name="origin_city" 
                                                required
                                                class="w-full rounded-lg border-slate-300 py-2.5 pl-9 pr-8 text-sm font-medium text-slate-900 focus:border-blue-600 focus:ring-blue-600 bg-white">
                                            <option value="" disabled {{ !request('origin_city') ? 'selected' : '' }}>Pilih Kota Asal</option>
                                            @foreach($originCities as $city)
                                                <option value="{{ $city }}" {{ (request('origin_city', 'Jepara') === $city) ? 'selected' : '' }}>
                                                    {{ $city }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Kota Tujuan --}}
                                <div>
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
                                        <select id="destination_city" 
                                                name="destination_city" 
                                                required
                                                class="w-full rounded-lg border-slate-300 py-2.5 pl-9 pr-8 text-sm font-medium text-slate-900 focus:border-blue-600 focus:ring-blue-600 bg-white">
                                            <option value="" disabled {{ !request('destination_city') ? 'selected' : '' }}>Pilih Kota Tujuan</option>
                                            @foreach($destinationCities as $city)
                                                <option value="{{ $city }}" {{ (request('destination_city', 'Semarang') === $city) ? 'selected' : '' }}>
                                                    {{ $city }}
                                                </option>
                                            @endforeach
                                        </select>
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
        <section id="jadwal-bus" class="site-shell py-10 sm:py-12">
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
            <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
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
                                {{-- Nama Armada Bus Jelas & Terlihat --}}
                                <div class="flex items-center justify-between gap-2 mb-3 bg-blue-50/80 p-2.5 rounded-xl border border-blue-100">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white shadow-xs">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-extrabold text-blue-950 truncate">{{ $route->bus->bus_name }}</p>
                                            <p class="text-[10px] text-blue-600 font-mono font-medium">{{ $route->bus->bus_code }} · {{ $route->bus->plate_number ?: 'Tanpa Plat' }}</p>
                                        </div>
                                    </div>
                                    <span class="shrink-0 text-[10px] font-extrabold text-blue-700 bg-white px-2 py-0.5 rounded-md border border-blue-200 uppercase tracking-wider">
                                        {{ str_replace('_', ' ', $route->bus->bus_type) }}
                                    </span>
                                </div>

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

        {{-- 4. CARA BOOKING --}}
        <section id="cara-booking" class="border-t border-slate-200 bg-white py-10 sm:py-12 lg:py-14">
            <div class="site-shell">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 border border-blue-200 px-3.5 py-1 text-xs font-bold text-blue-700 mb-4 shadow-xs">
                        <span class="h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                        <span>PANDUAN PEMESANAN</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900 leading-tight">
                        Cara Booking Tiket Resmi
                    </h2>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
                        Pesan tiket bus PO CAN Travel secara cepat, transparan, dan terkonfirmasi langsung dalam lima langkah praktis tanpa antri di loket terminal.
                    </p>
                </div>

                {{-- Grid Langkah Pemesanan --}}
                <div class="mt-6 sm:mt-8 grid gap-4 sm:gap-5 sm:grid-cols-2 lg:grid-cols-5">
                    @php
                        $bookingSteps = [
                            [
                                'num' => '01',
                                'name' => 'Cari Perjalanan',
                                'desc' => 'Tentukan rute asal, kota tujuan, dan tanggal keberangkatan yang sesuai jadwal Anda.',
                                'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
                                'badge' => 'Rute & Tanggal',
                                'tip' => 'Jadwal update real-time',
                            ],
                            [
                                'num' => '02',
                                'name' => 'Pilih Kursi Nyata',
                                'desc' => 'Lihat denah kursi bus interaktif dan pilih langsung nomor kursi favorit tanpa diacak.',
                                'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z',
                                'badge' => 'Denah Interaktif',
                                'tip' => 'Bebas pilih jendela / lorong',
                            ],
                            [
                                'num' => '03',
                                'name' => 'Isi Data Penumpang',
                                'desc' => 'Masukkan data nama lengkap, nomor WhatsApp aktif, dan identitas untuk manifes resmi.',
                                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                                'badge' => 'Manifes Sah',
                                'tip' => 'Nama sesuai KTP/SIM',
                            ],
                            [
                                'num' => '04',
                                'name' => 'Bayar & Verifikasi',
                                'desc' => 'Gunakan simulasi transfer bank atau QRIS instan dengan verifikasi otomatis tanpa biaya admin siluman.',
                                'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
                                'badge' => 'Instan & Otomatis',
                                'tip' => 'Tanpa biaya tersembunyi',
                            ],
                            [
                                'num' => '05',
                                'name' => 'E-Ticket QR Terbit',
                                'desc' => 'Tiket digital terbit seketika ber-QR Code. Tunjukkan layar ponsel saat boarding di terminal.',
                                'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z',
                                'badge' => 'Siap Berangkat',
                                'tip' => 'Boarding tanpa cetak fisik',
                            ],
                        ];
                    @endphp

                    @foreach($bookingSteps as $index => $step)
                        <div class="relative flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-xs transition duration-200 hover:-translate-y-1 hover:border-blue-400 hover:shadow-md">
                            <div>
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                                        </svg>
                                    </div>
                                    <span class="font-mono text-xs font-black text-blue-700 bg-blue-50/90 px-2.5 py-1 rounded-md border border-blue-200/60">
                                        Langkah {{ $step['num'] }}
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 leading-snug">
                                    {{ $step['name'] }}
                                </h3>
                                <p class="mt-2.5 text-xs text-slate-500 leading-relaxed">
                                    {{ $step['desc'] }}
                                </p>
                            </div>

                            <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center gap-1.5 text-[11px] font-semibold text-blue-600">
                                <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="truncate">{{ $step['tip'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Garansi & Kemudahan Pemesanan Strip --}}
                <div class="mt-6 sm:mt-8 rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50/90 via-indigo-50/60 to-slate-50 p-5 sm:p-6 shadow-xs">
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="flex items-start gap-3.5">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-xs">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">100% Kepastian Kursi</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Nomor kursi terkunci permanen saat konfirmasi order.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-xs">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Tarif Transparan</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Harga final tertera, tanpa biaya admin terselubung.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-xs">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Boarding E-Ticket</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Scan cepat di terminal langsung dari layar smartphone.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-xs">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Bantuan CS Siaga</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Tim operasional siap sedia memandu perjalanan Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 5. KEUNGGULAN LAYANAN & INFO --}}
        <section id="benefit" class="border-t border-slate-200 bg-slate-50/70 py-10 sm:py-12 lg:py-14">
            <div class="site-shell">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3.5 py-1 text-xs font-bold text-emerald-800 mb-4 shadow-xs">
                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                        <span>MENGAPA PO CAN TRAVEL</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900 leading-tight">
                        Keunggulan Nyata untuk Kenyamanan Anda
                    </h2>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
                        Kami mengutamakan keselamatan, ketepatan jadwal armada, dan kemudahan teknologi digital agar perjalanan Anda nyaman sejak pemesanan hingga tiba di tujuan.
                    </p>
                </div>

                {{-- 4 Pilar Keunggulan --}}
                <div class="mt-6 sm:mt-8 grid gap-5 sm:gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- 01: Pilih Kursi Real-Time --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-xs flex flex-col justify-between hover:border-blue-300 transition">
                        <div>
                            <div class="flex items-center justify-between mb-5">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <span class="font-mono text-xs font-black text-slate-400">01</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Pilih Kursi Nyata</h3>
                            <p class="mt-2.5 text-xs leading-relaxed text-slate-600">
                                Denah bus ditampilkan interaktif dan real-time. Anda bebas memilih posisi dekat jendela, lorong, atau baris depan sebelum membayar.
                            </p>
                        </div>
                        <div class="mt-5 pt-3.5 border-t border-slate-100 flex flex-wrap gap-1.5">
                            <span class="rounded bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700">Denah Live</span>
                            <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">Bebas Pilih</span>
                        </div>
                    </div>

                    {{-- 02: Pembayaran Transparan --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-xs flex flex-col justify-between hover:border-blue-300 transition">
                        <div>
                            <div class="flex items-center justify-between mb-5">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <span class="font-mono text-xs font-black text-slate-400">02</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Pembayaran Otomatis</h3>
                            <p class="mt-2.5 text-xs leading-relaxed text-slate-600">
                                Verifikasi cepat dan aman terhubung langsung ke sistem reservasi resmi. Tarif transparan tanpa tambahan biaya siluman saat tiba di terminal.
                            </p>
                        </div>
                        <div class="mt-5 pt-3.5 border-t border-slate-100 flex flex-wrap gap-1.5">
                            <span class="rounded bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">Auto-Verifikasi</span>
                            <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">No Hidden Fee</span>
                        </div>
                    </div>

                    {{-- 03: E-Ticket QR Code --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-xs flex flex-col justify-between hover:border-blue-300 transition">
                        <div>
                            <div class="flex items-center justify-between mb-5">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 shadow-2xs">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                </div>
                                <span class="font-mono text-xs font-black text-slate-400">03</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">E-Ticket QR Resmi</h3>
                            <p class="mt-2.5 text-xs leading-relaxed text-slate-600">
                                E-ticket digital langsung terbit lengkap dengan QR code resmi. Boarding tanpa repot cetak kertas fisik, praktis dan ramah lingkungan.
                            </p>
                        </div>
                        <div class="mt-5 pt-3.5 border-t border-slate-100 flex flex-wrap gap-1.5">
                            <span class="rounded bg-indigo-50 px-2 py-0.5 text-[10px] font-semibold text-indigo-700">QR Code Sah</span>
                            <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">Paperless</span>
                        </div>
                    </div>

                    {{-- 04: Armada Terawat & Nyaman --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-xs flex flex-col justify-between hover:border-blue-300 transition">
                        <div>
                            <div class="flex items-center justify-between mb-5">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <span class="font-mono text-xs font-black text-slate-400">04</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Armada Prima & Nyaman</h3>
                            <p class="mt-2.5 text-xs leading-relaxed text-slate-600">
                                Bus berstandar tinggi dengan AC dingin, port USB charger tiap bangku, reclining seat, dan kru berpengalaman di setiap rute antarkota.
                            </p>
                        </div>
                        <div class="mt-5 pt-3.5 border-t border-slate-100 flex flex-wrap gap-1.5">
                            <span class="rounded bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700">AC & USB Port</span>
                            <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">Kru Ramah</span>
                        </div>
                    </div>
                </div>

                {{-- Trust Metrics Counter Bar --}}
                <div class="mt-6 sm:mt-8 rounded-2xl border border-slate-200 bg-white p-6 sm:p-7 shadow-xs">
                    <div class="grid grid-cols-2 gap-8 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
                        <div class="text-center pt-2 sm:pt-0">
                            <p class="font-mono text-2xl sm:text-3xl lg:text-4xl font-black text-blue-900">10.000+</p>
                            <p class="mt-1.5 text-xs sm:text-sm font-bold text-slate-700">Tiket Terpesan</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Kepercayaan ribuan penumpang</p>
                        </div>

                        <div class="text-center pt-2 sm:pt-0">
                            <p class="font-mono text-2xl sm:text-3xl lg:text-4xl font-black text-blue-900">100%</p>
                            <p class="mt-1.5 text-xs sm:text-sm font-bold text-slate-700">Jadwal Terkonfirmasi</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Kepastian jam keberangkatan</p>
                        </div>

                        <div class="text-center pt-2 sm:pt-0">
                            <p class="font-mono text-2xl sm:text-3xl lg:text-4xl font-black text-blue-900">4.9 / 5.0</p>
                            <p class="mt-1.5 text-xs sm:text-sm font-bold text-slate-700">Rating Kepuasan</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Dari ulasan nyata penumpang</p>
                        </div>

                        <div class="text-center pt-2 sm:pt-0">
                            <p class="font-mono text-2xl sm:text-3xl lg:text-4xl font-black text-blue-900">24/7</p>
                            <p class="mt-1.5 text-xs sm:text-sm font-bold text-slate-700">Layanan Siaga</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Dukungan operasional penuh</p>
                        </div>
                    </div>
                </div>

                {{-- Kebijakan & Ketentuan Layanan --}}
                <div id="kebijakan" class="mt-6 sm:mt-8 rounded-2xl border border-slate-200 bg-white p-6 sm:p-7 shadow-xs">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600 shadow-2xs">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">
                            Kebijakan Pembatalan, Boarding, & Perjalanan
                        </h3>
                    </div>

                    <div class="grid gap-6 md:grid-cols-3 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-5">
                        <div class="space-y-1.5">
                            <h4 class="font-bold text-slate-900 text-sm">1. Pembatalan & Perubahan</h4>
                            <p>
                                {{ $settings['cancellation_policy'] ?? 'Pembatalan atau perubahan jadwal tiket dapat dilakukan maksimal 3 jam sebelum waktu keberangkatan yang tertera pada e-ticket.' }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <h4 class="font-bold text-slate-900 text-sm">2. Ketentuan Boarding Terminal</h4>
                            <p>
                                Penumpang disarankan tiba di terminal keberangkatan minimal 30 menit sebelum jadwal untuk validasi QR code e-ticket kepada petugas loket atau kondektur bus.
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <h4 class="font-bold text-slate-900 text-sm">3. Fasilitas & Bagasi</h4>
                            <p>
                                Setiap tiket mencakup bagasi standar penumpang (hingga 20 kg). Dilarang membawa barang berbahaya atau terlarang sesuai aturan perhubungan antarkota resmi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 6. CTA (Sederhana: Siap berangkat? Cari jadwal bus dan pesan perjalanan Anda sekarang. Cari Jadwal) --}}
        <section class="border-t border-slate-800 bg-blue-900 text-white py-8 sm:py-10">
            <div class="site-shell flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Siap berangkat?</h2>
                    <p class="mt-1.5 text-xs sm:text-sm text-blue-100">
                        Cari jadwal bus dan pesan perjalanan Anda sekarang.
                    </p>
                </div>
                <div>
                    <a href="{{ route('customer.trips.index') }}" 
                       class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-xs sm:text-sm font-bold text-blue-900 hover:bg-blue-50 transition shadow-sm">
                        Cari Jadwal
                    </a>
                </div>
            </div>
        </section>
    </main>

    {{-- 7. FOOTER (Dark navy/blue, compact dan profesional) --}}
    <footer id="kontak" class="border-t border-slate-800 bg-slate-900 text-slate-400">
        <div class="site-shell py-10 sm:py-12">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4 text-xs">
                {{-- PO CAN Travel & Deskripsi --}}
                <div class="space-y-3">
                    <div class="flex items-center gap-2 font-bold text-white">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-xs font-black text-white shadow-2xs">C</span>
                        <span class="text-base font-extrabold">{{ $settings['app_name'] ?? 'PO CAN Travel' }}</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400">
                        Layanan pemesanan tiket bus antarkota resmi dengan kepastian jadwal dan kenyamanan armada terdepan.
                    </p>
                    @if(!empty($settings['footer_address']))
                        <p class="text-[11px] text-slate-500 pt-1 leading-relaxed">
                            {{ $settings['footer_address'] }}
                        </p>
                    @endif
                </div>

                {{-- Jadwal & Cara Booking --}}
                <div class="space-y-3">
                    <p class="font-bold text-slate-200 text-sm">Layanan</p>
                    <ul class="space-y-2">
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
                <div class="space-y-3">
                    <p class="font-bold text-slate-200 text-sm">Kebijakan</p>
                    <ul class="space-y-2">
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
                <div class="space-y-3">
                    <p class="font-bold text-slate-200 text-sm">Kontak</p>
                    <ul class="space-y-2">
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

            <div class="mt-8 border-t border-slate-800 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $settings['app_name'] ?? 'PO CAN Travel' }}. Seluruh hak cipta dilindungi.</p>
                <p>Website Resmi Pemesanan Tiket Bus</p>
            </div>
        </div>
    </footer>

    {{-- Bottom Navigation Bar for Mobile (Home Landing) --}}
    <nav class="sm:hidden fixed bottom-0 inset-x-0 z-30 border-t border-slate-200 bg-white/95 backdrop-blur-md px-3 py-2 flex items-center justify-around shadow-lg">
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Home</span>
                </a>
                <a href="{{ route('customer.trips.index') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-500 hover:text-blue-600 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35"/></svg>
                    <span>Jadwal</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-blue-700 hover:text-blue-900 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Admin</span>
                </a>
            @else
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Home</span>
                </a>
                <a href="{{ route('customer.trips.index') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-500 hover:text-blue-600 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35"/></svg>
                    <span>Cari</span>
                </a>
                <a href="{{ route('customer.orders.index') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-500 hover:text-blue-600 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    <span>Pesanan</span>
                </a>
                <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-500 hover:text-blue-600 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Dashboard</span>
                </a>
            @endif
        @else
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-blue-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Home</span>
            </a>
            <a href="{{ route('customer.trips.index') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-500 hover:text-blue-600 transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35"/></svg>
                <span>Jadwal</span>
            </a>
            <a href="{{ route('login') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-500 hover:text-blue-600 transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                <span>Masuk</span>
            </a>
            <a href="{{ route('register') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-blue-600 hover:text-blue-700 transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span>Daftar</span>
            </a>
        @endauth
    </nav>

</body>
</html>