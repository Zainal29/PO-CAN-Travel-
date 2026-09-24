<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600">Transaksi & Tiket</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Manajemen Pesanan Tiket</h1>
                <p class="mt-1 text-sm text-slate-500">Pantau seluruh pemesanan tiket perjalanan, status pembayaran, dan manifes penumpang.</p>
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
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Cari Pesanan</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Kode order / nama pemesan..."
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Status Order</label>
                    <select name="status" class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="">Semua Status Order</option>
                        @foreach(['pending' => 'Pending (Menunggu)', 'paid' => 'Paid (Dibayar)', 'completed' => 'Completed (Selesai)', 'cancelled' => 'Cancelled (Batal)', 'expired' => 'Expired (Kedaluwarsa)'] as $status => $label)
                            <option value="{{ $status }}" @selected(request('status') === $status)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Status Bayar</label>
                    <select name="payment_status" class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="">Semua Status Bayar</option>
                        @foreach(['unpaid' => 'Unpaid (Belum Bayar)', 'pending' => 'Pending Verifikasi', 'verified' => 'Verified (Lunas)', 'rejected' => 'Rejected (Ditolak)'] as $status => $label)
                            <option value="{{ $status }}" @selected(request('payment_status') === $status)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary w-full min-h-10 text-xs font-semibold">
                        Terapkan Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'payment_status']))
                        <a href="{{ route('admin.orders.index') }}" class="btn-secondary min-h-10 px-3 text-xs" title="Reset">
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
                        <th class="px-6 py-4">Kode Order</th>
                        <th class="px-6 py-4">Pemesan</th>
                        <th class="px-6 py-4">Rute Perjalanan</th>
                        <th class="px-6 py-4">Penumpang</th>
                        <th class="px-6 py-4">Total Tarif</th>
                        <th class="px-6 py-4">Pembayaran</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-mono text-xs font-bold text-brand-950 hover:underline">
                                    {{ $order->order_code }}
                                </a>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900">{{ $order->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $order->user->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900 flex items-center gap-1.5 text-xs">
                                    <span>{{ $order->route->origin_city }}</span>
                                    <span class="text-amber-500">&rarr;</span>
                                    <span>{{ $order->route->destination_city }}</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $order->route->departure_date->format('d M Y') }} · {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-900">{{ $order->total_passengers }} org</span>
                                @if($order->details->isNotEmpty())
                                    <p class="text-[11px] text-slate-500">Kursi: {{ $order->details->pluck('seat_number')->join(', ') }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$order->payment_status" type="payment" />
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$order->order_status" type="order" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-slate-500">
                                Tidak ada data pesanan tiket yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards View --}}
        <div class="md:hidden space-y-4">
            @forelse($orders as $order)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
                    <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-3">
                        <div>
                            <span class="font-mono text-xs font-bold text-brand-950">{{ $order->order_code }}</span>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <x-status-badge :status="$order->order_status" type="order" />
                            <x-status-badge :status="$order->payment_status" type="payment" />
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-slate-400">Pemesan</p>
                        <p class="text-sm font-bold text-slate-900">{{ $order->user->name }}</p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-3 text-xs space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-slate-900">
                            <span>{{ $order->route->origin_city }}</span>
                            <span class="text-amber-500">&rarr;</span>
                            <span>{{ $order->route->destination_city }}</span>
                        </div>
                        <p class="text-slate-500">
                            {{ $order->route->departure_date->format('d M Y') }} · {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }} WIB
                        </p>
                        <p class="text-slate-500">
                            {{ $order->total_passengers }} penumpang · Kursi: {{ $order->details->pluck('seat_number')->join(', ') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400">Total Tarif</span>
                            <p class="text-base font-bold text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('admin.orders.show', $order) }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Detail Order
                        </a>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500">
                    Tidak ada data pesanan tiket yang ditemukan.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>