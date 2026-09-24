@extends('customer.layouts.index')

@section('title', 'Pembayaran Tiket ' . $order->order_code . ' — PO CAN Travel')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    {{-- Back Link & Header --}}
    <div>
        <a href="{{ route('customer.orders.show', $order) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Detail Pesanan
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Checkout Tiket</p>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mt-1">Pembayaran Tiket Perjalanan</h1>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500">Kode Pesanan:</span>
                <span class="font-mono text-sm font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                    {{ $order->order_code }}
                </span>
            </div>
        </div>
    </div>

    {{-- Main Grid: 2 Columns on desktop --}}
    <div class="grid gap-6 lg:grid-cols-12 items-start">
        {{-- Left: Payment Form (7 cols) --}}
        <div class="lg:col-span-7">
            <form method="POST" action="{{ route('customer.payments.store', $order) }}" class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-6">
                @csrf

                <div>
                    <h2 class="text-base font-bold text-slate-900">1. Pilih Metode Pembayaran</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Pilih channel simulasi pembayaran yang ingin Anda gunakan</p>

                    <div class="mt-4 space-y-3" x-data="{ selectedMethod: '{{ old('payment_method', 'bca') }}' }">
                        {{-- BCA Option --}}
                        <label class="relative flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition"
                               :class="selectedMethod === 'bca' ? 'border-blue-600 bg-blue-50/40 text-blue-950 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300 text-slate-700'">
                            <div class="flex items-center gap-3.5">
                                <input type="radio" name="payment_method" value="bca" x-model="selectedMethod" required class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                <div>
                                    <p class="font-bold text-sm">BCA Virtual Account</p>
                                    <p class="text-xs text-slate-500">Simulasi transfer via m-BCA / KlikBCA / ATM BCA</p>
                                </div>
                            </div>
                            <span class="rounded bg-blue-600/10 px-2 py-0.5 font-bold text-[11px] text-blue-700">BCA</span>
                        </label>

                        {{-- BRI Option --}}
                        <label class="relative flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition"
                               :class="selectedMethod === 'bri' ? 'border-blue-600 bg-blue-50/40 text-blue-950 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300 text-slate-700'">
                            <div class="flex items-center gap-3.5">
                                <input type="radio" name="payment_method" value="bri" x-model="selectedMethod" required class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                <div>
                                    <p class="font-bold text-sm">BRI Virtual Account (BRIVA)</p>
                                    <p class="text-xs text-slate-500">Simulasi transfer via BRImo / ATM BRI</p>
                                </div>
                            </div>
                            <span class="rounded bg-blue-800/10 px-2 py-0.5 font-bold text-[11px] text-blue-800">BRI</span>
                        </label>

                        {{-- QRIS Option --}}
                        <label class="relative flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition"
                               :class="selectedMethod === 'qris' ? 'border-blue-600 bg-blue-50/40 text-blue-950 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300 text-slate-700'">
                            <div class="flex items-center gap-3.5">
                                <input type="radio" name="payment_method" value="qris" x-model="selectedMethod" required class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                <div>
                                    <p class="font-bold text-sm">QRIS Nasional</p>
                                    <p class="text-xs text-slate-500">GoPay, OVO, DANA, ShopeePay, LinkAja, & Mobile Banking</p>
                                </div>
                            </div>
                            <span class="rounded bg-red-600/10 px-2 py-0.5 font-bold text-[11px] text-red-700 font-mono">QRIS</span>
                        </label>
                    </div>

                    @error('payment_method')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Payer Information --}}
                <div class="border-t border-slate-100 pt-5 space-y-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">2. Identitas Pembayar</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Konfirmasi nama dan nomor kontak untuk verifikasi bukti pembayaran</p>
                    </div>

                    <div>
                        <label for="payer_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nama Lengkap Pembayar
                        </label>
                        <input
                            type="text"
                            id="payer_name"
                            name="payer_name"
                            value="{{ old('payer_name', auth()->user()?->name ?? $order->customer_name) }}"
                            required
                            maxlength="100"
                            placeholder="Contoh: Budi Santoso"
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                        >
                        @error('payer_name')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payer_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nomor Telepon / WhatsApp
                        </label>
                        <input
                            type="tel"
                            id="payer_phone"
                            name="payer_phone"
                            value="{{ old('payer_phone', auth()->user()?->phone ?? $order->customer_phone) }}"
                            required
                            pattern="[0-9]{10,14}"
                            placeholder="Contoh: 081234567890"
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                        >
                        <p class="text-[11px] text-slate-400 mt-1">Masukkan 10-14 digit angka nomor telepon aktif Anda.</p>
                        @error('payer_phone')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Simulation Notice Banner --}}
                <div class="rounded-xl border border-blue-200 bg-blue-50/70 p-4 text-xs text-blue-900 flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8V5m0 14a9 9 0 110-18 9 9 0 0118 0z"/>
                    </svg>
                    <div class="leading-relaxed">
                        <strong>simulasi pembayaran internal:</strong> pembayaran ini tidak terhubung ke bank nyata. Hanya untuk demonstrasi aplikasi.
                    </div>
                </div>

                {{-- Submit CTA Button --}}
                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-md hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 transition flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Bayar Sekarang (Rp {{ number_format($order->total_price, 0, ',', '.') }})</span>
                </button>
            </form>
        </div>

        {{-- Right: Order Summary Sidebar (5 cols) --}}
        <div class="lg:col-span-5 space-y-5">
            {{-- Summary Card --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900">Ringkasan Tagihan</h2>
                    <p class="text-xs text-slate-500">Tiket perjalanan PO CAN Travel</p>
                </div>

                {{-- Trip Summary --}}
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100 space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold uppercase text-[10px]">Rute Perjalanan</span>
                        <p class="font-extrabold text-sm text-slate-900 mt-0.5">
                            {{ $order->route->origin_city }} &rarr; {{ $order->route->destination_city }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-200/60">
                        <div>
                            <span class="text-slate-400 font-semibold uppercase text-[10px]">Jadwal Berangkat</span>
                            <p class="font-bold text-slate-800 mt-0.5">{{ $order->route->departure_date->translatedFormat('d M Y') }}</p>
                            <p class="font-mono text-slate-600 text-[11px]">{{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }} WIB</p>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold uppercase text-[10px]">Armada</span>
                            <p class="font-bold text-slate-800 mt-0.5">{{ $order->route->bus->bus_name }}</p>
                            <p class="capitalize text-slate-600 text-[11px]">{{ str_replace('_', ' ', $order->route->bus->bus_type) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Cost Breakdown --}}
                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Tarif Tiket ({{ $order->total_passengers }} Kursi)</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Administrasi</span>
                        <span class="font-semibold text-emerald-600">Gratis (Rp 0)</span>
                    </div>

                    <div class="border-t border-slate-100 pt-3 flex justify-between items-baseline">
                        <span class="text-sm font-bold text-slate-900">Total Pembayaran</span>
                        <span class="text-2xl font-black text-blue-600 font-mono">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Trust Badges --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 text-xs text-slate-600 space-y-2.5 shadow-sm">
                <div class="flex items-center gap-2 font-bold text-slate-800">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Garansi Pemesanan Resmi</span>
                </div>
                <p class="leading-relaxed text-slate-500">
                    Tiket resmi diterbitkan langsung oleh sistem manajemen operasional armada PO CAN Travel dan terlindungi secara aman.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
