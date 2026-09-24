<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PO CAN Travel') — Pemesanan Tiket Bus Resmi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="min-h-full flex flex-col bg-slate-50 font-sans text-slate-900 antialiased selection:bg-blue-600 selection:text-white pb-16 sm:pb-0">

    {{-- Navbar Utama Customer (Clean, putih, sticky, border tipis) --}}
    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white shadow-xs">
        <div class="site-shell flex h-16 items-center justify-between gap-4">

            {{-- Brand Logo --}}
            <a href="{{ auth()->check() && auth()->user()->role === 'customer' ? route('customer.dashboard') : route('home') }}" 
               class="flex items-center gap-3 shrink-0">
                <div class="h-11 w-11 rounded-xl bg-white border border-slate-200/90 overflow-hidden shadow-xs flex items-center justify-center shrink-0">
                    <img src="{{ asset('storage/images/LOGO-CAN-TRAVEL.jpeg') }}" 
                         alt="Logo PO CAN Travel" 
                         class="h-full w-full object-cover scale-135"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <span class="hidden h-full w-full items-center justify-center font-black text-blue-900 text-base">C</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-base sm:text-lg font-extrabold tracking-tight text-slate-900 leading-tight">
                        PO CAN Travel
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600">
                        Tiket Bus Antarkota
                    </span>
                </div>
            </a>

            {{-- Desktop Navigation Links --}}
            <nav class="hidden md:flex items-center gap-1 text-sm font-semibold">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.dashboard') }}" 
                           class="rounded-lg px-3 py-2 transition {{ request()->routeIs('customer.dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            Beranda
                        </a>
                        <a href="{{ route('customer.trips.index') }}" 
                           class="rounded-lg px-3 py-2 transition {{ request()->routeIs('customer.trips.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            Cari Perjalanan
                        </a>
                        <a href="{{ route('customer.orders.index') }}" 
                           class="rounded-lg px-3 py-2 transition {{ request()->routeIs('customer.orders.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            Pesanan
                        </a>
                        <a href="{{ route('profile.edit') }}" 
                           class="rounded-lg px-3 py-2 transition {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            Profil
                        </a>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('customer.trips.index') }}" 
                           class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">
                            Cari Perjalanan
                        </a>
                    @endif
                @else
                    <a href="{{ route('customer.trips.index') }}" 
                       class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">
                        Cari Perjalanan
                    </a>
                @endauth
            </nav>

            {{-- Right User Area --}}
            <div class="flex items-center gap-2 sm:gap-3 text-sm">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <div class="hidden sm:flex flex-col text-right">
                            <span class="text-xs font-bold text-slate-900 truncate max-w-[150px]">{{ auth()->user()->name }}</span>
                            <span class="text-[11px] text-slate-500">Pelanggan</span>
                        </div>

                        <a href="{{ route('profile.edit') }}" 
                           title="Profil Akun"
                           class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100 transition shadow-xs">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    title="Keluar"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition shadow-xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Kembali ke Admin</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" 
                       class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" 
                       class="rounded-lg bg-blue-600 hover:bg-blue-700 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Main Content Container --}}
    <main class="site-shell flex-1 py-6 sm:py-8">

        {{-- Flash Success Message --}}
        @if(session('success'))
            <div role="status" 
                 class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800 shadow-xs">
                <svg class="h-5 w-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <div class="flex-1">{{ session('success') }}</div>
            </div>
        @endif

        {{-- Flash Error Message --}}
        @if(session('error'))
            <div role="alert" 
                 class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800 shadow-xs">
                <svg class="h-5 w-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                </svg>
                <div class="flex-1">{{ session('error') }}</div>
            </div>
        @endif

        {{-- Validation Errors Alert --}}
        @if($errors->any())
            <div role="alert" 
                 class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-xs">
                <div class="font-bold flex items-center gap-2 mb-2">
                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                    </svg>
                    <span>Mohon periksa kembali formulir berikut:</span>
                </div>
                <ul class="list-disc space-y-1 pl-6 text-xs text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </main>

    {{-- Bottom Navigation Bar for Mobile (User-friendly quick access) --}}
    @auth
        @if(auth()->user()->role === 'customer')
            <nav class="sm:hidden fixed bottom-0 inset-x-0 z-30 border-t border-slate-200 bg-white/95 backdrop-blur-md px-3 py-2 flex items-center justify-around shadow-lg">
                <a href="{{ route('customer.dashboard') }}" 
                   class="flex flex-col items-center gap-1 text-[11px] font-semibold transition {{ request()->routeIs('customer.dashboard') ? 'text-blue-600' : 'text-slate-500' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('customer.trips.index') }}" 
                   class="flex flex-col items-center gap-1 text-[11px] font-semibold transition {{ request()->routeIs('customer.trips.*') ? 'text-blue-600' : 'text-slate-500' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35"/>
                    </svg>
                    <span>Cari</span>
                </a>
                <a href="{{ route('customer.orders.index') }}" 
                   class="flex flex-col items-center gap-1 text-[11px] font-semibold transition {{ request()->routeIs('customer.orders.*') ? 'text-blue-600' : 'text-slate-500' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    <span>Pesanan</span>
                </a>
                <a href="{{ route('profile.edit') }}" 
                   class="flex flex-col items-center gap-1 text-[11px] font-semibold transition {{ request()->routeIs('profile.*') ? 'text-blue-600' : 'text-slate-500' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Profil</span>
                </a>
            </nav>
        @endif
    @endauth

    {{-- Footer (Dark navy/blue, compact & profesional) --}}
    <footer class="border-t border-slate-800 bg-slate-900 text-slate-400 mt-auto">
        <div class="site-shell py-8 sm:py-10">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 text-xs">
                {{-- Brand Info --}}
                <div class="space-y-2">
                    <div class="flex items-center gap-2 font-bold text-white">
                        <span class="flex h-6 w-6 items-center justify-center rounded bg-blue-600 text-xs font-black text-white">C</span>
                        <span class="text-sm">PO CAN Travel</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Layanan pemesanan tiket bus antarkota resmi dengan kepastian jadwal dan armada terpercaya.
                    </p>
                </div>

                {{-- Layanan --}}
                <div class="space-y-2">
                    <p class="font-bold text-slate-200">Layanan</p>
                    <ul class="space-y-1.5 text-slate-400">
                        <li><a href="{{ route('customer.trips.index') }}" class="hover:text-white transition">Cari Perjalanan</a></li>
                        <li><a href="{{ route('home') }}#cara-booking" class="hover:text-white transition">Cara Booking</a></li>
                        @auth
                            @if(auth()->user()->role === 'customer')
                                <li><a href="{{ route('customer.orders.index') }}" class="hover:text-white transition">Pesanan Saya</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>

                {{-- Kebijakan --}}
                <div class="space-y-2">
                    <p class="font-bold text-slate-200">Kebijakan</p>
                    <ul class="space-y-1.5 text-slate-400">
                        <li><a href="{{ route('home') }}#kebijakan" class="hover:text-white transition">Kebijakan Pembatalan</a></li>
                        <li><a href="{{ route('home') }}#kebijakan" class="hover:text-white transition">Ketentuan Boarding</a></li>
                    </ul>
                </div>

                {{-- Bantuan & Kontak --}}
                <div class="space-y-2">
                    <p class="font-bold text-slate-200">Bantuan</p>
                    <ul class="space-y-1.5 text-slate-400">
                        <li>Email: support@pocantravel.com</li>
                        <li>Telepon: 081234567890</li>
                        <li class="text-[11px] text-slate-500">Jam Layanan: 06:00 – 21:00 WIB</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 border-t border-slate-800 pt-5 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-slate-500">
                <p>&copy; {{ date('Y') }} PO CAN Travel. Seluruh hak cipta dilindungi.</p>
                <p>Platform Pemesanan Tiket Bus Resmi</p>
            </div>
        </div>
    </footer>

    @stack('scripts')

</body>
</html>
