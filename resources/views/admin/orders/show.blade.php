<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Detail Order
            </h2>

            <a
                href="{{ route('admin.orders.index') }}"
                class="text-indigo-600"
            >
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-6 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-100 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold mb-4">
                    Informasi Order
                </h3>

                <div class="grid md:grid-cols-2 gap-4">

                    <div>
                        <span class="text-gray-500">Kode Order</span>
                        <p class="font-semibold">
                            {{ $order->order_code }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">Customer</span>
                        <p class="font-semibold">
                            {{ $order->user->name }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">Email</span>
                        <p>
                            {{ $order->user->email }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">Jumlah Penumpang</span>
                        <p>
                            {{ $order->total_passengers }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">Total Harga</span>
                        <p class="font-semibold">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">Status Order</span>
                        <p>
                            {{ ucfirst($order->order_status) }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">Status Pembayaran</span>
                        <p>
                            {{ ucfirst($order->payment_status) }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">Expired</span>
                        <p>
                            {{ $order->expired_at?->format('d/m/Y H:i') ?? '-' }}
                        </p>
                    </div>

                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold mb-4">
                    Perjalanan
                </h3>

                <p class="font-semibold">
                    {{ $order->route->origin_city }}
                    →
                    {{ $order->route->destination_city }}
                </p>

                <p class="text-gray-500">
                    {{ $order->route->origin_terminal }}
                    →
                    {{ $order->route->destination_terminal }}
                </p>

                <p class="mt-2">
                    {{ $order->route->departure_date?->format('d F Y') }}
                    —
                    {{ $order->route->departure_time }}
                </p>

                <p class="mt-1 text-gray-500">
                    Bus:
                    {{ $order->route->bus->bus_name }}
                    ({{ $order->route->bus->plate_number }})
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold mb-4">
                    Data Penumpang
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="py-3 text-left">Nama</th>
                                <th class="py-3 text-left">Telepon</th>
                                <th class="py-3 text-left">Email</th>
                                <th class="py-3 text-left">Kursi</th>
                                <th class="py-3 text-left">Harga</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($order->details as $detail)
                                <tr class="border-b">
                                    <td class="py-3">
                                        {{ $detail->passenger_name }}
                                    </td>

                                    <td class="py-3">
                                        {{ $detail->passenger_phone }}
                                    </td>

                                    <td class="py-3">
                                        {{ $detail->passenger_email ?? '-' }}
                                    </td>

                                    <td class="py-3 font-semibold">
                                        {{ $detail->seat_number }}
                                    </td>

                                    <td class="py-3">
                                        Rp {{ number_format($detail->price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($order->payment)
                <div class="bg-white p-6 rounded-xl shadow">

                    <h3 class="text-lg font-semibold mb-4">
                        Pembayaran
                    </h3>

                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <span class="text-gray-500">
                                Metode
                            </span>

                            <p>
                                {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}
                            </p>
                        </div>

                        <div>
                            <span class="text-gray-500">
                                Status
                            </span>

                            <p>
                                {{ ucfirst($order->payment->status) }}
                            </p>
                        </div>

                        <div>
                            <span class="text-gray-500">
                                Jumlah
                            </span>

                            <p>
                                Rp {{ number_format($order->payment->amount, 0, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <span class="text-gray-500">
                                Transaction ID
                            </span>

                            <p>
                                {{ $order->payment->transaction_id ?? '-' }}
                            </p>
                        </div>

                    </div>

                    @if($order->payment->payment_proof)
                        <div class="mt-6">
                            <p class="text-gray-500 mb-2">
                                Bukti Pembayaran
                            </p>

                            <a
                                href="{{ Storage::url($order->payment->payment_proof) }}"
                                target="_blank"
                                class="text-indigo-600"
                            >
                                Lihat Bukti Pembayaran
                            </a>
                        </div>
                    @endif

                </div>
            @endif

            <div class="bg-white p-6 rounded-xl shadow">

                <h3 class="text-lg font-semibold mb-4">
                    Pengelolaan Order
                </h3>

                @if($order->order_status === 'pending' || $order->order_status === 'paid')
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex gap-3">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="order_status" value="{{ $order->order_status === 'pending' ? 'confirmed' : 'completed' }}">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
                            {{ $order->order_status === 'pending' ? 'Konfirmasi Order' : 'Selesaikan Order' }}
                        </button>
                    </form>
                @endif

                @if(in_array($order->order_status, ['pending', 'confirmed'], true))
                    <form
                        method="POST"
                        action="{{ route('admin.orders.cancel', $order) }}"
                        class="mt-6"
                    >
                        @csrf
                        @method('PATCH')

                        <textarea
                            name="cancellation_note"
                            required
                            rows="3"
                            placeholder="Alasan pembatalan..."
                            class="w-full rounded-lg border-gray-300"
                        ></textarea>

                        <button
                            type="submit"
                            class="mt-3 px-4 py-2 bg-red-600 text-white rounded-lg"
                            onclick="return confirm('Batalkan order ini?')"
                        >
                            Batalkan Order
                        </button>
                    </form>
                @endif

                @if ($order->payment_status === 'verified' && in_array($order->order_status, ['paid', 'confirmed'], true) && ! $order->checked_in_at)
                    <form method="POST" action="{{ route('admin.orders.check-in', $order) }}" class="mt-6">
                        @csrf
                        @method('PATCH')
                        <button class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Catat Check-in</button>
                    </form>
                @elseif ($order->checked_in_at)
                    <p class="mt-6 text-sm font-medium text-emerald-700">Sudah check-in: {{ $order->checked_in_at->format('d M Y H:i') }}</p>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
