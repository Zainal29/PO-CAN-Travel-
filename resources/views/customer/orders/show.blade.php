@extends('customer.layouts.index')

@section('title', 'Detail Pesanan ' . $order->order_code . ' — PO CAN Travel')

@section('content')
<div class="space-y-6">
    {{-- Back Link & Header --}}
    <div>
        <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Riwayat Pesanan
        </a>

        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Rincian Tiket & Perjalanan</p>
                <div class="flex flex-wrap items-center gap-3 mt-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold font-mono tracking-tight text-slate-900">
                        {{ $order->order_code }}
                    </h1>
                    <x-status-badge :status="$order->order_status" type="order" />
                </div>
                <p class="mt-1 text-xs text-slate-500">
                    Dipesan pada {{ $order->created_at->translatedFormat('l, d F Y, H:i') }} WIB
                </p>
            </div>

            <div class="flex items-center gap-2">
                @if($order->order_status === 'pending')
                    <a href="{{ route('customer.payments.create', $order) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-blue-700 transition">
                        <span>Lanjut ke Pembayaran</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                @elseif($order->order_status === 'paid' || $order->order_status === 'completed')
                    <a href="{{ route('customer.orders.ticket', $order) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                        <span>Buka E-Ticket Resmi</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Live Countdown Timer --}}
    @if($order->order_status === 'paid' || $order->order_status === 'completed')
        <x-trip-countdown :order="$order" />
    @endif

    {{-- Main Grid --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Left Column (2 Cols) --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Route Visualization Card --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $order->route->bus->image_url }}" 
                             alt="{{ $order->route->bus->bus_name }}" 
                             class="h-12 w-16 rounded-xl object-cover border border-slate-200 shadow-xs shrink-0"
                             onerror="this.src='{{ asset('images/hero-bus.jpg') }}'">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Jadwal & Rute Armada</h2>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $order->route->bus->bus_name }} ({{ $order->route->bus->bus_code }})</p>
                        </div>
                    </div>
                    <span class="rounded-lg bg-blue-50 border border-blue-200/60 px-3 py-1 text-xs font-bold text-blue-700 uppercase tracking-wide">
                        {{ str_replace('_', ' ', $order->route->bus->bus_type) }}
                    </span>
                </div>

                {{-- Vertical Route Visual Timeline --}}
                <div class="relative pl-6 sm:pl-8 border-l-2 border-dashed border-blue-300 space-y-8 my-4 ml-3">
                    {{-- Origin Departure --}}
                    <div class="relative">
                        <span class="absolute -left-[31px] sm:-left-[39px] top-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-white ring-4 ring-blue-50">
                            <span class="h-2 w-2 rounded-full bg-white"></span>
                        </span>
                        <div>
                            <span class="inline-block font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-md border border-blue-200">
                                {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }} WIB · Keberangkatan
                            </span>
                            <h3 class="mt-1.5 text-lg font-extrabold text-slate-900">{{ $order->route->origin_city }}</h3>
                            <p class="text-xs text-slate-600 mt-0.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $order->route->origin_terminal }}</span>
                            </p>
                        </div>
                    </div>

                    {{-- Destination Arrival --}}
                    <div class="relative">
                        <span class="absolute -left-[31px] sm:-left-[39px] top-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-white ring-4 ring-emerald-50">
                            <span class="h-2 w-2 rounded-full bg-white"></span>
                        </span>
                        <div>
                            <span class="inline-block font-mono text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-200">
                                {{ \Carbon\Carbon::parse($order->route->estimated_arrival_time)->format('H:i') }} WIB (Estimasi Tiba)
                            </span>
                            <h3 class="mt-1.5 text-lg font-extrabold text-slate-900">{{ $order->route->destination_city }}</h3>
                            <p class="text-xs text-slate-600 mt-0.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $order->route->destination_terminal }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Bus Specs & Info --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 rounded-xl bg-slate-50 p-4 border border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-500 font-semibold uppercase text-[11px]">Tanggal Berangkat</span>
                        <p class="mt-1 font-bold text-slate-900">{{ $order->route->departure_date->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 font-semibold uppercase text-[11px]">Kelas Bus</span>
                        <p class="mt-1 font-bold text-slate-900 capitalize">{{ str_replace('_', ' ', $order->route->bus->bus_type) }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 font-semibold uppercase text-[11px]">Nomor Polisi</span>
                        <p class="mt-1 font-mono font-bold text-slate-900">{{ $order->route->bus->plate_number ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 font-semibold uppercase text-[11px]">Total Kapasitas</span>
                        <p class="mt-1 font-bold text-slate-900">{{ $order->route->bus->total_seats }} Kursi</p>
                    </div>
                </div>
            </div>

            {{-- Passenger Manifest & Seats --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Manifes Penumpang & Kursi</h2>
                        <p class="text-xs text-slate-500">Daftar penumpang resmi pada tiket perjalanan ini</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-700 font-bold">
                        {{ $order->details->count() }} Penumpang
                    </span>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($order->details as $index => $detail)
                        <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3.5">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 font-bold text-xs text-blue-700 border border-blue-100">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-900">{{ $detail->passenger_name }}</p>
                                    <div class="flex flex-wrap items-center gap-2 mt-0.5 text-xs text-slate-500">
                                        <span>{{ $detail->passenger_phone }}</span>
                                        @if($detail->passenger_email)
                                            <span>&bull;</span>
                                            <span>{{ $detail->passenger_email }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-4 pl-12 sm:pl-0">
                                <span class="rounded-lg bg-blue-50 px-3 py-1 font-mono text-xs font-bold text-blue-800 border border-blue-200">
                                    Kursi {{ $detail->seat_number }}
                                </span>
                                <span class="text-xs font-bold text-slate-900 font-mono">
                                    Rp {{ number_format($detail->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Review Form if Completed --}}
            @if ($order->order_status === 'completed')
                <div class="rounded-2xl border border-blue-200/80 bg-blue-50/40 p-6 shadow-sm space-y-4">
                    <div class="border-b border-blue-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900">Ulasan & Rating Perjalanan</h2>
                        <p class="text-xs text-slate-600 mt-0.5">Penilaian Anda membantu kami terus meningkatkan kualitas pelayanan armada.</p>
                    </div>

                    @if ($order->review)
                        <div class="rounded-xl bg-white p-5 border border-slate-200 space-y-2">
                            <div class="flex items-center gap-1.5 text-amber-500">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= $order->review->rating ? 'fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-xs font-bold text-slate-800">{{ $order->review->rating }} dari 5 Bintang</span>
                            </div>
                            @if ($order->review->comment)
                                <p class="text-xs leading-relaxed text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100">
                                    "{{ $order->review->comment }}"
                                </p>
                            @endif
                        </div>
                    @else
                        <form method="POST" action="{{ route('customer.orders.review', $order) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="rating" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Rating Kepuasan</label>
                                <select id="rating" name="rating" required class="rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 sm:w-48 shadow-sm">
                                    <option value="">Pilih rating</option>
                                    @for ($rating = 5; $rating >= 1; $rating--)
                                        <option value="{{ $rating }}" @selected(old('rating') == $rating)>
                                            {{ $rating }} Bintang {{ $rating === 5 ? '— Sangat Puas' : ($rating === 4 ? '— Puas' : '') }}
                                        </option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="comment" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Ulasan Pengalaman</label>
                                <textarea id="comment" name="comment" rows="3" maxlength="2000" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm" placeholder="Ceritakan kenyamanan bus, ketepatan waktu armada, dan keramahan kru...">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-blue-700 transition">
                                Kirim Ulasan
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- Cancellation Form if Pending --}}
            @if ($order->order_status === 'pending')
                <div class="rounded-2xl border border-red-200 bg-red-50/40 p-6 shadow-sm space-y-3">
                    <h2 class="text-base font-bold text-red-950">Batalkan Pesanan</h2>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Jika Anda membatalkan pesanan ini, kursi yang telah dipilih akan dilepaskan kembali ke jadwal perjalanan untuk pemesan lain.
                    </p>

                    <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" class="space-y-3 pt-1">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="cancellation_note" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Alasan Pembatalan
                            </label>
                            <textarea
                                id="cancellation_note"
                                name="cancellation_note"
                                rows="2"
                                required
                                minlength="5"
                                maxlength="1000"
                                placeholder="Jelaskan alasan pembatalan pesanan tiket ini..."
                                class="w-full rounded-xl border-slate-300 text-xs focus:border-red-500 focus:ring-red-500 shadow-sm"
                            >{{ old('cancellation_note') }}</textarea>
                            @error('cancellation_note')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')"
                            class="inline-flex items-center justify-center rounded-xl border border-red-300 bg-white px-4 py-2 text-xs font-bold text-red-700 hover:bg-red-50 hover:border-red-400 transition shadow-sm"
                        >
                            Batalkan Pesanan Ini
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Right Column (1 Col) --}}
        <div class="space-y-6">
            {{-- Payment Breakdown Card --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-900">Rincian Pembayaran</h2>
                    <x-status-badge :status="$order->payment?->status ?? 'unpaid'" type="payment" />
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Jumlah Penumpang</span>
                        <span class="font-bold text-slate-900">{{ $order->total_passengers }} Orang</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tarif per Kursi</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($order->route->price, 0, ',', '.') }}</span>
                    </div>

                    <div class="border-t border-slate-100 pt-3 flex justify-between items-baseline">
                        <span class="text-sm font-bold text-slate-900">Total Tagihan</span>
                        <span class="text-xl font-extrabold text-blue-600 font-mono">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Status specific banners --}}
                @if($order->order_status === 'pending')
                    <div class="rounded-xl border border-amber-200 bg-amber-50/80 p-4 text-xs space-y-2">
                        <div class="flex items-center gap-1.5 font-bold text-amber-900">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Menunggu Pembayaran</span>
                        </div>
                        <p class="text-amber-800 leading-relaxed">
                            Batas waktu pembayaran berlaku hingga {{ $order->expired_at ? $order->expired_at->format('H:i, d M Y') : 'segera' }}.
                        </p>
                        <a href="{{ route('customer.payments.create', $order) }}" class="inline-flex items-center justify-center w-full rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-blue-700 transition mt-2">
                            Bayar Sekarang &rarr;
                        </a>
                    </div>
                @elseif($order->order_status === 'paid')
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50/80 p-4 text-xs space-y-2">
                        <div class="flex items-center gap-1.5 font-bold text-emerald-900">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Pembayaran Terverifikasi</span>
                        </div>
                        <p class="text-emerald-800 leading-relaxed">
                            E-ticket resmi Anda telah aktif dan siap digunakan untuk proses check-in saat boarding.
                        </p>
                        <a href="{{ route('customer.orders.ticket', $order) }}" class="inline-flex items-center justify-center w-full rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-blue-700 transition mt-2">
                            Buka E-Ticket Resmi
                        </a>
                    </div>
                @elseif($order->order_status === 'completed')
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-600">
                        <p class="font-bold text-slate-900">Perjalanan Telah Selesai</p>
                        <p class="mt-1">Terima kasih telah mempercayakan perjalanan Anda bersama armada PO CAN Travel.</p>
                    </div>
                @elseif($order->order_status === 'cancelled')
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-xs text-red-700">
                        <p class="font-bold text-red-900">Pesanan Dibatalkan</p>
                        <p class="mt-1">Pesanan tiket perjalanan ini telah dibatalkan.</p>
                    </div>
                @endif

                @if($order->payment)
                    <div class="border-t border-slate-100 pt-3 text-xs space-y-2">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Metode</span>
                            <span class="font-bold text-slate-800 uppercase">{{ str_replace('_', ' ', $order->payment->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">ID Transaksi</span>
                            <span class="font-mono font-semibold text-slate-800">{{ $order->payment->transaction_id ?: '-' }}</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Boarding Information Card --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 text-xs text-slate-600 space-y-3 shadow-sm">
                <div class="flex items-center gap-2 text-slate-900 font-bold">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8V5m0 14a9 9 0 110-18 9 9 0 0118 0z"/></svg>
                    <span>Informasi Keberangkatan</span>
                </div>
                <p class="leading-relaxed">
                    Harap tiba di terminal keberangkatan minimal <strong>30 menit</strong> sebelum jadwal keberangkatan bus.
                </p>
                <p class="leading-relaxed">
                    Tunjukkan e-ticket atau QR code resmi pada petugas loket PO CAN Travel untuk pencetakan boarding pass.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
