<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600">Finansial & Kas</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Riwayat Pembayaran</h1>
                <p class="mt-1 text-sm text-slate-500">Daftar transaksi pembayaran tiket, metode transfer/QRIS, dan verifikasi dana.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.payments.export-excel', request()->all()) }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition"
                   title="Unduh laporan keuangan pendapatan dalam format Excel/CSV">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Cetak Excel Keuangan</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8 space-y-6">
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

        {{-- Filter Box --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Cari Kode Order</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Contoh: PO-2026..."
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Status Pembayaran</label>
                    <select name="status" class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="">Semua Status</option>
                        @foreach(['unpaid' => 'Belum Dibayar', 'pending' => 'Pending Verifikasi', 'verified' => 'Terverifikasi (Lunas)', 'rejected' => 'Ditolak'] as $status => $label)
                            <option value="{{ $status }}" @selected(request('status') === $status)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Metode Pembayaran</label>
                    <select name="payment_method" class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="">Semua Metode</option>
                        @foreach([
                            'bca' => 'Bank BCA',
                            'bri' => 'Bank BRI',
                            'qris' => 'QRIS Simulasi',
                            'transfer' => 'Transfer Bank',
                            'virtual_account' => 'Virtual Account',
                            'e_wallet' => 'E-Wallet',
                            'cash' => 'Tunai',
                        ] as $method => $label)
                            <option value="{{ $method }}" @selected(request('payment_method') === $method)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary w-full min-h-10 text-xs font-semibold">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'payment_method']))
                        <a href="{{ route('admin.payments.index') }}" class="btn-secondary min-h-10 px-3 text-xs" title="Reset">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50/75 text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Transaction ID</th>
                        <th class="px-6 py-4">Kode Order</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4">Jumlah Dana</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Waktu Pembayaran</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-semibold text-slate-700">
                                    {{ $payment->transaction_id ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($payment->order)
                                    <a href="{{ route('admin.orders.show', $payment->order) }}" class="font-mono text-xs font-bold text-brand-950 hover:underline">
                                        {{ $payment->order->order_code }}
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic">Order Diarsipkan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($payment->order && $payment->order->user)
                                    <p class="font-semibold text-slate-900">{{ $payment->order->user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $payment->order->user->email }}</p>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded bg-slate-100 px-2 py-0.5 text-xs font-medium uppercase text-slate-700">
                                    {{ str_replace('_', ' ', $payment->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$payment->status" type="payment" />
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $payment->paid_at ? $payment->paid_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-slate-500">
                                Tidak ada data riwayat pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards View --}}
        <div class="md:hidden space-y-4">
            @forelse($payments as $payment)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
                    <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-3">
                        <div>
                            <span class="font-mono text-xs font-bold text-slate-900">
                                {{ $payment->transaction_id ?? 'Belum ada TX-ID' }}
                            </span>
                            @if($payment->order)
                                <p class="font-mono text-xs text-brand-950 mt-0.5 font-semibold">
                                    Order: {{ $payment->order->order_code }}
                                </p>
                            @endif
                        </div>
                        <x-status-badge :status="$payment->status" type="payment" />
                    </div>

                    @if($payment->order && $payment->order->user)
                        <div class="text-xs">
                            <span class="text-slate-400">Pelanggan</span>
                            <p class="font-semibold text-slate-900">{{ $payment->order->user->name }} ({{ $payment->order->user->email }})</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-3 text-xs">
                        <div>
                            <span class="text-slate-400">Metode</span>
                            <p class="font-bold text-slate-900 uppercase mt-0.5">{{ str_replace('_', ' ', $payment->payment_method) }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400">Jumlah</span>
                            <p class="font-bold text-slate-900 mt-0.5">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1 border-t border-slate-100 text-xs text-slate-500">
                        <span>{{ $payment->paid_at ? $payment->paid_at->translatedFormat('d M Y, H:i') : 'Belum bayar' }}</span>
                        <a href="{{ route('admin.payments.show', $payment) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-semibold text-slate-700 hover:bg-slate-50">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500">
                    Tidak ada data riwayat pembayaran.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    </div>
</x-app-layout>