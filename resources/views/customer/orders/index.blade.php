@extends('customer.layouts.index')

@section('title', 'Pesanan Saya — PO CAN Travel')

@section('content')
<div x-data="{ activeFilter: 'all' }" class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="eyebrow">Aktivitas Tiket</p>
            <h1 class="page-heading mt-1">
                Pesanan Saya
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola tiket perjalanan bus, lihat status pembayaran, dan akses e-ticket Anda.
            </p>
        </div>

        <a href="{{ route('customer.trips.index') }}" class="btn-primary min-h-10 text-xs font-semibold px-4">
            Cari Perjalanan Baru
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <button
            @click="activeFilter = 'all'"
            :class="activeFilter === 'all' ? 'bg-brand-950 text-white font-semibold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
            class="rounded-lg px-3.5 py-1.5 text-xs transition"
        >
            Semua
        </button>
        <button
            @click="activeFilter = 'pending'"
            :class="activeFilter === 'pending' ? 'bg-brand-950 text-white font-semibold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
            class="rounded-lg px-3.5 py-1.5 text-xs transition"
        >
            Belum Bayar
        </button>
        <button
            @click="activeFilter = 'paid'"
            :class="activeFilter === 'paid' ? 'bg-brand-950 text-white font-semibold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
            class="rounded-lg px-3.5 py-1.5 text-xs transition"
        >
            Dibayar
        </button>
        <button
            @click="activeFilter = 'completed'"
            :class="activeFilter === 'completed' ? 'bg-brand-950 text-white font-semibold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
            class="rounded-lg px-3.5 py-1.5 text-xs transition"
        >
            Selesai
        </button>
        <button
            @click="activeFilter = 'cancelled'"
            :class="activeFilter === 'cancelled' ? 'bg-brand-950 text-white font-semibold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
            class="rounded-lg px-3.5 py-1.5 text-xs transition"
        >
            Dibatalkan
        </button>
    </div>

    {{-- Order List Cards --}}
    <div class="space-y-4">
        @forelse ($orders as $order)
            @php
                $orderStatus = strtolower($order->order_status);
            @endphp
            <div
                x-show="activeFilter === 'all' || activeFilter === '{{ $orderStatus }}'"
                x-transition
                class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm hover:border-slate-300 transition space-y-4"
            >
                {{-- Top row: Code, Date & Status --}}
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-3">
                        <span class="rounded bg-slate-100 px-2 py-1 font-mono text-xs font-bold text-brand-950">
                            {{ $order->order_code }}
                        </span>
                        <span class="text-xs text-slate-500">
                            Dipesan {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-status-badge :status="$order->order_status" type="order" />
                    </div>
                </div>

                {{-- Middle row: Route, Schedule, Bus, Seat --}}
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 items-center">
                    <div>
                        <span class="text-xs text-slate-400 font-medium">Rute Perjalanan</span>
                        <div class="flex items-center gap-2 font-bold text-slate-900 mt-1">
                            <span>{{ $order->route->origin_city }}</span>
                            <span class="text-amber-500">&rarr;</span>
                            <span>{{ $order->route->destination_city }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ $order->route->origin_terminal }} &rarr; {{ $order->route->destination_terminal }}
                        </p>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 font-medium">Waktu Keberangkatan</span>
                        <p class="font-bold text-slate-900 mt-1">
                            {{ $order->route->departure_date->translatedFormat('d M Y') }} · {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }} WIB
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Estimasi tiba {{ \Carbon\Carbon::parse($order->route->estimated_arrival_time)->format('H:i') }} WIB
                        </p>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 font-medium">Armada & Kursi</span>
                        <p class="font-semibold text-slate-900 mt-1">
                            {{ $order->route->bus->bus_name }}
                        </p>
                        <p class="text-xs text-slate-600 mt-0.5 capitalize">
                            {{ str_replace('_', ' ', $order->route->bus->bus_type) }} ·
                            @if($order->details->isNotEmpty())
                                Kursi: <strong class="font-mono text-slate-900">{{ $order->details->pluck('seat_number')->join(', ') }}</strong>
                            @else
                                {{ $order->total_passengers }} Penumpang
                            @endif
                        </p>
                    </div>

                    <div class="lg:text-right">
                        <span class="text-xs text-slate-400 font-medium">Total Pembayaran</span>
                        <p class="text-xl font-bold text-brand-950 mt-1">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-slate-500 capitalize mt-0.5">
                            {{ $order->payment ? str_replace('_', ' ', $order->payment->payment_method) : 'Belum bayar' }}
                        </p>
                    </div>
                </div>

                {{-- Bottom row: Actions --}}
                <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                    <div>
                        @if($order->order_status === 'pending')
                            <span class="text-amber-700 font-semibold">Segera selesaikan pembayaran sebelum batas waktu berakhir.</span>
                        @elseif($order->order_status === 'paid')
                            <span class="text-emerald-700 font-semibold">Tiket telah terkonfirmasi. Siap untuk perjalanan Anda!</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if($order->order_status === 'pending')
                            <a href="{{ route('customer.payments.create', $order) }}" class="btn-primary min-h-9 px-3.5 py-1.5 text-xs font-semibold">
                                Bayar Sekarang
                            </a>
                        @elseif($order->order_status === 'paid')
                            <a href="{{ route('customer.orders.ticket', $order) }}" class="inline-flex min-h-9 items-center justify-center rounded-lg bg-amber-400 px-3.5 py-1.5 text-xs font-bold text-brand-950 hover:bg-amber-300 transition">
                                Lihat E-Ticket
                            </a>
                        @endif
                        <a href="{{ route('customer.orders.show', $order) }}" class="btn-secondary min-h-9 px-3.5 py-1.5 text-xs font-semibold">
                            Detail Pesanan
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <h3 class="mt-4 text-base font-bold text-slate-900">Belum ada pesanan</h3>
                <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                    Pesanan perjalanan Anda akan muncul di sini setelah melakukan booking jadwal bus.
                </p>
                <div class="mt-5">
                    <a href="{{ route('customer.trips.index') }}" class="btn-primary min-h-10 text-xs font-semibold px-5">
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
