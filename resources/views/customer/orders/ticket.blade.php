@extends('customer.layouts.index')

@section('title', 'E-Ticket — PO CAN Travel')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="mb-6 flex items-center justify-between gap-4">
        <a href="{{ route('customer.orders.show', $order) }}" class="text-sm font-medium text-gray-500 hover:text-gray-900">&larr; Kembali ke pesanan</a>
        <button type="button" onclick="window.print()" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold hover:bg-gray-100">Cetak</button>
    </div>

    <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex items-start justify-between gap-4 border-b border-dashed border-gray-300 pb-6">
            <div>
                <p class="text-sm font-semibold text-gray-500">PO CAN Travel</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900">E-Ticket</h1>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Kode tiket</p>
                <p class="mt-1 font-mono text-lg font-bold tracking-wide text-gray-900">{{ $order->ticket_code }}</p>
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

        <div class="border-t border-dashed border-gray-300 pt-6">
            <h2 class="font-semibold text-gray-900">Penumpang dan kursi</h2>
            <div class="mt-3 space-y-2 text-sm">
                @foreach ($order->details as $detail)
                    <div class="flex justify-between gap-4"><span>{{ $detail->passenger_name }}</span><span class="font-semibold">Kursi {{ $detail->seat_number }}</span></div>
                @endforeach
            </div>
        </div>

        <p class="mt-6 rounded-lg bg-gray-50 p-4 text-xs leading-5 text-gray-600">Tunjukkan e-ticket dan identitas yang sesuai kepada petugas saat boarding. Kode tiket ini hanya berlaku untuk satu kali perjalanan.</p>
    </article>
</div>
@endsection
