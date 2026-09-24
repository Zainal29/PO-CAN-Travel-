
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
    {{-- Form --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2">

        <h2 class="text-lg font-bold text-gray-900">
            Data Pembayaran
        </h2>

          <p class="mt-1 text-sm text-gray-500">Ini adalah simulasi pembayaran internal aplikasi.</p>

          <form method="POST" action="{{ route('customer.payments.store', $order) }}" class="mt-6 space-y-6">

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

                    <option value="bca" @selected(old('payment_method', $order->payment?->payment_method) === 'bca')>
                        BCA
                    </option>

                    <option value="bri" @selected(old('payment_method', $order->payment?->payment_method) === 'bri')>
                        BRI
                    </option>

                    <option value="qris" @selected(old('payment_method', $order->payment?->payment_method) === 'qris')>
                        QRIS
                    </option>

                </select>

                @error('payment_method')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="payer_name" class="mb-2 block text-sm font-medium text-gray-700">Nama Pembayar</label>
                <input id="payer_name" name="payer_name" type="text" value="{{ old('payer_name', $order->user->name) }}" maxlength="255" required class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                @error('payer_name')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="payer_phone" class="mb-2 block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input id="payer_phone" name="payer_phone" type="text" value="{{ old('payer_phone', $order->user->phone) }}" maxlength="20" required class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                @error('payer_phone')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="border-t border-gray-100 pt-6">

                <button type="submit"
                        class="w-full rounded-lg bg-gray-900 px-5 py-3 text-sm font-semibold text-white hover:bg-gray-700">
                    Bayar Sekarang
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
                Setelah form valid, pembayaran simulasi akan langsung dinyatakan berhasil.
            </p>

        </div>

    </div>

</div>

@endsection
