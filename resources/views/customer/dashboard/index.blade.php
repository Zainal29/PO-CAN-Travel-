@extends('customer.layouts.index')

@section('title', 'Dashboard Customer — PO CAN Travel')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">
        Selamat datang, {{ auth()->user()->name }}
    </h1>

    <p class="mt-1 text-sm text-gray-500">
        Kelola perjalanan dan pesanan tiket Anda.
    </p>
</div>

<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Total Pesanan</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ $stats['total_orders'] }}
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Menunggu Pembayaran</p>
        <p class="mt-2 text-3xl font-bold text-yellow-600">
            {{ $stats['pending_orders'] }}
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Sudah Dibayar</p>
        <p class="mt-2 text-3xl font-bold text-green-600">
            {{ $stats['paid_orders'] }}
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Selesai</p>
        <p class="mt-2 text-3xl font-bold text-blue-600">
            {{ $stats['completed_orders'] }}
        </p>
    </div>

</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    Pesanan Terbaru
                </h2>

                <p class="text-sm text-gray-500">
                    Riwayat pesanan terbaru Anda.
                </p>
            </div>

            <a href="{{ route('customer.orders.index') }}"
               class="text-sm font-medium text-blue-600 hover:text-blue-800">
                Lihat semua
            </a>
        </div>

        <div class="mt-5 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-200 text-gray-500">
                    <tr>
                        <th class="px-3 py-3">Kode</th>
                        <th class="px-3 py-3">Perjalanan</th>
                        <th class="px-3 py-3">Tanggal</th>
                        <th class="px-3 py-3">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($latestOrders as $order)

                        <tr>
                            <td class="px-3 py-4 font-medium text-gray-900">
                                <a href="{{ route('customer.orders.show', $order) }}"
                                   class="hover:text-blue-600">
                                    {{ $order->order_code }}
                                </a>
                            </td>

                            <td class="px-3 py-4">
                                {{ $order->route->origin_city }}
                                →
                                {{ $order->route->destination_city }}
                            </td>

                            <td class="px-3 py-4">
                                {{ $order->route->departure_date->format('d/m/Y') }}
                            </td>

                            <td class="px-3 py-4">
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="px-3 py-8 text-center text-gray-500">
                                Belum ada pesanan.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

    </div>

    <div class="rounded-xl bg-gray-900 p-6 text-white">

        <h2 class="text-lg font-bold">
            Mau bepergian?
        </h2>

        <p class="mt-2 text-sm text-gray-300">
            Cari jadwal perjalanan dan pesan tiket Anda sekarang.
        </p>

        <a href="{{ route('customer.trips.index') }}"
           class="mt-6 inline-flex rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-100">
            Cari Perjalanan
        </a>

    </div>

</div>

@endsection

    
