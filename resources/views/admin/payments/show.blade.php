<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition mb-2">
                    &larr; Kembali ke riwayat pembayaran
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        Detail Transaksi
                    </h1>
                    <x-status-badge :status="$payment->status" type="payment" />
                </div>
                <p class="mt-1 text-sm text-slate-500">
                    ID Transaksi: <span class="font-mono font-semibold text-slate-800">{{ $payment->transaction_id ?? '-' }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-7 sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800 flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800 flex items-center gap-2">
                <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Payment Detail Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Informasi Pembayaran</h2>
                    <p class="text-xs text-slate-500">Rincian status dan metode pembayaran yang digunakan.</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-400">Total Nominal</p>
                    <p class="text-2xl font-black text-brand-950">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-5 text-xs">
                <div>
                    <span class="text-slate-400 font-semibold uppercase">Metode Pembayaran</span>
                    <p class="mt-1 text-sm font-bold text-slate-900 uppercase">{{ str_replace('_', ' ', $payment->payment_method) }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase">Status Pembayaran</span>
                    <div class="mt-1">
                        <x-status-badge :status="$payment->status" type="payment" />
                    </div>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase">Waktu Pembayaran</span>
                    <p class="mt-1 text-sm font-medium text-slate-900">
                        {{ $payment->paid_at ? $payment->paid_at->translatedFormat('d F Y, H:i') . ' WIB' : 'Belum Dibayar' }}
                    </p>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase">ID Transaksi</span>
                    <p class="mt-1 font-mono text-sm font-semibold text-slate-800">{{ $payment->transaction_id ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase">Dibuat Pada</span>
                    <p class="mt-1 text-sm text-slate-700">{{ $payment->created_at->format('d/m/Y H:i') }} WIB</p>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase">Catatan</span>
                    <p class="mt-1 text-sm text-slate-700">{{ $payment->notes ?: '-' }}</p>
                </div>
            </div>

            @if($payment->payment_proof)
                <div class="pt-4 border-t border-slate-100">
                    <span class="block text-xs font-semibold uppercase text-slate-400 mb-2">Bukti Pembayaran</span>
                    <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Bukti Transfer" class="max-w-xs rounded-xl border border-slate-200">
                </div>
            @endif
        </div>

        {{-- Associated Order Card --}}
        @if($payment->order)
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Pesanan Terkait</h3>
                        <p class="text-xs text-slate-500">Tiket dan data rute yang dibayar dalam transaksi ini.</p>
                    </div>
                    <a href="{{ route('admin.orders.show', $payment->order) }}" class="text-xs font-semibold text-amber-700 hover:text-amber-900">
                        Buka Detail Order &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Kode Order</span>
                        <p class="font-mono text-sm font-bold text-brand-950 mt-1">{{ $payment->order->order_code }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Pelanggan</span>
                        <p class="text-sm font-bold text-slate-900 mt-1">{{ $payment->order->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Rute</span>
                        <p class="text-sm font-bold text-slate-900 mt-1">
                            {{ $payment->order->route->origin_city ?? '-' }} &rarr; {{ $payment->order->route->destination_city ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Jadwal</span>
                        <p class="text-sm font-medium text-slate-700 mt-1">
                            {{ $payment->order->route?->departure_date?->format('d/m/Y') }} · {{ $payment->order->route?->departure_time }}
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Data pesanan terkait telah diarsipkan.</p>
            </div>
        @endif
    </div>
</x-app-layout>
