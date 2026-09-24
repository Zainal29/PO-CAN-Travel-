
@extends('customer.layouts.index')

@section('title', 'Pesanan Saya — PO CAN Travel')

@section('content')

<div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Pesanan Saya
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Lihat dan kelola seluruh pesanan tiket Anda.
        </p>
    </div>

    <a href="{{ route('customer.trips.index') }}"
       class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-700">
        Cari Perjalanan
    </a>
</div>

<div class="rounded-xl border border-gray-200 bg-white shadow-sm">

    <div class="overflow-x-auto">

        <table class="w-full min-w-[900px] text-left text-sm">

            <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-4">Kode Order</th>
                    <th class="px-5 py-4">Perjalanan</th>
                    <th class="px-5 py-4">Keberangkatan</th>
                    <!-- <th class="px-5 py-4">Penumpang</th> -->
                    <th class="px-5 py-4">Total</th>
                    <th class="px-5 py-4">Pembayaran</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse ($orders as $order)

                    @php
                       $orderStatusClasses = [
    'pending' => 'bg-yellow-100 text-yellow-700',
    'paid' => 'bg-green-100 text-green-700',
    'cancelled' => 'bg-red-100 text-red-700',
    'completed' => 'bg-gray-100 text-gray-700',
    'expired' => 'bg-gray-100 text-gray-600',
];

                        $paymentStatusClasses = [
                            'unpaid' => 'bg-yellow-100 text-yellow-700',
                            'pending' => 'bg-blue-100 text-blue-700',
                            'verified' => 'bg-green-100 text-green-700',
                            'rejected' => 'bg-red-100 text-red-700',
                        ];
                    @endphp

                    <tr class="hover:bg-gray-50">

                        <td class="px-5 py-4">
                            <a href="{{ route('customer.orders.show', $order) }}"
                               class="font-semibold text-gray-900 hover:text-blue-600">
                                {{ $order->order_code }}
                            </a>

                            <p class="mt-1 text-xs text-gray-400">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>
                        </td>

                        <td class="px-5 py-4">

                            <p class="font-medium text-gray-900">
                                {{ $order->route->origin_city }}
                                →
                                {{ $order->route->destination_city }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                {{ $order->route->bus->bus_name }}
                            </p>

                        </td>

                        <td class="px-5 py-4">

                            <p class="font-medium text-gray-900">
                                {{ $order->route->departure_date->format('d/m/Y') }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }}
                            </p>

                        </td>

                        <td class="px-5 py-4">
                            {{ $order->total_passengers }} orang
                        </td>

                        <td class="px-5 py-4 font-semibold text-gray-900">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4">

                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $paymentStatusClasses[$order->payment?->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($order->payment?->status ?? 'unpaid') }}
                            </span>

                        </td>

                        <td class="px-5 py-4">

                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $orderStatusClasses[$order->order_status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($order->order_status) }}
                            </span>

                        </td>

                        <td class="px-5 py-4 text-right">

                            <a href="{{ route('customer.orders.show', $order) }}"
                               class="font-medium text-blue-600 hover:text-blue-800">
                                Detail
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center">

                            <div class="mx-auto max-w-sm">

                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-500">
                                    <span class="text-xl">—</span>
                                </div>

                                <h2 class="mt-4 font-semibold text-gray-900">
                                    Belum ada pesanan
                                </h2>

                                <p class="mt-2 text-sm text-gray-500">
                                    Anda belum memiliki pesanan tiket.
                                </p>

                                <a href="{{ route('customer.trips.index') }}"
                                   class="mt-5 inline-flex rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-700">
                                    Cari Perjalanan
                                </a>

                            </div>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($orders->hasPages())

        <div class="border-t border-gray-200 px-5 py-4">
            {{ $orders->links() }}
        </div>

    @endif

</div>

@endsection
