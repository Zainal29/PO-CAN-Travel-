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

                @if($payment->payment_proof)
                    <div class="mt-8">

                        <h4 class="font-semibold mb-3">
                            Bukti Pembayaran
                        </h4>

                        <a
                            href="{{ Storage::url($payment->payment_proof) }}"
                            target="_blank"
                        >
                            <img
                                src="{{ Storage::url($payment->payment_proof) }}"
                                alt="Bukti pembayaran"
                                class="max-w-md rounded-lg border"
                            >
                        </a>

                    </div>
                @endif

                @if($payment->notes)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <strong>Catatan:</strong>

                        <p class="mt-1">
                            {{ $payment->notes }}
                        </p>
                    </div>
                @endif

                @if($payment->status === 'pending')

                    <div class="mt-8 grid md:grid-cols-2 gap-6">

                        <form
                            method="POST"
                            action="{{ route('admin.payments.verify', $payment) }}"
                        >
                            @csrf
                            @method('PATCH')

                            <label class="block mb-2 font-medium">
                                Catatan Verifikasi
                            </label>

                            <textarea
                                name="notes"
                                rows="3"
                                class="w-full rounded-lg border-gray-300"
                                placeholder="Catatan admin..."
                            ></textarea>

                            <button
                                type="submit"
                                class="mt-3 w-full px-4 py-2 bg-green-600 text-white rounded-lg"
                                onclick="return confirm('Verifikasi pembayaran ini?')"
                            >
                                Verifikasi Pembayaran
                            </button>
                        </form>

                        <form
                            method="POST"
                            action="{{ route('admin.payments.reject', $payment) }}"
                        >
                            @csrf
                            @method('PATCH')

                            <label class="block mb-2 font-medium">
                                Alasan Penolakan
                            </label>

                            <textarea
                                name="notes"
                                rows="3"
                                required
                                class="w-full rounded-lg border-gray-300"
                                placeholder="Jelaskan alasan penolakan..."
                            ></textarea>

                            <button
                                type="submit"
                                class="mt-3 w-full px-4 py-2 bg-red-600 text-white rounded-lg"
                                onclick="return confirm('Tolak pembayaran ini?')"
                            >
                                Tolak Pembayaran
                            </button>
                        </form>

                    </div>

                @endif

            </div>
        </div>
    </div>
</x-app-layout>
