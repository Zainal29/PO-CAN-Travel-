<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke daftar pesanan
                </a>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold font-mono tracking-tight text-slate-900">
                        Order {{ $order->order_code }}
                    </h1>
                    <x-status-badge :status="$order->order_status" type="order" />
                    <x-status-badge :status="$order->payment_status" type="payment" />
                    @if($order->checked_in_at)
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-800 border border-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                            Check-in Terverifikasi
                        </span>
                    @endif
                </div>
                <p class="mt-1 text-xs text-slate-500">
                    Dipesan pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB · Tiket: <span class="font-mono font-bold text-slate-700">{{ $order->ticket_code ?: 'Belum terbit' }}</span>
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.orders.show', $order) }}" 
                   title="Segarkan status jadwal otomatis"
                   class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition shadow-xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Sinkronkan Status</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-7 sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-emerald-800 flex items-center gap-2.5 shadow-xs">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-xs font-bold text-red-800 flex items-center gap-2.5 shadow-xs">
                <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Real-time Countdown Timer & Operasional Status --}}
        @if($order->order_status === 'paid' || $order->order_status === 'completed')
            <div class="space-y-2">
                <x-trip-countdown :order="$order" :context="$scheduleContext ?? null" />
                <div class="flex items-center justify-between px-1 text-[11px] text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        <strong>Sistem Otomatisasi:</strong> Tiket akan otomatis ter-check-in saat waktu keberangkatan tiba, dan otomatis selesai saat bus sampai di terminal tujuan.
                    </span>
                    <span>WIB (Asia/Jakarta)</span>
                </div>
            </div>
        @endif

        {{-- Route & Trip Context Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Rincian Rute & Armada Bus</h2>
                    <p class="text-xs text-slate-500">Jadwal keberangkatan dan estimasi kedatangan bus.</p>
                </div>
                <span class="rounded-lg bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 border border-blue-200/60 uppercase tracking-wide">
                    {{ $order->route->bus->bus_name }}
                </span>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100 space-y-2.5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rute Perjalanan</p>
                    <div class="flex items-center gap-2 text-base font-extrabold text-slate-900">
                        <span>{{ $order->route->origin_city }}</span>
                        <span class="text-blue-600 font-normal">&rarr;</span>
                        <span>{{ $order->route->destination_city }}</span>
                    </div>
                    <div class="text-xs text-slate-600 space-y-1 pt-1 border-t border-slate-200/60">
                        <p><strong class="text-slate-800">Naik:</strong> {{ $order->route->origin_terminal }}</p>
                        <p><strong class="text-slate-800">Turun:</strong> {{ $order->route->destination_terminal }}</p>
                    </div>
                </div>

                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100 space-y-2.5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Waktu & Spesifikasi Armada</p>
                    <div class="flex items-baseline gap-2">
                        <span class="font-mono text-base font-extrabold text-slate-900">
                            {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }} WIB
                        </span>
                        <span class="text-slate-400 text-xs">s/d</span>
                        <span class="font-mono text-sm font-bold text-emerald-700">
                            {{ \Carbon\Carbon::parse($order->route->estimated_arrival_time)->format('H:i') }} WIB
                        </span>
                    </div>
                    <div class="text-xs text-slate-600 space-y-1 pt-1 border-t border-slate-200/60">
                        <p><strong class="text-slate-800">Tanggal:</strong> {{ $order->route->departure_date->translatedFormat('l, d F Y') }}</p>
                        <p><strong class="text-slate-800">Armada:</strong> {{ $order->route->bus->bus_name }} ({{ $order->route->bus->plate_number ?: '-' }}) · <span class="capitalize">{{ str_replace('_', ' ', $order->route->bus->bus_type) }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Customer & Payment Summary --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Data Pemesan & Transaksi</h2>
                    <p class="text-xs text-slate-500">Informasi akun pengguna dan verifikasi pembayaran.</p>
                </div>
                <x-status-badge :status="$order->payment?->status ?? 'unpaid'" type="payment" />
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 font-semibold uppercase text-[11px]">Nama Pemesan</span>
                    <p class="font-bold text-sm text-slate-900 mt-1">{{ $order->user->name }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase text-[11px]">Email</span>
                    <p class="font-bold text-sm text-slate-900 mt-1 truncate" title="{{ $order->user->email }}">{{ $order->user->email }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase text-[11px]">Jumlah Penumpang</span>
                    <p class="font-bold text-sm text-slate-900 mt-1">{{ $order->total_passengers }} Orang</p>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase text-[11px]">Total Tagihan</span>
                    <p class="font-extrabold text-sm font-mono text-blue-600 mt-1">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($order->payment)
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold uppercase text-[11px]">Metode Pembayaran</span>
                        <p class="font-bold text-slate-900 mt-1 uppercase">{{ str_replace('_', ' ', $order->payment->payment_method) }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase text-[11px]">Status Bayar</span>
                        <p class="font-bold text-emerald-700 mt-1 capitalize">{{ $order->payment->status }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase text-[11px]">ID Transaksi</span>
                        <p class="font-mono text-slate-900 mt-1 font-semibold">{{ $order->payment->transaction_id ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase text-[11px]">Status Kursi</span>
                        <p class="font-semibold mt-1 {{ $order->seats_released ? 'text-red-700' : 'text-emerald-700' }}">
                            {{ $order->seats_released ? 'Sudah dilepas' : 'Terkunci aktif' }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Passenger Manifest --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Manifes Penumpang & Kursi</h2>
                    <p class="text-xs text-slate-500">Daftar nama tiket dan alokasi kursi penumpang.</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-700 font-bold">
                    {{ $order->details->count() }} Kursi
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Nama Penumpang</th>
                            <th class="px-6 py-3.5">Nomor Telepon</th>
                            <th class="px-6 py-3.5">Email</th>
                            <th class="px-6 py-3.5 text-center">Nomor Kursi</th>
                            <th class="px-6 py-3.5 text-right">Tarif</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($order->details as $detail)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $detail->passenger_name }}</td>
                                <td class="px-6 py-4 text-slate-600 font-mono text-xs">{{ $detail->passenger_phone }}</td>
                                <td class="px-6 py-4 text-slate-600 text-xs">{{ $detail->passenger_email ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-1 font-mono text-xs font-bold text-blue-700 border border-blue-200">
                                        Kursi {{ $detail->seat_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-slate-900">
                                    Rp {{ number_format($detail->price, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Management & Operational Actions Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Tindakan Operasional & Validasi Check-In</h3>
                    <p class="text-xs text-slate-500">Kelola status keberangkatan, check-in tiket, atau penyelesaian perjalanan.</p>
                </div>
            </div>

            {{-- Check-in Status Banner --}}
            @if ($order->checked_in_at)
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4 sm:p-5 flex items-start gap-3.5 text-xs text-emerald-900">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-xs">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <p class="font-bold text-sm text-emerald-950">Penumpang Telah Ter-Check-In & Tervalidasi</p>
                        <p class="text-emerald-800 leading-relaxed">
                            Check-in tercatat pada <strong>{{ $order->checked_in_at->translatedFormat('d F Y, H:i') }} WIB</strong>. Penumpang resmi terdaftar dalam manifes keberangkatan bus.
                        </p>
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-blue-200 bg-blue-50/70 p-4 sm:p-5 flex items-start gap-3.5 text-xs text-blue-900">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-xs">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <p class="font-bold text-sm text-blue-950">Menunggu Waktu Keberangkatan</p>
                        <p class="text-blue-800 leading-relaxed">
                            Status check-in akan <strong>otomatis aktif</strong> saat waktu keberangkatan tiba. Admin juga dapat melakukan check-in manual atau scan QR jika penumpang hadir lebih awal di loket.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Operational Action Buttons --}}
            <div class="flex flex-wrap items-center gap-3 pt-2">
                {{-- Complete Trip Button --}}
                @if($order->order_status === 'paid')
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="order_status" value="completed">
                        <button type="submit" 
                                onclick="return confirm('Tandai perjalanan ini telah selesai dan tiba di tujuan?')"
                                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-blue-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Tandai Perjalanan Selesai</span>
                        </button>
                    </form>
                @endif

                {{-- Manual Check-in if not yet checked in --}}
                @if ($order->payment?->status === 'verified' && $order->order_status === 'paid' && ! $order->checked_in_at)
                    <form method="POST" action="{{ route('admin.orders.check-in', $order) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Catat Check-in Langsung</span>
                        </button>
                    </form>
                @endif

                {{-- Release Seats --}}
                @if(in_array($order->order_status, ['cancelled', 'expired'], true) && ! $order->seats_released)
                    <form method="POST" action="{{ route('admin.orders.release-seats', $order) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-amber-700 transition shadow-xs"
                            onclick="return confirm('Lepaskan kursi dari order ini?')"
                        >
                            <span>Lepaskan Kursi</span>
                        </button>
                    </form>
                @endif

                {{-- Archive Order --}}
                @if(in_array($order->order_status, ['cancelled', 'expired', 'completed'], true))
                    <form method="POST" action="{{ route('admin.orders.archive', $order) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-xs"
                            onclick="return confirm('Arsipkan pesanan ini?')"
                        >
                            <span>Arsipkan Order</span>
                        </button>
                    </form>
                @endif
            </div>

            {{-- QR Scanner Accordion/Box --}}
            @if ($order->payment?->status === 'verified' && $order->order_status === 'paid' && ! $order->checked_in_at)
                <div class="pt-4 border-t border-slate-100">
                    <details class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                        <summary class="cursor-pointer font-bold text-xs text-slate-700 hover:text-blue-600 transition select-none flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span>Pindai / Validasi QR E-Ticket Tiket</span>
                            </span>
                            <span class="text-xs text-slate-400">Klik untuk buka scanner</span>
                        </summary>

                        <form method="POST" action="{{ route('admin.orders.qr.check-in') }}" class="mt-4 space-y-3">
                            @csrf
                            @method('PATCH')
                            <label for="qr_payload" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Hasil Scan QR E-Ticket</label>
                            <textarea id="qr_payload" name="qr_payload" rows="3" required maxlength="5000" class="w-full rounded-xl border-slate-300 text-xs focus:border-blue-600 focus:ring-blue-600 font-mono shadow-xs" placeholder="Tempel hasil scan QR e-ticket di sini..."></textarea>
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white hover:bg-blue-700 transition shadow-xs">
                                Validasi QR dan Check-in
                            </button>
                        </form>
                    </details>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
