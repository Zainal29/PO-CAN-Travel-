<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Manajemen Order
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form method="GET" class="bg-white p-4 rounded-xl shadow mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari kode order / customer..."
                        class="rounded-lg border-gray-300"
                    >

                    <select name="status" class="rounded-lg border-gray-300">
                        <option value="">Semua Status Order</option>

                       @foreach([
    'pending',
    'paid',
    'cancelled',
    'completed',
    'expired',
] as $status)
                            <option
                                value="{{ $status }}"
                                @selected(request('status') === $status)
                            >
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>

                    <select
                        name="payment_status"
                        class="rounded-lg border-gray-300"
                    >
                        <option value="">Semua Status Pembayaran</option>

                        @foreach([
                            'unpaid',
                            'pending',
                            'verified',
                            'rejected',
                        ] as $status)
                            <option
                                value="{{ $status }}"
                                @selected(request('payment_status') === $status)
                            >
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        type="submit"
                        class="bg-indigo-600 text-white rounded-lg px-4 py-2"
                    >
                        Filter
                    </button>
                </div>
            </form>

            <div class="bg-white shadow rounded-xl overflow-hidden">

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">Order</th>
                                <th class="px-6 py-3 text-left">Customer</th>
                                <th class="px-6 py-3 text-left">Perjalanan</th>
                                <th class="px-6 py-3 text-left">Penumpang</th>
                                <th class="px-6 py-3 text-left">Total</th>
                                <th class="px-6 py-3 text-left">Pembayaran</th>
                                <th class="px-6 py-3 text-left">Order</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($orders as $order)
                                <tr class="border-t">

                                    <td class="px-6 py-4 font-medium">
                                        {{ $order->order_code }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div>
                                            {{ $order->user->name }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            {{ $order->user->email }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $order->route->origin_city }}
                                        →
                                        {{ $order->route->destination_city }}

                                        <div class="text-sm text-gray-500">
                                            {{ $order->route->departure_date?->format('d/m/Y') }}
                                            {{ $order->route->departure_time }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $order->total_passengers }}
                                    </td>

                                    <td class="px-6 py-4">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ ucfirst($order->payment?->status ?? 'unpaid') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ ucfirst($order->order_status) }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a
                                            href="{{ route('admin.orders.show', $order) }}"
                                            class="text-indigo-600"
                                        >
                                            Detail
                                        </a>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="8"
                                        class="px-6 py-10 text-center text-gray-500"
                                    >
                                        Belum ada order.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6">
                    {{ $orders->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>