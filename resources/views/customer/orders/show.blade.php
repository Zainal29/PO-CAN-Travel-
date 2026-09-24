@extends('customer.layouts.index')

@section('title', 'Detail Pesanan ' . $order->order_code . ' — PO CAN Travel')

@section('content')
<div class="space-y-6">
    {{-- Back Link & Header --}}
    <div>
        <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition mb-3">
            &larr; Kembali ke Pesanan
        </a>

        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="eyebrow">Rincian Tiket & Perjalanan</p>
                <div class="flex items-center gap-3 mt-1">
                    <h1 class="page-heading font-mono">
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
                    <a href="{{ route('customer.payments.create', $order) }}" class="btn-primary min-h-10 text-xs font-semibold px-5 shadow-sm">
                        Bayar Sekarang &rarr;
                    </a>
                @elseif($order->order_status === 'paid')
                    <a href="{{ route('customer.orders.ticket', $order) }}" class="inline-flex min-h-10 items-center justify-center rounded-lg bg-amber-400 px-5 text-xs font-bold text-brand-950 hover:bg-amber-300 transition shadow-sm">
                        Lihat E-Ticket &rarr;
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Left Column: 2 Cols --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Route Visualization Card --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900">Jadwal & Rute Perjalanan</h2>
                    <span class="rounded bg-slate-100 px-2 py-0.5 text-xs font-semibold uppercase text-slate-700">
                        {{ $order->route->bus->bus_name }}
                    </span>
                </div>

                {{-- Vertical Route Visualization --}}
                <div class="relative pl-6 sm:pl-8 border-l-2 border-dashed border-amber-400 space-y-8 my-3">
                    {{-- Origin --}}
                    <div class="relative">
                        <span class="absolute -left-[31px] sm:-left-[39px] top-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-brand-950 text-white ring-4 ring-white">
                            <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                        </span>
                        <div>
                            <span class="inline-block font-mono text-xs font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }} WIB
                            </span>
                            <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $order->route->origin_city }}</h3>
                            <p class="text-xs text-slate-500">{{ $order->route->origin_terminal }}</p>
                        </div>
                    </div>

                    {{-- Destination --}}
                    <div class="relative">
                        <span class="absolute -left-[31px] sm:-left-[39px] top-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-white ring-4 ring-white">
                            <span class="h-2 w-2 rounded-full bg-white"></span>
                        </span>
                        <div>
                            <span class="inline-block font-mono text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                {{ \Carbon\Carbon::parse($order->route->estimated_arrival_time)->format('H:i') }} WIB (Estimasi)
                            </span>
                            <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $order->route->destination_city }}</h3>
                            <p class="text-xs text-slate-500">{{ $order->route->destination_terminal }}</p>
                        </div>
                    </div>
                </div>

                {{-- Bus Specs & Info --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 rounded-xl bg-slate-50 p-4 border border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Tanggal Berangkat</span>
                        <p class="mt-1 font-bold text-slate-900">{{ $order->route->departure_date->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Kelas Bus</span>
                        <p class="mt-1 font-bold text-slate-900 capitalize">{{ str_replace('_', ' ', $order->route->bus->bus_type) }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Plat Bus</span>
                        <p class="mt-1 font-mono font-bold text-slate-900">{{ $order->route->bus->plate_number ?: '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Passenger List & Seats --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900">Manifes Penumpang</h2>
                    <span class="text-xs text-slate-500 font-semibold">{{ $order->details->count() }} Penumpang</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($order->details as $index => $detail)
                        <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 font-bold text-xs text-slate-700">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-900">{{ $detail->passenger_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $detail->passenger_phone }} · {{ $detail->passenger_email ?: 'Tanpa email' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 sm:text-right">
                                <span class="rounded-lg bg-amber-50 px-2.5 py-1 font-mono text-xs font-bold text-amber-800 border border-amber-200">
                                    Kursi {{ $detail->seat_number }}
                                </span>
                                <span class="text-xs font-semibold text-slate-900">
                                    Rp {{ number_format($detail->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Review Form if Completed --}}
            @if ($order->order_status === 'completed')
                <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-6 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-slate-900">Review Perjalanan</h2>

                    @if ($order->review)
                        <div class="rounded-xl bg-white p-4 border border-slate-200 space-y-2">
                            <div class="flex items-center gap-1 text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= $order->review->rating ? 'fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                                <span class="ml-2 text-xs font-bold text-slate-700">{{ $order->review->rating }}/5</span>
                            </div>
                            @if ($order->review->comment)
                                <p class="text-xs leading-5 text-slate-600">{{ $order->review->comment }}</p>
                            @endif
                        </div>
                    @else
                        <p class="text-xs text-slate-600">Bagikan pengalaman perjalanan Anda bersama armada PO CAN Travel.</p>

                        <form method="POST" action="{{ route('customer.orders.review', $order) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="rating" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Rating Kepuasan</label>
                                <select id="rating" name="rating" required class="rounded-lg border-slate-300 text-sm focus:border-amber-500 focus:ring-amber-500 sm:w-48">
                                    <option value="">Pilih rating</option>
                                    @for ($rating = 5; $rating >= 1; $rating--)
                                        <option value="{{ $rating }}" @selected(old('rating') == $rating)>{{ $rating }} Bintang</option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="comment" class="block text-xs font-semibold uppercase text-slate-700 mb-1">Ulasan Pengalaman</label>
                                <textarea id="comment" name="comment" rows="3" maxlength="2000" class="w-full rounded-lg border-slate-300 text-sm focus:border-amber-500 focus:ring-amber-500" placeholder="Ceritakan kenyamanan bus, ketepatan waktu, dan pelayanan kru...">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="btn-primary min-h-10 text-xs font-semibold px-5">
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
                    <p class="text-xs text-slate-600">
                        Jika Anda membatalkan pesanan ini, kursi yang telah dipilih akan dilepaskan kembali ke jadwal perjalanan.
                    </p>

                    <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" class="space-y-3 pt-1">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="cancellation_note" class="block text-xs font-semibold uppercase text-slate-700 mb-1">
                                Alasan Pembatalan
                            </label>
                            <textarea
                                id="cancellation_note"
                                name="cancellation_note"
                                rows="2"
                                required
                                minlength="5"
                                maxlength="1000"
                                placeholder="Jelaskan alasan pembatalan..."
                                class="w-full rounded-lg border-slate-300 text-xs focus:border-red-500 focus:ring-red-500"
                            >{{ old('cancellation_note') }}</textarea>
                            @error('cancellation_note')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')"
                            class="rounded-lg border border-red-300 bg-white px-4 py-2 text-xs font-semibold text-red-700 hover:bg-red-50 transition"
                        >
                            Batalkan Pesanan
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Right Column: 1 Col --}}
        <div class="space-y-6">
            {{-- Payment Breakdown Card --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-900">Rincian Pembayaran</h2>
                    <x-status-badge :status="$order->payment?->status ?? 'unpaid'" type="payment" />
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Jumlah Tiket</span>
                        <span class="font-semibold text-slate-900">{{ $order->total_passengers }} Orang</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tarif per Kursi</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($order->route->price, 0, ',', '.') }}</span>
                    </div>

                    <div class="border-t border-slate-100 pt-3 flex justify-between items-baseline">
                        <span class="text-sm font-bold text-slate-900">Total Tagihan</span>
                        <span class="text-xl font-black text-brand-950">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Status specific banners --}}
                @if($order->order_status === 'pending')
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs space-y-2">
                        <p class="font-bold text-amber-900">Menunggu Pembayaran</p>
                        <p class="text-amber-800 leading-relaxed">
                            Batas waktu pembayaran berlaku hingga {{ $order->expired_at ? $order->expired_at->format('H:i, d M Y') : 'segera' }}.
                        </p>
                        <a href="{{ route('customer.payments.create', $order) }}" class="btn-primary w-full min-h-10 text-xs font-semibold mt-2">
                            Bayar Sekarang &rarr;
                        </a>
                    </div>
                @elseif($order->order_status === 'paid')
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs space-y-2">
                        <p class="font-bold text-emerald-900">Pembayaran Terverifikasi</p>
                        <p class="text-emerald-800 leading-relaxed">
                            E-ticket resmi Anda telah diterbitkan dan siap digunakan saat boarding.
                        </p>
                        <a href="{{ route('customer.orders.ticket', $order) }}" class="btn-primary w-full min-h-10 text-xs font-semibold mt-2">
                            Buka E-Ticket
                        </a>
                    </div>
                @elseif($order->order_status === 'completed')
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-600">
                        <p class="font-bold text-slate-900">Perjalanan telah selesai</p>
                        <p class="mt-1">Terima kasih telah mempercayakan perjalanan Anda bersama PO CAN Travel.</p>
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
                            <span class="font-semibold text-slate-800 uppercase">{{ str_replace('_', ' ', $order->payment->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">ID Transaksi</span>
                            <span class="font-mono font-semibold text-slate-800">{{ $order->payment->transaction_id ?: '-' }}</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Boarding Information Card --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 text-xs text-slate-600 space-y-2 shadow-sm">
                <h3 class="font-bold text-slate-900">Informasi Keberangkatan</h3>
                <p class="leading-relaxed">
                    Harap tiba di terminal keberangkatan minimal <strong>30 menit</strong> sebelum jadwal berangkat bus.
                </p>
                <p class="leading-relaxed">
                    Tunjukkan e-ticket atau QR code pada petugas loket PO CAN Travel untuk proses check-in.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
