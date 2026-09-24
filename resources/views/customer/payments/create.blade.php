@extends('customer.layouts.index')

@section('title', 'Pembayaran — PO CAN Travel')

@section('content')

<div class="mb-6">
    <a href="{{ route('customer.orders.show', $order) }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-brand-700">
        <span>←</span>
        Kembali ke Detail Pesanan
    </a>
</div>

<div class="mb-8">
    <p class="eyebrow">Pembayaran pesanan</p>


<div class="mt-2 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="page-heading">
            {{ $order->order_code }}
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Pilih metode pembayaran dan selesaikan simulasi pembayaran pesanan.
        </p>
    </div>

    <div class="inline-flex w-fit items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700">
        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
        Simulasi Pembayaran
    </div>
</div>


</div>

{{-- Progress --}}

<div class="mb-8 overflow-hidden border-b border-slate-200 pb-5">
    <ol class="grid grid-cols-4 gap-2 text-center text-xs font-semibold">
        <li class="text-slate-400">
            <span class="hidden sm:inline">1. </span>Perjalanan
        </li>


    <li class="text-slate-400">
        <span class="hidden sm:inline">2. </span>Kursi
    </li>

    <li class="text-slate-400">
        <span class="hidden sm:inline">3. </span>Review
    </li>

    <li class="text-brand-700">
        <span class="hidden sm:inline">4. </span>Pembayaran
    </li>
</ol>


</div>

<div class="grid gap-6 lg:grid-cols-3">


{{-- FORM PEMBAYARAN --}}
<div class="surface overflow-hidden lg:col-span-2">

    {{-- Header --}}
    <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-5 sm:px-6">
        <div class="flex items-start gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                    <path d="M3 10h18"></path>
                    <path d="M7 15h3"></path>
                </svg>
            </div>

            <div>
                <h2 class="section-title">
                    Data Pembayaran
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Pilih metode pembayaran yang ingin digunakan.
                </p>
            </div>
        </div>
    </div>

    <form method="POST"
          action="{{ route('customer.payments.store', $order) }}"
          class="space-y-6 p-5 sm:p-6">

        @csrf

        {{-- METODE PEMBAYARAN --}}
        <div>
            <label for="payment_method"
                   class="mb-2 block text-sm font-semibold text-slate-700">
                Metode Pembayaran
            </label>

            <select
                id="payment_method"
                name="payment_method"
                required
                class="w-full rounded-xl border-slate-300 bg-white py-3 text-sm font-medium text-slate-700 shadow-sm transition focus:border-brand-600 focus:ring-brand-600"
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
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- INSTRUKSI BCA --}}
        <div id="payment-info-bca"
             class="payment-info hidden overflow-hidden rounded-2xl border border-blue-100 bg-blue-50/60">

            <div class="border-b border-blue-100 px-5 py-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold text-slate-900">
                            Pembayaran BCA
                        </p>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Rekening simulasi PO CAN Travel
                        </p>
                    </div>

                    <span class="rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-blue-700 shadow-sm">
                        BCA
                    </span>
                </div>
            </div>

            <div class="grid gap-4 p-5 sm:grid-cols-2">
                <div class="rounded-xl border border-blue-100 bg-white p-4">
                    <p class="text-xs font-medium text-slate-500">
                        Nomor Rekening
                    </p>

                    <p class="mt-1 text-lg font-bold tracking-wide text-slate-900">
                        1234567890
                    </p>
                </div>

                <div class="rounded-xl border border-blue-100 bg-white p-4">
                    <p class="text-xs font-medium text-slate-500">
                        Atas Nama
                    </p>

                    <p class="mt-1 text-lg font-bold text-slate-900">
                        PO CAN Travel
                    </p>
                </div>
            </div>

            <div class="px-5 pb-5">
                <p class="text-xs leading-5 text-blue-700">
                    Dalam mode simulasi, Anda tidak perlu melakukan transfer sungguhan.
                    Cukup gunakan informasi ini sebagai tampilan instruksi pembayaran.
                </p>
            </div>
        </div>

        {{-- INSTRUKSI BRI --}}
        <div id="payment-info-bri"
             class="payment-info hidden overflow-hidden rounded-2xl border border-red-100 bg-red-50/60">

            <div class="border-b border-red-100 px-5 py-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold text-slate-900">
                            Pembayaran BRI
                        </p>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Rekening simulasi PO CAN Travel
                        </p>
                    </div>

                    <span class="rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-red-700 shadow-sm">
                        BRI
                    </span>
                </div>
            </div>

            <div class="grid gap-4 p-5 sm:grid-cols-2">
                <div class="rounded-xl border border-red-100 bg-white p-4">
                    <p class="text-xs font-medium text-slate-500">
                        Nomor Rekening
                    </p>

                    <p class="mt-1 text-lg font-bold tracking-wide text-slate-900">
                        9876543210
                    </p>
                </div>

                <div class="rounded-xl border border-red-100 bg-white p-4">
                    <p class="text-xs font-medium text-slate-500">
                        Atas Nama
                    </p>

                    <p class="mt-1 text-lg font-bold text-slate-900">
                        PO CAN Travel
                    </p>
                </div>
            </div>

            <div class="px-5 pb-5">
                <p class="text-xs leading-5 text-red-700">
                    Dalam mode simulasi, Anda tidak perlu melakukan transfer sungguhan.
                    Cukup gunakan informasi ini sebagai tampilan instruksi pembayaran.
                </p>
            </div>
        </div>

        {{-- INSTRUKSI QRIS --}}
        <div id="payment-info-qris"
             class="payment-info hidden overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">

            <div class="border-b border-slate-200 px-5 py-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold text-slate-900">
                            Pembayaran QRIS
                        </p>

                        <p class="mt-0.5 text-xs text-slate-500">
                            QR Code simulasi PO CAN Travel
                        </p>
                    </div>

                    <span class="rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm">
                        QRIS
                    </span>
                </div>
            </div>

            <div class="flex flex-col items-center gap-4 p-6 text-center">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <svg viewBox="0 0 180 180"
                         class="h-40 w-40 sm:h-44 sm:w-44"
                         aria-label="QRIS simulasi">

                        <rect width="180" height="180" fill="white"/>

                        <g fill="black">
                            <rect x="10" y="10" width="50" height="50"/>
                            <rect x="20" y="20" width="30" height="30" fill="white"/>
                            <rect x="27" y="27" width="16" height="16"/>

                            <rect x="120" y="10" width="50" height="50"/>
                            <rect x="130" y="20" width="30" height="30" fill="white"/>
                            <rect x="137" y="27" width="16" height="16"/>

                            <rect x="10" y="120" width="50" height="50"/>
                            <rect x="20" y="130" width="30" height="30" fill="white"/>
                            <rect x="27" y="137" width="16" height="16"/>

                            <rect x="75" y="10" width="10" height="10"/>
                            <rect x="90" y="10" width="10" height="20"/>
                            <rect x="70" y="35" width="20" height="10"/>
                            <rect x="95" y="35" width="15" height="15"/>

                            <rect x="70" y="70" width="15" height="15"/>
                            <rect x="90" y="70" width="10" height="25"/>
                            <rect x="105" y="70" width="20" height="10"/>
                            <rect x="130" y="70" width="10" height="20"/>
                            <rect x="150" y="70" width="20" height="10"/>

                            <rect x="70" y="95" width="10" height="15"/>
                            <rect x="85" y="100" width="20" height="10"/>
                            <rect x="110" y="90" width="15" height="20"/>
                            <rect x="135" y="95" width="15" height="15"/>
                            <rect x="155" y="90" width="15" height="20"/>

                            <rect x="70" y="120" width="20" height="10"/>
                            <rect x="95" y="120" width="10" height="20"/>
                            <rect x="110" y="120" width="25" height="10"/>
                            <rect x="140" y="120" width="10" height="25"/>
                            <rect x="155" y="125" width="15" height="15"/>

                            <rect x="70" y="145" width="15" height="15"/>
                            <rect x="90" y="150" width="20" height="10"/>
                            <rect x="115" y="145" width="15" height="15"/>
                            <rect x="135" y="155" width="20" height="15"/>
                            <rect x="160" y="150" width="10" height="20"/>
                        </g>
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-semibold text-slate-900">
                        QRIS Simulasi
                    </p>

                    <p class="mt-1 max-w-md text-xs leading-5 text-slate-500">
                        QR Code ini hanya untuk keperluan simulasi aplikasi dan
                        tidak dapat digunakan untuk transaksi nyata.
                    </p>
                </div>
            </div>
        </div>

        {{-- DATA PEMBAYAR --}}
        <div class="border-t border-slate-100 pt-6">

            <div class="mb-5">
                <h3 class="text-sm font-bold text-slate-900">
                    Data Pembayar
                </h3>

                <p class="mt-1 text-xs text-slate-500">
                    Data ini digunakan sebagai informasi transaksi simulasi.
                </p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">

                <div>
                    <label for="payer_name"
                           class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Pembayar
                    </label>

                    <input
                        id="payer_name"
                        name="payer_name"
                        type="text"
                        value="{{ old('payer_name', $order->user->name) }}"
                        maxlength="255"
                        required
                        autocomplete="name"
                        class="w-full rounded-xl border-slate-300 bg-white py-3 text-sm shadow-sm transition focus:border-brand-600 focus:ring-brand-600"
                    >

                    @error('payer_name')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="payer_phone"
                           class="mb-2 block text-sm font-semibold text-slate-700">
                        Nomor Telepon
                    </label>

                    <input
                        id="payer_phone"
                        name="payer_phone"
                        type="text"
                        value="{{ old('payer_phone', $order->user->phone) }}"
                        maxlength="20"
                        required
                        autocomplete="tel"
                        class="w-full rounded-xl border-slate-300 bg-white py-3 text-sm shadow-sm transition focus:border-brand-600 focus:ring-brand-600"
                    >

                    @error('payer_phone')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- TOTAL MOBILE --}}
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 lg:hidden">
            <div class="flex items-center justify-between gap-4">
                <span class="text-sm font-semibold text-slate-600">
                    Total Pembayaran
                </span>

                <span class="text-lg font-bold text-slate-950">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- SUBMIT --}}
        <div class="border-t border-slate-100 pt-6">

            <button type="submit"
                    class="btn-primary w-full justify-center py-3.5 text-sm font-bold">
                Konfirmasi & Bayar Sekarang
            </button>

            <p class="mt-3 text-center text-xs leading-5 text-slate-400">
                Dengan melanjutkan, transaksi akan diproses sebagai pembayaran
                simulasi internal PO CAN Travel.
            </p>
        </div>

    </form>
</div>

{{-- RINGKASAN PESANAN --}}
<div class="h-fit space-y-6">

    <div class="surface overflow-hidden">

        <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-4 sm:px-6">
            <h2 class="text-base font-bold text-slate-900">
                Ringkasan Pesanan
            </h2>
        </div>

        <div class="space-y-5 p-5 sm:p-6">

            {{-- ROUTE --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Perjalanan
                </p>

                <div class="mt-2 flex items-center gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-900">
                            {{ $order->route->origin_city }}
                        </p>

                        @if($order->route->origin_terminal)
                            <p class="mt-0.5 truncate text-xs text-slate-500">
                                {{ $order->route->origin_terminal }}
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-1 items-center gap-2">
                        <span class="h-px flex-1 bg-slate-200"></span>

                        <span class="text-slate-400">→</span>

                        <span class="h-px flex-1 bg-slate-200"></span>
                    </div>

                    <div class="min-w-0 text-right">
                        <p class="text-sm font-bold text-slate-900">
                            {{ $order->route->destination_city }}
                        </p>

                        @if($order->route->destination_terminal)
                            <p class="mt-0.5 truncate text-xs text-slate-500">
                                {{ $order->route->destination_terminal }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- DATE --}}
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="4" width="18" height="17" rx="2"></rect>
                        <path d="M16 2v4M8 2v4M3 10h18"></path>
                    </svg>
                </div>

                <div>
                    <p class="text-xs text-slate-400">
                        Tanggal Keberangkatan
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $order->route->departure_date->translatedFormat('d F Y') }}
                    </p>
                </div>
            </div>

            {{-- DEPARTURE --}}
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 7v5l3 2"></path>
                    </svg>
                </div>

                <div>
                    <p class="text-xs text-slate-400">
                        Keberangkatan
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }}
                    </p>
                </div>
            </div>

            {{-- PASSENGERS --}}
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="9" cy="8" r="3"></circle>
                        <path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"></path>
                        <path d="M16 5.5a3 3 0 0 1 0 5.8M18 14.5c1.8.8 3 2.7 3 5"></path>
                    </svg>
                </div>

                <div>
                    <p class="text-xs text-slate-400">
                        Penumpang
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $order->total_passengers }} orang
                    </p>
                </div>
            </div>

        </div>

        {{-- TOTAL --}}
        <div class="border-t border-slate-100 bg-slate-50/70 p-5 sm:p-6">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs text-slate-500">
                        Total Pembayaran
                    </p>

                    <p class="mt-1 text-xl font-bold text-slate-950">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </p>
                </div>

                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-bold text-amber-700">
                    BELUM BAYAR
                </span>
            </div>
        </div>

    </div>

    {{-- INFO SIMULASI --}}
    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">

        <div class="flex gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 10v6"></path>
                    <path d="M12 7h.01"></path>
                </svg>
            </div>

            <div>
                <h3 class="text-sm font-bold text-amber-900">
                    Pembayaran Simulasi
                </h3>

                <p class="mt-1 text-xs leading-5 text-amber-800">
                    PO CAN Travel saat ini menggunakan sistem pembayaran
                    simulasi internal. Tidak ada transaksi bank atau QRIS
                    sungguhan yang dilakukan.
                </p>
            </div>
        </div>

    </div>

</div>


</div>

{{-- PAYMENT METHOD INTERACTION --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('payment_method');

        const panels = {
            bca: document.getElementById('payment-info-bca'),
            bri: document.getElementById('payment-info-bri'),
            qris: document.getElementById('payment-info-qris'),
        };

        function updatePaymentInfo() {
            Object.values(panels).forEach(function (panel) {
                if (panel) {
                    panel.classList.add('hidden');
                }
            });

            const selected = select.value;

            if (panels[selected]) {
                panels[selected].classList.remove('hidden');
            }
        }

        select.addEventListener('change', updatePaymentInfo);

        updatePaymentInfo();
    });
</script>

@endsection
