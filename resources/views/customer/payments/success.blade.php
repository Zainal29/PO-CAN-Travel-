@extends('customer.layouts.index')

@section('title', 'Pembayaran Berhasil — PO CAN Travel')

@section('content')
<div class="mx-auto max-w-xl">
    <div class="rounded-2xl border border-emerald-200 bg-white p-8 text-center shadow-sm">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-2xl text-emerald-700">✓</div>
        <p class="mt-5 text-sm font-semibold text-emerald-700">Pembayaran Berhasil</p>
        <h1 class="mt-2 text-2xl font-bold text-gray-900">Pesanan {{ $order->order_code }}</h1>
        <p class="mt-3 text-sm leading-6 text-gray-600">Pembayaran simulasi internal PO CAN Travel telah berhasil diproses.</p>

        <div class="mt-6 space-y-3 rounded-xl bg-gray-50 p-5 text-left text-sm">
            <div class="flex justify-between gap-4"><span class="text-gray-500">Total</span><span class="font-semibold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></div>
            <div class="flex justify-between gap-4"><span class="text-gray-500">Metode</span><span class="font-semibold uppercase text-gray-900">{{ $order->payment->payment_method }}</span></div>
            <div class="flex justify-between gap-4"><span class="text-gray-500">Status</span><span class="font-semibold text-emerald-700">Lunas</span></div>
            <div class="flex justify-between gap-4"><span class="text-gray-500">ID Pembayaran</span><span class="font-mono text-xs text-gray-900">{{ $order->payment->transaction_id }}</span></div>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('customer.orders.ticket', $order) }}" class="rounded-lg bg-gray-900 px-5 py-3 text-sm font-semibold text-white hover:bg-gray-700">Lihat E-Ticket</a>
            <a href="{{ route('customer.orders.show', $order) }}" class="rounded-lg border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">Detail Pesanan</a>
        </div>
    </div>
</div>
@endsection
