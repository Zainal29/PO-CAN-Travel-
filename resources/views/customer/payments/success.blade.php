@extends('customer.layouts.index')

@section('title', 'Pembayaran Berhasil — PO CAN Travel')

@section('content')
<div class="mx-auto max-w-xl py-4 sm:py-8">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 text-center shadow-md space-y-6">
        {{-- Success Icon Circle --}}
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-8 ring-emerald-50/50">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <div>
            <span class="inline-block rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
                Transaksi Berhasil
            </span>
            <h1 class="mt-3 text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Tiket Anda Siap Digunakan!</h1>
            <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed max-w-md mx-auto">
                Pembayaran perjalanan telah diverifikasi secara instan. E-ticket resmi Anda kini telah aktif.
            </p>
        </div>

        {{-- Transaction Details Box --}}
        <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5 text-left text-xs space-y-3">
            <div class="flex justify-between items-center pb-2.5 border-b border-slate-200">
                <span class="text-slate-500 font-medium">Nomor Pesanan</span>
                <span class="font-mono font-bold text-slate-900">{{ $order->order_code }}</span>
            </div>

            <div class="flex justify-between items-center pb-2.5 border-b border-slate-200">
                <span class="text-slate-500 font-medium">Rute</span>
                <span class="font-bold text-slate-900">{{ $order->route->origin_city }} &rarr; {{ $order->route->destination_city }}</span>
            </div>

            <div class="flex justify-between items-center pb-2.5 border-b border-slate-200">
                <span class="text-slate-500 font-medium">Jadwal Keberangkatan</span>
                <span class="font-semibold text-slate-800">
                    {{ $order->route->departure_date->translatedFormat('d M Y') }}, {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }} WIB
                </span>
            </div>

            <div class="flex justify-between items-center pb-2.5 border-b border-slate-200">
                <span class="text-slate-500 font-medium">Metode Pembayaran</span>
                <span class="font-bold uppercase text-slate-900">{{ $order->payment?->payment_method ?? 'Simulasi' }}</span>
            </div>

            <div class="flex justify-between items-center pb-2.5 border-b border-slate-200">
                <span class="text-slate-500 font-medium">ID Transaksi</span>
                <span class="font-mono text-slate-700">{{ $order->payment?->transaction_id ?? '-' }}</span>
            </div>

            <div class="flex justify-between items-baseline pt-1">
                <span class="font-bold text-slate-900">Total Pembayaran</span>
                <span class="font-mono text-base font-extrabold text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <a href="{{ route('customer.orders.ticket', $order) }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 text-xs font-bold text-white shadow-md hover:bg-blue-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <span>Buka E-Ticket Resmi</span>
            </a>
            <a href="{{ route('customer.orders.show', $order) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition shadow-sm">
                Detail Pesanan
            </a>
        </div>

        <p class="text-[11px] text-slate-400">
            Bukti pemesanan dan tiket juga tersimpan aman di akun Anda.
        </p>
    </div>
</div>
@endsection
