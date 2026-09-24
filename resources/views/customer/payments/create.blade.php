
@extends('customer.layouts.index')

@section('title', 'Pembayaran — PO CAN Travel')

@section('content')

<div class="mb-6">
    <a href="{{ route('customer.orders.show', $order) }}"
       class="text-sm font-medium text-gray-500 hover:text-gray-900">
        ← Kembali ke Detail Pesanan
    </a>
</div>

<div class="mb-8">
    <p class="text-sm text-gray-500">
        Pembayaran Pesanan
    </p>

    <h1 class="mt-1 text-2xl font-bold text-gray-900">
        {{ $order->order_code }}
    </h1>

    <p class="mt-1 text-sm text-gray-500">
        Selesaikan pembayaran untuk melanjutkan proses pesanan.
    </p>
</div>

<div class="grid gap-6 lg:grid-cols-3">

    <div class="rounded-xl border border-amber-200 bg-amber-50 p-6 shadow-sm lg:col-span-3">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
            <img src="{{ $paymentQrCode }}" alt="QR code informasi rekening" class="h-40 w-40 rounded-lg bg-white p-2">
            <div>
                <h2 class="text-lg font-bold text-amber-900">Informasi rekening</h2>
                <p class="mt-1 text-sm text-amber-800">Scan QR code untuk melihat info rekening.</p>
                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-amber-900">Transfer ke:
Bank: BCA
No. Rek: 1234567890
Atas Nama: PO CAN Travel
Nominal: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2">

        <h2 class="text-lg font-bold text-gray-900">
            Data Pembayaran
        </h2>

        <form method="POST"
              action="{{ route('customer.payments.store', $order) }}"
              enctype="multipart/form-data"
              class="mt-6 space-y-6">

            @csrf

            <div>
                <label for="payment_method"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Metode Pembayaran
                </label>

                <select
                    id="payment_method"
                    name="payment_method"
                    required
                    class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                >

                    <option value="">
                        Pilih metode pembayaran
                    </option>

                    <option value="transfer"
                        @selected(old('payment_method', $order->payment?->payment_method) === 'transfer')>
                        Transfer Bank
                    </option>

                    <option value="virtual_account"
                        @selected(old('payment_method', $order->payment?->payment_method) === 'virtual_account')>
                        Virtual Account
                    </option>

                    <option value="e_wallet"
                        @selected(old('payment_method', $order->payment?->payment_method) === 'e_wallet')>
                        E-Wallet
                    </option>

                    <option value="cash"
                        @selected(old('payment_method', $order->payment?->payment_method) === 'cash')>
                        Cash
                    </option>

                </select>

                @error('payment_method')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="transaction_id"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    ID Transaksi
                    <span class="font-normal text-gray-400">(opsional)</span>
                </label>

                <input
                    id="transaction_id"
                    name="transaction_id"
                    type="text"
                    value="{{ old('transaction_id', $order->payment?->transaction_id) }}"
                    maxlength="100"
                    placeholder="Contoh: TRX123456789"
                    class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                >

                <p class="mt-1 text-xs text-gray-500">
                    Isi jika Anda memiliki nomor referensi transaksi.
                </p>

                @error('transaction_id')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="payment_proof"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Bukti Pembayaran
                    <span class="font-normal text-gray-400">(opsional)</span>
                </label>

                <input
                    id="payment_proof"
                    name="payment_proof"
                    type="file"
                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    class="block w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-sm file:font-medium"
                >

                <p class="mt-1 text-xs text-gray-500">
                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.
                </p>

                @if ($order->payment?->payment_proof)

                    <p class="mt-2 text-xs text-gray-500">
                        Bukti pembayaran sebelumnya sudah tersimpan.
                        Upload file baru hanya jika ingin menggantinya.
                    </p>

                @endif

                @error('payment_proof')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="notes"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Catatan
                    <span class="font-normal text-gray-400">(opsional)</span>
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    rows="4"
                    maxlength="1000"
                    placeholder="Tambahkan catatan jika diperlukan..."
                    class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                >{{ old('notes', $order->payment?->notes) }}</textarea>

                @error('notes')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="border-t border-gray-100 pt-6">

                <button type="submit"
                        class="w-full rounded-lg bg-gray-900 px-5 py-3 text-sm font-semibold text-white hover:bg-gray-700">
                    Kirim Pembayaran
                </button>

            </div>

        </form>

    </div>

    {{-- Ringkasan --}}
    <div class="h-fit space-y-6">

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Ringkasan Pesanan
            </h2>

            <div class="mt-5 space-y-4">

                <div>
                    <p class="text-xs text-gray-500">
                        Rute
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $order->route->origin_city }}
                        →
                        {{ $order->route->destination_city }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        Tanggal
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $order->route->departure_date->format('d F Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        Keberangkatan
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        Jumlah Penumpang
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $order->total_passengers }} orang
                    </p>
                </div>

            </div>

            <div class="mt-6 border-t border-gray-100 pt-5">

                <div class="flex items-center justify-between">
                    <span class="font-semibold text-gray-900">
                        Total Pembayaran
                    </span>

                    <span class="text-xl font-bold text-gray-900">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </span>
                </div>

            </div>

        </div>

        <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-5">

            <h3 class="font-semibold text-yellow-800">
                Perhatian
            </h3>

            <p class="mt-2 text-sm leading-6 text-yellow-700">
                Pastikan data pembayaran yang Anda masukkan sudah benar.
                Pembayaran akan diperiksa oleh admin sebelum pesanan dinyatakan lunas.
            </p>

        </div>

    </div>

</div>

@endsection
