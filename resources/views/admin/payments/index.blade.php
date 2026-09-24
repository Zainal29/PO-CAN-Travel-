<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Verifikasi Pembayaran
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

            <form
                method="GET"
                class="bg-white p-4 rounded-xl shadow mb-6"
            >
                <div class="grid md:grid-cols-4 gap-4">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Kode order..."
                        class="rounded-lg border-gray-300"
                    >

                    <select
                        name="status"
                        class="rounded-lg border-gray-300"
                    >
                        <option value="">
                            Semua Status
                        </option>

                        @foreach([
                            'unpaid',
                            'pending',
                            'verified',
                            'rejected',
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
                        name="payment_method"
                        class="rounded-lg border-gray-300"
                    >
                        <option value="">
                            Semua Metode
                        </option>

                        @foreach([
                            'transfer',
                            'virtual_account',
                            'e_wallet',
                            'cash',
                        ] as $method)
                            <option
                                value="{{ $method }}"
                                @selected(request('payment_method') === $method)
                            >
                                {{ ucfirst(str_replace('_', ' ', $method)) }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        type="submit"
                        class="bg-indigo-600 text-white rounded-lg"
                    >
                        Filter
                    </button>

                </div>
            </form>

            <div class="bg-white rounded-xl shadow overflow-hidden">

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    Order
                                </th>

                                <th class="px-6 py-3 text-left">
                                    Customer
                                </th>

                                <th class="px-6 py-3 text-left">
                                    Metode
                                </th>

                                <th class="px-6 py-3 text-left">
                                    Jumlah
                                </th>

                                <th class="px-6 py-3 text-left">
                                    Status
                                </th>

                                <th class="px-6 py-3 text-right">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($payments as $payment)
                                <tr class="border-t">

                                    <td class="px-6 py-4">
                                        {{ $payment->order->order_code }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $payment->order->user->name }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </td>

                                    <td class="px-6 py-4">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ ucfirst($payment->status) }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a
                                            href="{{ route('admin.payments.show', $payment) }}"
                                            class="text-indigo-600"
                                        >
                                            Detail
                                        </a>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="6"
                                        class="px-6 py-10 text-center text-gray-500"
                                    >
                                        Belum ada pembayaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6">
                    {{ $payments->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>