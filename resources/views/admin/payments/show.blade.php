<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Detail Pembayaran
            </h2>

            <a
                href="{{ route('admin.payments.index') }}"
                class="text-indigo-600"
            >
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-6">

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

            <div class="bg-white p-6 rounded-xl shadow">

                <h3 class="text-lg font-semibold mb-6">
                    Informasi Pembayaran
                </h3>

                <div class="grid md:grid-cols-2 gap-5">

                    <div>
                        <span class="text-gray-500">
                            Order
                        </span>

                        <p class="font-semibold">
                            {{ $payment->order->order_code }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">
                            Customer
                        </span>

                        <p>
                            {{ $payment->order->user->name }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">
                            Metode Pembayaran
                        </span>

                        <p>
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">
                            Jumlah
                        </span>

                        <p class="font-semibold">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">
                            Transaction ID
                        </span>

                        <p>
                            {{ $payment->transaction_id ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">
                            Status
                        </span>

                        <p>
                            {{ ucfirst($payment->status) }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">
                            Dibayar
                        </span>

                        <p>
                            {{ $payment->paid_at?->format('d/m/Y H:i') ?? '-' }}
                        </p>
                    </div>

                </div>

                @if($payment->notes)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <strong>Catatan:</strong>

                        <p class="mt-1">
                            {{ $payment->notes }}
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
