@extends('customer.layouts.index')

@section('title', 'Beranda')

@section('content')
@php
    $nextOrder = $latestOrders->first(fn ($order) => in_array($order->order_status, ['pending', 'paid'], true));
@endphp

<div class="space-y-6 sm:space-y-8">
    {{-- 1. Welcome Greeting & Quick Search --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
        <div class="max-w-2xl">
            <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Beranda Pelanggan</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mt-1">
                Selamat datang, {{ auth()->user()->name }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Mau pergi ke mana hari ini? Temukan jadwal keberangkatan bus antarkota dengan kursi pilihan Anda.
            </p>
        </div>

        {{-- Quick Search Form --}}
        <form method="GET" action="{{ route('customer.trips.index') }}" class="mt-6 grid gap-3 sm:grid-cols-3 max-w-3xl">
            <div>
                <label for="origin_city" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Kota Asal</label>
                <input id="origin_city" name="origin_city" placeholder="Contoh: Jepara" required 
                       class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div>
                <label for="destination_city" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Kota Tujuan</label>
                <input id="destination_city" name="destination_city" placeholder="Contoh: Semarang" required 
                       class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-blue-600 focus:ring-blue-600">
            </div>
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full rounded-lg bg-blue-600 hover:bg-blue-700 py-2.5 px-4 text-sm font-bold text-white shadow-xs transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35"/>
                    </svg>
                    <span>Cari Perjalanan</span>
                </button>
            </div>
        </form>
    </div>

    {{-- 2. Ringkasan Status Perjalanan --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="rounded-xl border border-slate-200 bg-white p-4 sm:p-5 shadow-xs">
            <span class="text-xs font-semibold text-slate-500">Total Pesanan</span>
            <p class="text-2xl font-black text-slate-900 font-mono mt-1">{{ $stats['total_orders'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Seluruh transaksi tiket</p>
        </div>

        <div class="rounded-xl border border-amber-200 bg-amber-50/50 p-4 sm:p-5 shadow-xs">
            <span class="text-xs font-semibold text-amber-800">Menunggu Pembayaran</span>
            <p class="text-2xl font-black text-amber-700 font-mono mt-1">{{ $stats['pending_orders'] }}</p>
            <p class="text-[11px] text-amber-600 mt-1">Segera selesaikan tagihan</p>
        </div>

        <div class="rounded-xl border border-blue-200 bg-blue-50/50 p-4 sm:p-5 shadow-xs">
            <span class="text-xs font-semibold text-blue-800">Tiket Aktif / Lunas</span>
            <p class="text-2xl font-black text-blue-700 font-mono mt-1">{{ $stats['paid_orders'] }}</p>
            <p class="text-[11px] text-blue-600 mt-1">Siap untuk keberangkatan</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 sm:p-5 shadow-xs">
            <span class="text-xs font-semibold text-slate-500">Perjalanan Selesai</span>
            <p class="text-2xl font-black text-slate-900 font-mono mt-1">{{ $stats['completed_orders'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Riwayat perjalanan Anda</p>
        </div>
    </div>

    {{-- 3. Rencana Perjalanan Anda (Upcoming Trip) & Riwayat Pesanan --}}
    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        {{-- Rencana Perjalanan Mendatang --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Perjalanan Anda</h2>
                    <p class="text-xs text-slate-500">Jadwal bus aktif terdekat yang telah Anda pesan.</p>
                </div>
                @if($nextOrder)
                    <a href="{{ route('customer.orders.show', $nextOrder) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                        Detail Pesanan &rarr;
                    </a>
                @endif
            </div>

            @if($nextOrder)
                <article class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs space-y-5">
                    {{-- Header Meta --}}
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-slate-900">{{ $nextOrder->order_code }}</span>
                            <span class="text-slate-300">•</span>
                            <span class="font-semibold text-slate-600">{{ $nextOrder->route->departure_date->translatedFormat('d M Y') }}</span>
                        </div>
                        <x-status-badge :status="$nextOrder->order_status" type="order" />
                    </div>

                    {{-- Live Countdown Clock --}}
                    @if($nextOrder->order_status === 'paid' || $nextOrder->order_status === 'completed')
                        <x-trip-countdown :order="$nextOrder" />
                    @endif

                    {{-- Visual Rute Tiket --}}
                    <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto_1fr] items-center gap-4 bg-slate-50 p-4 rounded-lg border border-slate-100">
                        <div>
                            <span class="text-[11px] font-bold uppercase text-slate-400">Keberangkatan</span>
                            <h3 class="text-base font-bold text-slate-900">{{ $nextOrder->route->origin_city }}</h3>
                            <p class="text-xs text-slate-500 truncate">{{ $nextOrder->route->origin_terminal }}</p>
                            <p class="font-mono text-base font-extrabold text-blue-900 mt-1">
                                {{ \Carbon\Carbon::parse($nextOrder->route->departure_time)->format('H:i') }} WIB
                            </p>
                        </div>

                        <div class="flex sm:flex-col items-center justify-center text-blue-600 font-bold">
                            <span class="text-xl sm:text-2xl leading-none">↓</span>
                        </div>

                        <div class="sm:text-right">
                            <span class="text-[11px] font-bold uppercase text-slate-400">Kedatangan</span>
                            <h3 class="text-base font-bold text-slate-900">{{ $nextOrder->route->destination_city }}</h3>
                            <p class="text-xs text-slate-500 truncate">{{ $nextOrder->route->destination_terminal }}</p>
                            <p class="font-mono text-base font-extrabold text-slate-700 mt-1">
                                {{ \Carbon\Carbon::parse($nextOrder->route->estimated_arrival_time)->format('H:i') }} WIB
                            </p>
                        </div>
                    </div>

                    {{-- Bus & Seat Info --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 text-xs border-t border-slate-100 pt-3">
                        <div>
                            <span class="font-bold text-slate-900">{{ $nextOrder->route->bus->bus_name }}</span>
                            <span class="text-slate-400">·</span>
                            <span class="capitalize text-slate-600">{{ str_replace('_', ' ', $nextOrder->route->bus->bus_type) }}</span>
                            <span class="text-slate-400">·</span>
                            <span class="text-slate-700 font-semibold">{{ $nextOrder->total_passengers }} Penumpang</span>
                        </div>
                        <div class="font-mono font-bold text-base text-slate-900">
                            Rp {{ number_format($nextOrder->total_price, 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- Action CTA --}}
                    <div class="pt-2 flex flex-wrap gap-2">
                        @if($nextOrder->order_status === 'paid')
                            <a href="{{ route('customer.orders.ticket', $nextOrder) }}" 
                               class="rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-2 px-4 shadow-xs transition">
                                Buka E-Ticket
                            </a>
                        @elseif($nextOrder->order_status === 'pending')
                            <a href="{{ route('customer.payments.create', $nextOrder) }}" 
                               class="rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-2 px-4 shadow-xs transition">
                                Bayar Sekarang
                            </a>
                        @endif
                        <a href="{{ route('customer.orders.show', $nextOrder) }}" 
                           class="rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs py-2 px-4 shadow-xs transition">
                            Lihat Detail Pesanan
                        </a>
                    </div>
                </article>
            @else
                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center space-y-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-600 mx-auto">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Belum ada perjalanan aktif</h3>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                            Rencanakan perjalanan Anda dengan memilih jadwal keberangkatan bus yang tersedia.
                        </p>
                    </div>
                    <a href="{{ route('customer.trips.index') }}" 
                       class="inline-flex rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 transition shadow-xs">
                        Cari Jadwal Perjalanan
                    </a>
                </div>
            @endif
        </div>

        {{-- Riwayat Booking Terbaru --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Riwayat Pesanan</h2>
                    <p class="text-xs text-slate-500">Daftar booking tiket bus terakhir Anda.</p>
                </div>
                <a href="{{ route('customer.orders.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white divide-y divide-slate-100 shadow-xs overflow-hidden">
                @forelse($latestOrders as $order)
                    <a href="{{ route('customer.orders.show', $order) }}" 
                       class="p-4 block hover:bg-slate-50/80 transition space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-mono font-bold text-slate-900">{{ $order->order_code }}</span>
                            <x-status-badge :status="$order->order_status" type="order" />
                        </div>
                        <div class="font-bold text-sm text-slate-900">
                            {{ $order->route->origin_city }} <span class="text-blue-600 font-normal">→</span> {{ $order->route->destination_city }}
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-500 pt-1">
                            <span>{{ $order->route->departure_date->translatedFormat('d M Y') }}</span>
                            <span class="font-mono font-bold text-slate-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </a>
                @empty
                    <div class="p-6 text-center text-xs text-slate-500">
                        Belum ada riwayat pesanan tiket.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
