<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ auth()->user()?->role === 'admin' ? 'Admin Portal' : 'Profil' }} — PO CAN Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 font-sans antialiased text-slate-800">
@if(auth()->user()?->role === 'admin')
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">
        {{-- Mobile Backdrop --}}
        <div x-show="sidebarOpen"
             x-cloak
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden">
        </div>

        {{-- Sidebar (Desktop & Mobile Drawer) --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-brand-950 text-white transition-transform duration-300 ease-in-out lg:static lg:z-auto lg:translate-x-0">
            {{-- Brand Logo --}}
            <div class="flex h-16 shrink-0 items-center justify-between px-6 border-b border-slate-800/80">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 font-bold tracking-tight">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-400 text-sm font-black text-brand-950">C</span>
                    <span class="text-base text-white">PO CAN <span class="font-normal text-amber-400 text-xs uppercase tracking-wider ml-1 px-1.5 py-0.5 rounded bg-amber-400/10 border border-amber-400/30">Admin</span></span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-md">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Nav Links --}}
            <nav class="flex-1 space-y-1 px-3 py-4 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400 text-brand-950 font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-brand-950' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.buses.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.buses.*') ? 'bg-amber-400 text-brand-950 font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.buses.*') ? 'text-brand-950' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Armada Bus
                </a>

                <a href="{{ route('admin.routes.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.routes.*') ? 'bg-amber-400 text-brand-950 font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.routes.*') ? 'text-brand-950' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Perjalanan & Rute
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-amber-400 text-brand-950 font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.orders.*') ? 'text-brand-950' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    Pesanan Tiket
                </a>

                <a href="{{ route('admin.payments.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-amber-400 text-brand-950 font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.payments.*') ? 'text-brand-950' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Pembayaran
                </a>

                <a href="{{ route('admin.customers.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.customers.*') ? 'bg-amber-400 text-brand-950 font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.customers.*') ? 'text-brand-950' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Pelanggan
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-amber-400 text-brand-950 font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-brand-950' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Pengaturan
                </a>
            </nav>

            {{-- Sidebar Footer / User Profile --}}
            <div class="p-3 border-t border-slate-800/80">
                <div class="flex items-center justify-between rounded-lg bg-slate-900/60 p-2.5">
                    <div class="min-w-0 pr-2">
                        <p class="truncate text-xs font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-400">Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar" class="rounded p-1.5 text-slate-400 hover:bg-slate-800 hover:text-white transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Area --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Topbar --}}
            <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden" aria-label="Buka navigasi">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <span class="font-medium text-slate-900">PO CAN Travel</span>
                        <span>/</span>
                        <span>Portal Admin</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition shadow-sm">
                        <svg class="h-3.5 w-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Lihat Web
                    </a>
                </div>
            </header>

            {{-- Page Header --}}
            @if(isset($header))
                <div class="border-b border-slate-200 bg-white">
                    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 pb-12">
                {{ $slot }}
            </main>
        </div>
    </div>
@else
    {{-- Customer Profile / General Auth Layout --}}
    <nav class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4 sm:px-6">
            <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2 font-bold text-brand-950">
                <span class="flex h-7 w-7 items-center justify-center rounded bg-brand-950 text-xs font-black text-amber-300">C</span>
                PO CAN Travel
            </a>
            <a href="{{ route('customer.dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-brand-950">
                &larr; Kembali ke dashboard
            </a>
        </div>
    </nav>
    @if(isset($header))
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-5xl px-4 py-5 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif
    <main>{{ $slot }}</main>
@endif
@stack('scripts')
</body>
</html>
