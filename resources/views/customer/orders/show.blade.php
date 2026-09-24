
@extends('customer.layouts.index')

@section('title', 'Detail Pesanan — PO CAN Travel')

@section('content')

<div class="mb-6">
    <a href="{{ route('customer.orders.index') }}"
       class="text-sm font-medium text-gray-500 hover:text-gray-900">
        ← Kembali ke Pesanan
    </a>
</div>

<div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

    <div>
        <p class="text-sm text-gray-500">
            Detail Pesanan
        </p>

        <h1 class="mt-1 text-2xl font-bold text-gray-900">
            {{ $order->order_code }}
        </h1>
    </div>

    @php
        $statusClasses = [
            'pending' => 'bg-yellow-100 text-yellow-700',
            'confirmed' => 'bg-blue-100 text-blue-700',
            'paid' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
            'completed' => 'bg-gray-100 text-gray-700',
            'expired' => 'bg-gray-100 text-gray-600',
        ];
    @endphp

    <span class="inline-flex w-fit rounded-full px-3 py-1.5 text-sm font-semibold {{ $statusClasses[$order->order_status] ?? 'bg-gray-100 text-gray-600' }}">
        {{ ucfirst($order->order_status) }}
    </span>

</div>

<div class="grid gap-6 lg:grid-cols-3">

    {{-- Informasi perjalanan --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2">

        <h2 class="text-lg font-bold text-gray-900">
            Informasi Perjalanan
        </h2>

        <div class="mt-6 grid gap-6 md:grid-cols-2">

            <div>
                <p class="text-xs text-gray-500">
                    Rute
                </p>

                <p class="mt-1 text-base font-semibold text-gray-900">
                    {{ $order->route->origin_city }}
                    →
                    {{ $order->route->destination_city }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Bus
                </p>

                <p class="mt-1 text-base font-semibold text-gray-900">
                    {{ $order->route->bus->bus_name }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Tanggal Keberangkatan
                </p>

                <p class="mt-1 text-base font-semibold text-gray-900">
                    {{ $order->route->departure_date->format('d F Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Jam Keberangkatan
                </p>

                <p class="mt-1 text-base font-semibold text-gray-900">
                    {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }}
                </p>
            </div>

           <div class="md:col-span-2">
    <p class="text-xs text-gray-500">
        Terminal Asal
    </p>

    <p class="mt-1 text-sm font-medium text-gray-900">
        {{ $order->route->origin_terminal }}
    </p>
</div>

            <div>
                <p class="text-xs text-gray-500">
                    Terminal Tujuan
                </p>

                <p class="mt-1 text-sm font-medium text-gray-900">
                    {{ $order->route->destination_terminal }}
                </p>
            </div>

        </div>

    </div>

    {{-- Ringkasan pembayaran --}}
    <div class="h-fit rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-bold text-gray-900">
            Ringkasan Pembayaran
        </h2>

        <div class="mt-5 space-y-4 text-sm">

            <div class="flex justify-between gap-4">
                <span class="text-gray-500">
                    Penumpang
                </span>

                <span class="font-medium text-gray-900">
                    {{ $order->total_passengers }} orang
                </span>
            </div>

            <div class="flex justify-between gap-4">
                <span class="text-gray-500">
                    Harga per orang
                </span>

                <span class="font-medium text-gray-900">
                    Rp {{ number_format($order->route->price, 0, ',', '.') }}
                </span>
            </div>

            <div class="border-t border-gray-100 pt-4">

                <div class="flex justify-between gap-4">
                    <span class="font-semibold text-gray-900">
                        Total
                    </span>

                    <span class="text-lg font-bold text-gray-900">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </span>
                </div>

            </div>

        </div>

        @if (in_array($order->order_status, ['pending', 'confirmed'], true) && $order->payment_status !== 'verified')

            <a href="{{ route('customer.payments.create', $order) }}"
               class="mt-6 block rounded-lg bg-gray-900 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-gray-700">
                {{ $order->payment_status === 'pending' ? 'Perbarui Pembayaran' : 'Bayar Sekarang' }}
            </a>

        @endif

        @if ($order->payment_status === 'verified' && $order->ticket_code)
            <a href="{{ route('customer.orders.ticket', $order) }}"
               class="mt-3 block rounded-lg border border-gray-900 px-4 py-3 text-center text-sm font-semibold text-gray-900 hover:bg-gray-100">
                Lihat E-Ticket
            </a>
        @endif

    </div>

</div>

{{-- Data penumpang --}}
<div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-lg font-bold text-gray-900">
                Data Penumpang
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Daftar penumpang dalam pesanan ini.
            </p>
        </div>

        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
            {{ $order->details->count() }} Penumpang
        </span>

    </div>

    <div class="mt-6 overflow-x-auto">

        <table class="w-full min-w-[700px] text-left text-sm">

            <thead class="border-b border-gray-200 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-3 py-3">#</th>
                    <th class="px-3 py-3">Nama</th>
                    <th class="px-3 py-3">Telepon</th>
                    <th class="px-3 py-3">Email</th>
                    <th class="px-3 py-3">Kursi</th>
                    <th class="px-3 py-3 text-right">Harga</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @foreach ($order->details as $index => $detail)

                    <tr>
                        <td class="px-3 py-4 text-gray-500">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-3 py-4 font-medium text-gray-900">
                            {{ $detail->passenger_name }}
                        </td>

                        <td class="px-3 py-4 text-gray-600">
                            {{ $detail->passenger_phone }}
                        </td>

                        <td class="px-3 py-4 text-gray-600">
                            {{ $detail->passenger_email ?: '-' }}
                        </td>

                        <td class="px-3 py-4">
                            <span class="rounded-lg bg-gray-100 px-2.5 py-1 font-semibold text-gray-700">
                                {{ $detail->seat_number }}
                            </span>
                        </td>

                        <td class="px-3 py-4 text-right font-medium">
                            Rp {{ number_format($detail->price, 0, ',', '.') }}
                        </td>
                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

{{-- Informasi pembayaran --}}
<div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

        <div>
            <h2 class="text-lg font-bold text-gray-900">
                Informasi Pembayaran
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Status pembayaran untuk pesanan ini.
            </p>
        </div>

        @php
            $paymentClasses = [
                'unpaid' => 'bg-yellow-100 text-yellow-700',
                'pending' => 'bg-blue-100 text-blue-700',
                'verified' => 'bg-green-100 text-green-700',
                'rejected' => 'bg-red-100 text-red-700',
            ];
        @endphp

        <span class="inline-flex w-fit rounded-full px-3 py-1.5 text-sm font-semibold {{ $paymentClasses[$order->payment_status] ?? 'bg-gray-100 text-gray-600' }}">
            {{ ucfirst($order->payment_status) }}
        </span>

    </div>

    @if ($order->payment)

        <div class="mt-6 grid gap-5 md:grid-cols-3">

            <div>
                <p class="text-xs text-gray-500">
                    Metode Pembayaran
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900">
                    {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Jumlah
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900">
                    Rp {{ number_format($order->payment->amount, 0, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    ID Transaksi
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900">
                    {{ $order->payment->transaction_id ?: '-' }}
                </p>
            </div>

        </div>

        @if ($order->payment->notes)

            <div class="mt-5 rounded-lg bg-gray-50 p-4">
                <p class="text-xs font-medium text-gray-500">
                    Catatan
                </p>

                <p class="mt-1 text-sm text-gray-700">
                    {{ $order->payment->notes }}
                </p>
            </div>

        @endif

    @else

        <div class="mt-5 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-700">
            Data pembayaran belum tersedia.
        </div>

    @endif

</div>

{{-- Review perjalanan --}}
@if ($order->order_status === 'completed')
    <div class="mt-6 rounded-xl border border-amber-200 bg-white p-6 shadow-sm">
        <h2 class="font-bold text-gray-900">Review Perjalanan</h2>

        @if ($order->review)
            <p class="mt-2 text-sm text-gray-600">
                Anda sudah memberikan rating {{ $order->review->rating }}/5 untuk perjalanan ini.
            </p>
            @if ($order->review->comment)
                <p class="mt-3 rounded-lg bg-gray-50 p-4 text-sm text-gray-700">{{ $order->review->comment }}</p>
            @endif
        @else
            <p class="mt-1 text-sm text-gray-500">Bagikan pengalaman Anda setelah perjalanan selesai.</p>

            <form method="POST" action="{{ route('customer.orders.review', $order) }}" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label for="rating" class="mb-2 block text-sm font-medium text-gray-700">Rating</label>
                    <select id="rating" name="rating" required class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500 sm:w-48">
                        <option value="">Pilih rating</option>
                        @for ($rating = 5; $rating >= 1; $rating--)
                            <option value="{{ $rating }}" @selected(old('rating') == $rating)>{{ $rating }}/5</option>
                        @endfor
                    </select>
                    @error('rating')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="comment" class="mb-2 block text-sm font-medium text-gray-700">Komentar</label>
                    <textarea id="comment" name="comment" rows="3" maxlength="2000" class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500" placeholder="Ceritakan pengalaman Anda...">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-700">Kirim Review</button>
            </form>
        @endif
    </div>
@endif

{{-- Pembatalan --}}
@if (
    in_array($order->order_status, ['pending', 'confirmed'], true)
)

    <div class="mt-6 rounded-xl border border-red-200 bg-white p-6 shadow-sm">

        <h2 class="font-bold text-gray-900">
            Batalkan Pesanan
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Pembatalan akan mengembalikan kursi ke jadwal perjalanan.
        </p>

        <form method="POST"
              action="{{ route('customer.orders.cancel', $order) }}"
              class="mt-5">

            @csrf
            @method('PATCH')

            <label for="cancellation_note"
                   class="mb-2 block text-sm font-medium text-gray-700">
                Alasan pembatalan
            </label>

            <textarea
                id="cancellation_note"
                name="cancellation_note"
                rows="3"
                required
                minlength="5"
                maxlength="1000"
                placeholder="Masukkan alasan pembatalan..."
                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"
            >{{ old('cancellation_note') }}</textarea>

            @error('cancellation_note')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            <button type="submit"
                    onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                    class="mt-4 rounded-lg border border-red-300 px-4 py-2.5 text-sm font-semibold text-red-700 hover:bg-red-50">
                Batalkan Pesanan
            </button>

        </form>

    </div>

@endif

@endsection
