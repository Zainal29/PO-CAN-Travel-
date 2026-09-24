@extends('customer.layouts.index')

@section('title', 'E-Ticket — PO CAN Travel')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="no-print mb-6 flex items-center justify-between gap-4">
        <a href="{{ route('customer.orders.show', $order) }}" class="text-sm font-semibold text-slate-600 hover:text-brand-950">&larr; Kembali ke pesanan</a>
        <button type="button" onclick="window.print()" class="btn-secondary min-h-9 px-3 py-1.5">Cetak</button>
    </div>

    <article class="border border-slate-300 bg-white p-6 sm:p-8">
        <div class="flex items-start justify-between gap-4 border-b border-dashed border-slate-300 pb-6">
            <div>
                <p class="text-sm font-semibold text-brand-700">PO CAN Travel</p>
                <h1 class="mt-1 text-2xl font-bold text-brand-950">E-Ticket</h1>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500">Kode tiket</p>
                <p class="mt-1 font-mono text-lg font-bold tracking-wide text-brand-950">{{ $order->ticket_code }}</p>
            </div>
        </div>

        <div class="grid gap-6 py-6 sm:grid-cols-2">
            <div>
                <p class="text-xs text-gray-500">Rute</p>
                <p class="mt-1 font-semibold text-gray-900">{{ $order->route->origin_city }} &rarr; {{ $order->route->destination_city }}</p>
                <p class="mt-1 text-sm text-gray-600">{{ $order->route->bus->bus_name }} · {{ strtoupper($order->route->bus->bus_type) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Jadwal</p>
                <p class="mt-1 font-semibold text-gray-900">{{ $order->route->departure_date->translatedFormat('d F Y') }}, {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }}</p>
                <p class="mt-1 text-sm text-gray-600">Datang paling lambat 30 menit sebelum berangkat.</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Titik naik</p>
                <p class="mt-1 font-semibold text-gray-900">{{ $order->route->origin_terminal }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Titik turun</p>
                <p class="mt-1 font-semibold text-gray-900">{{ $order->route->destination_terminal }}</p>
            </div>
        </div>

        <div class="border-t border-dashed border-slate-300 pt-6">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-gray-900">QR e-ticket</h2>
                    <p class="mt-1 text-sm text-gray-500">Tunjukkan QR ini saat check-in.</p>
                </div>
                <img src="{{ $ticketQrCode }}" alt="QR code e-ticket {{ $order->order_code }}" class="h-40 w-40 border border-slate-300 p-2">
            </div>
            <a href="{{ route('customer.orders.ticket.qr', $order) }}" class="no-print btn-primary mt-4">Download E-Tiket</a>
        </div>

        <div class="border-t border-dashed border-slate-300 pt-6">
            <h2 class="font-semibold text-gray-900">Penumpang dan kursi</h2>
            <div class="mt-3 space-y-2 text-sm">
                @foreach ($order->details as $detail)
                    <div class="flex justify-between gap-4"><span>{{ $detail->passenger_name }}</span><span class="font-semibold">Kursi {{ $detail->seat_number }}</span></div>
                @endforeach
            </div>
        </div>

        <p class="mt-6 border-l-2 border-brand-500 bg-slate-50 p-4 text-xs leading-5 text-slate-600">Tunjukkan e-ticket dan identitas yang sesuai kepada petugas saat boarding. Kode tiket ini hanya berlaku untuk satu kali perjalanan.</p>
    </article>
</div>
@endsection
