@extends('customer.layouts.index')

@section('title', 'Pesanan Saya — PO CAN Travel')

@section('content')
<div x-data="{ activeFilter: 'all' }" class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                Pesanan Saya
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500">
                Kelola tiket perjalanan bus, pantau status pembayaran, dan akses e-ticket Anda.
            </p>
        </div>

        <a href="{{ route('customer.trips.index') }}" 
           class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-xs font-bold text-white shadow-xs transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35"/>
            </svg>
            <span>Cari Perjalanan Baru</span>
        </a>
    </div>

    {{-- Filter Sederhana: Semua, Menunggu Pembayaran, Dibayar, Selesai, Dibatalkan --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <button type="button"
                @click="activeFilter = 'all'"
                :class="activeFilter === 'all' ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                class="rounded-lg px-3.5 py-1.5 text-xs transition">
            Semua
        </button>
        <button type="button"
                @click="activeFilter = 'pending'"
                :class="activeFilter === 'pending' ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                class="rounded-lg px-3.5 py-1.5 text-xs transition">
            Menunggu Pembayaran
        </button>
        <button type="button"
                @click="activeFilter = 'paid'"
                :class="activeFilter === 'paid' ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                class="rounded-lg px-3.5 py-1.5 text-xs transition">
            Dibayar
        </button>
        <button type="button"
                @click="activeFilter = 'completed'"
                :class="activeFilter === 'completed' ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                class="rounded-lg px-3.5 py-1.5 text-xs transition">
            Selesai
        </button>
        <button type="button"
                @click="activeFilter = 'cancelled'"
                :class="activeFilter === 'cancelled' ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                class="rounded-lg px-3.5 py-1.5 text-xs transition">
            Dibatalkan
        </button>
    </div>

    {{-- Order List Cards --}}
    <div class="space-y-4">
        @forelse ($orders as $order)
            @php
                $orderStatus = strtolower($order->order_status);
            @endphp
            <div x-show="activeFilter === 'all' || activeFilter === '{{ $orderStatus }}'"
                 x-transition
                 class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs transition hover:border-blue-300 space-y-4">
                {{-- Header Card: Order Code & Status --}}
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3 text-xs">
                    <div class="flex items-center gap-3">
                        <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded text-xs">
                            {{ $order->order_code }}
                        </span>
                        <span class="text-slate-500">
                            Dipesan {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    <div>
                        <x-status-badge :status="$order->order_status" type="order" />
                    </div>
                </div>

                {{-- Middle Content: Route, Schedule, Passengers, Total --}}
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 items-center">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rute Perjalanan</span>
                        <p class="font-bold text-base text-slate-900 mt-0.5">
                            {{ $order->route->origin_city }} <span class="text-blue-600 font-normal">→</span> {{ $order->route->destination_city }}
                        </p>
                        <p class="text-xs text-slate-500 truncate">
                            {{ $order->route->origin_terminal }} &rarr; {{ $order->route->destination_terminal }}
                        </p>
                    </div>

                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Keberangkatan</span>
                        <p class="font-bold text-sm text-slate-900 mt-0.5">
                            {{ $order->route->departure_date->translatedFormat('d M Y') }}
                        </p>
                        <p class="text-xs font-mono font-semibold text-blue-700">
                            {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }} WIB
                        </p>
                    </div>

                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Armada & Penumpang</span>
                        <p class="font-semibold text-xs text-slate-800 mt-0.5">
                            {{ $order->route->bus->bus_name }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{ $order->total_passengers }} Penumpang
                            @if($order->details->isNotEmpty())
                                · Kursi: <strong class="font-mono text-slate-800">{{ $order->details->pluck('seat_number')->join(', ') }}</strong>
                            @endif
                        </p>
                    </div>

                    <div class="lg:text-right">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Biaya</span>
                        <p class="text-xl font-black text-slate-900 font-mono mt-0.5">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Action Row --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-100 text-xs">
                    <div>
                        @if($order->order_status === 'pending')
                            <span class="text-amber-700 font-semibold">Menunggu konfirmasi pembayaran sebelum batas waktu.</span>
                        @elseif($order->order_status === 'paid')
                            <span class="text-green-700 font-semibold">Tiket telah dikonfirmasi dan siap untuk perjalanan.</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if($order->order_status === 'pending')
                            <a href="{{ route('customer.payments.create', $order) }}" 
                               class="rounded-lg bg-blue-600 hover:bg-blue-700 px-3.5 py-1.5 text-xs font-bold text-white shadow-xs transition">
                                Bayar Sekarang
                            </a>
                        @elseif($order->order_status === 'paid')
                            <a href="{{ route('customer.orders.ticket', $order) }}" 
                               class="rounded-lg bg-blue-600 hover:bg-blue-700 px-3.5 py-1.5 text-xs font-bold text-white shadow-xs transition">
                                Lihat E-Ticket
                            </a>
                        @endif
                        <a href="{{ route('customer.orders.show', $order) }}" 
                           class="rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3.5 py-1.5 text-xs font-bold text-slate-700 shadow-xs transition">
                            Lihat Pesanan
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-slate-200 bg-white p-12 text-center space-y-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 mx-auto">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Belum Ada Pesanan Tiket</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    Seluruh riwayat booking tiket bus antarkota Anda akan tersimpan rapi di halaman ini.
                </p>
                <div class="pt-2">
                    <a href="{{ route('customer.trips.index') }}" 
                       class="inline-flex rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-2.5 px-5 shadow-xs transition">
                        Cari Perjalanan Sekarang
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection
