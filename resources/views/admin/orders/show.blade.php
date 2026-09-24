<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition mb-2">
                    &larr; Kembali ke daftar pesanan
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        Order {{ $order->order_code }}
                    </h1>
                    <x-status-badge :status="$order->order_status" type="order" />
                    <x-status-badge :status="$order->payment_status" type="payment" />
                </div>
                <p class="mt-1 text-sm text-slate-500">
                    Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
                </p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-7 sm:px-6 lg:px-8 space-y-6">
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

        {{-- Route & Trip Context Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Rincian Perjalanan</h2>
                    <p class="text-xs text-slate-500">Rute, armada bus, dan jadwal keberangkatan.</p>
                </div>
                <span class="rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-800 border border-amber-200">
                    {{ $order->route->bus->bus_name }}
                </span>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100 space-y-3">
                    <p class="text-xs font-semibold uppercase text-slate-400">Rute Perjalanan</p>
                    <div class="flex items-center gap-2 text-base font-bold text-slate-900">
                        <span>{{ $order->route->origin_city }}</span>
                        <span class="text-amber-500">&rarr;</span>
                        <span>{{ $order->route->destination_city }}</span>
                    </div>
                    <p class="text-xs text-slate-500">
                        {{ $order->route->origin_terminal }} &rarr; {{ $order->route->destination_terminal }}
                    </p>
                </div>

                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100 space-y-3">
                    <p class="text-xs font-semibold uppercase text-slate-400">Waktu & Tanggal</p>
                    <p class="text-base font-bold text-slate-900">
                        {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }} WIB · {{ $order->route->departure_date->translatedFormat('d F Y') }}
                    </p>
                    <p class="text-xs text-slate-500">
                        Bus {{ $order->route->bus->bus_name }} ({{ $order->route->bus->plate_number ?: '-' }})
                    </p>
                </div>
            </div>
        </div>

        {{-- Customer & Order Summary --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900">Data Pemesan & Pembayaran</h2>
                <p class="text-xs text-slate-500">Informasi akun pengguna dan status transaksi.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 font-semibold uppercase">Nama Pemesan</span>
                    <p class="font-bold text-sm text-slate-900 mt-1">{{ $order->user->name }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase">Email</span>
                    <p class="font-bold text-sm text-slate-900 mt-1">{{ $order->user->email }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase">Total Penumpang</span>
                    <p class="font-bold text-sm text-slate-900 mt-1">{{ $order->total_passengers }} Orang</p>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase">Total Tagihan</span>
                    <p class="font-bold text-sm text-brand-950 mt-1">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($order->payment)
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Metode Pembayaran</span>
                        <p class="font-semibold text-slate-900 mt-1 capitalize">{{ str_replace('_', ' ', $order->payment->payment_method) }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Status Bayar</span>
                        <div class="mt-1">
                            <x-status-badge :status="$order->payment->status" type="payment" />
                        </div>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">ID Transaksi</span>
                        <p class="font-mono text-slate-900 mt-1 font-semibold">{{ $order->payment->transaction_id ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Status Kursi</span>
                        <p class="font-semibold mt-1 {{ $order->seats_released ? 'text-emerald-700' : 'text-slate-900' }}">
                            {{ $order->seats_released ? 'Sudah dilepas' : 'Terkunci aktif' }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Passenger Manifest --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900">Manifes Penumpang & Kursi</h2>
                <p class="text-xs text-slate-500">Daftar penumpang tiket dan nomor kursi yang dipilih.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Nama Penumpang</th>
                            <th class="px-6 py-3.5">Nomor Telepon</th>
                            <th class="px-6 py-3.5">Email</th>
                            <th class="px-6 py-3.5">Nomor Kursi</th>
                            <th class="px-6 py-3.5 text-right">Tarif</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($order->details as $detail)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $detail->passenger_name }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $detail->passenger_phone }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $detail->passenger_email ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-lg bg-amber-50 px-2.5 py-1 font-mono text-xs font-bold text-amber-800 border border-amber-200">
                                        Kursi {{ $detail->seat_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-slate-900">
                                    Rp {{ number_format($detail->price, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Management Actions Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-slate-900">Tindakan Operasional Order</h3>

            <div class="flex flex-wrap items-center gap-3">
                @if($order->order_status === 'paid')
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="order_status" value="completed">
                        <button type="submit" class="btn-primary min-h-10 text-xs font-semibold">
                            Tandai Perjalanan Selesai
                        </button>
                    </form>
                @endif

                @if(in_array($order->order_status, ['cancelled', 'expired'], true) && ! $order->seats_released)
                    <form method="POST" action="{{ route('admin.orders.release-seats', $order) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button
                            type="submit"
                            class="rounded-lg bg-amber-600 px-4 py-2.5 text-xs font-semibold text-white hover:bg-amber-700 transition"
                            onclick="return confirm('Lepaskan kursi dari order ini?')"
                        >
                            Lepaskan Kursi
                        </button>
                    </form>
                @endif

                @if(in_array($order->order_status, ['cancelled', 'expired', 'completed'], true))
                    <form method="POST" action="{{ route('admin.orders.archive', $order) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
                            onclick="return confirm('Arsipkan pesanan ini?')"
                        >
                            Arsipkan Order
                        </button>
                    </form>
                @endif
            </div>

            {{-- QR Check-in Box --}}
            @if ($order->payment?->status === 'verified' && $order->order_status === 'paid' && ! $order->checked_in_at)
                <div class="pt-4 border-t border-slate-100 space-y-4">
                    <form method="POST" action="{{ route('admin.orders.check-in', $order) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                            Catat Check-in Langsung
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.orders.qr.check-in') }}" class="rounded-xl border border-emerald-200 bg-emerald-50/75 p-5 space-y-3">
                        @csrf
                        @method('PATCH')
                        <label for="qr_payload" class="block text-sm font-bold text-emerald-950">Validasi QR e-ticket</label>
                        <textarea id="qr_payload" name="qr_payload" rows="3" required maxlength="5000" class="w-full rounded-lg border-emerald-300 text-xs focus:border-emerald-600 focus:ring-emerald-600 font-mono" placeholder="Tempel hasil scan QR e-ticket di sini..."></textarea>
                        <button type="submit" class="rounded-lg bg-emerald-700 px-4 py-2.5 text-xs font-semibold text-white hover:bg-emerald-800 transition">
                            Validasi QR dan Check-in
                        </button>
                    </form>
                </div>
            @elseif ($order->checked_in_at)
                <div class="pt-4 border-t border-slate-100 flex items-center gap-2 text-xs font-semibold text-emerald-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Sudah check-in: {{ $order->checked_in_at->format('d M Y H:i') }} WIB
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
