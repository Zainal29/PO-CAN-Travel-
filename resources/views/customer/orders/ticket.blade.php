@extends('customer.layouts.index')

@section('title', 'E-Ticket ' . $order->ticket_code . ' — PO CAN Travel')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    {{-- Top Action Bar (Hidden on Print) --}}
    <div class="no-print flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('customer.orders.show', $order) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-blue-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Detail Pesanan
        </a>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('customer.orders.ticket.qr', $order) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition shadow-sm">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh Gambar QR
            </a>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white hover:bg-blue-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak E-Ticket
            </button>
        </div>
    </div>

    {{-- Live Real-time Countdown Banner (No Print) --}}
    <div class="no-print">
        <x-trip-countdown :order="$order" />
    </div>

    {{-- Physical-style Ticket Container --}}
    <article class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-md">
        {{-- Blue Top Header Band --}}
        <div class="bg-blue-600 px-6 sm:px-8 py-5 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 backdrop-blur border border-white/20">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 16c0 .88.39 1.67 1 2.22V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h8v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm9 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm-11-7h13V6.5c0-.83-.67-1.5-1.5-1.5h-10c-.83 0-1.5.67-1.5 1.5V10z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold tracking-tight text-lg text-white">PO CAN Travel</span>
                            <span class="rounded bg-white/20 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white">Boarding Pass</span>
                        </div>
                        <p class="text-xs text-blue-100">Layanan Angkutan Antarkota & Travel Resmi</p>
                    </div>
                </div>

                <div class="sm:text-right">
                    <span class="text-[11px] font-semibold text-blue-200 uppercase tracking-wider">Nomor E-Ticket</span>
                    <p class="font-mono text-xl sm:text-2xl font-black tracking-wider text-white">{{ $order->ticket_code }}</p>
                </div>
            </div>
        </div>

        {{-- Main Ticket Body --}}
        <div class="p-6 sm:p-8 space-y-6">
            {{-- Journey Route Highlights --}}
            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-center">
                    {{-- Origin --}}
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Kota Keberangkatan</span>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ $order->route->origin_city }}</h2>
                        <p class="text-xs font-semibold text-slate-600 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $order->route->origin_terminal }}
                        </p>
                        <p class="mt-2 text-xs font-bold text-slate-900 bg-white border border-slate-200 rounded-lg px-2.5 py-1 inline-block font-mono">
                            {{ \Carbon\Carbon::parse($order->route->departure_time)->format('H:i') }} WIB
                        </p>
                    </div>

                    {{-- Arrow & Bus Indicator --}}
                    <div class="flex flex-col items-center justify-center text-center">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">{{ $order->route->bus->bus_name }}</span>
                        <div class="w-full flex items-center justify-center gap-2 my-1.5">
                            <div class="h-0.5 flex-1 bg-slate-200"></div>
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-50 text-blue-600 border border-blue-200">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16c0 .88.39 1.67 1 2.22V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h8v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm9 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm-11-7h13V6.5c0-.83-.67-1.5-1.5-1.5h-10c-.83 0-1.5.67-1.5 1.5V10z"/></svg>
                            </div>
                            <div class="h-0.5 flex-1 bg-slate-200"></div>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700 capitalize">{{ str_replace('_', ' ', $order->route->bus->bus_type) }}</span>
                    </div>

                    {{-- Destination --}}
                    <div class="sm:text-right">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Kota Tujuan</span>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ $order->route->destination_city }}</h2>
                        <p class="text-xs font-semibold text-slate-600 mt-1 flex sm:justify-end items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $order->route->destination_terminal }}
                        </p>
                        <p class="mt-2 text-xs font-bold text-slate-900 bg-white border border-slate-200 rounded-lg px-2.5 py-1 inline-block font-mono">
                            {{ \Carbon\Carbon::parse($order->route->estimated_arrival_time)->format('H:i') }} WIB (Estimasi)
                        </p>
                    </div>
                </div>
            </div>

            {{-- Date & Key Meta Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 rounded-xl border border-slate-200 p-4 text-xs">
                <div>
                    <span class="text-slate-400 uppercase font-semibold text-[11px]">Tanggal Keberangkatan</span>
                    <p class="font-bold text-slate-900 mt-0.5">{{ $order->route->departure_date->translatedFormat('l, d F Y') }}</p>
                </div>
                <div>
                    <span class="text-slate-400 uppercase font-semibold text-[11px]">Kode Pemesanan</span>
                    <p class="font-bold font-mono text-slate-900 mt-0.5">{{ $order->order_code }}</p>
                </div>
                <div>
                    <span class="text-slate-400 uppercase font-semibold text-[11px]">Plat Nomor Armada</span>
                    <p class="font-bold font-mono text-slate-900 mt-0.5">{{ $order->route->bus->plate_number ?: 'Tersedia di Loket' }}</p>
                </div>
                <div>
                    <span class="text-slate-400 uppercase font-semibold text-[11px]">Status Tiket</span>
                    <p class="font-bold text-emerald-600 mt-0.5 flex items-center gap-1">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Aktif / Terverifikasi
                    </p>
                </div>
            </div>

            {{-- Classic Perforated Separator with Notches --}}
            <div class="relative py-2 -mx-6 sm:-mx-8">
                <div class="absolute left-0 top-1/2 -mt-3 h-6 w-3 rounded-r-full bg-slate-100 border-y border-r border-slate-200"></div>
                <div class="border-t-2 border-dashed border-slate-200 w-full"></div>
                <div class="absolute right-0 top-1/2 -mt-3 h-6 w-3 rounded-l-full bg-slate-100 border-y border-l border-slate-200"></div>
            </div>

            {{-- Passengers & QR Code Row --}}
            <div class="grid gap-6 sm:grid-cols-3 items-center">
                {{-- Left: Passenger Manifest (2 cols) --}}
                <div class="sm:col-span-2 space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Penumpang & Kursi</h3>
                    <div class="divide-y divide-slate-100 border border-slate-200 rounded-xl overflow-hidden bg-white">
                        @foreach ($order->details as $index => $detail)
                            <div class="p-3 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-slate-100 font-bold text-[11px] text-slate-600">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $detail->passenger_name }}</p>
                                        <p class="text-[11px] text-slate-400">{{ $detail->passenger_phone }}</p>
                                    </div>
                                </div>
                                <span class="rounded-lg bg-blue-50 px-2.5 py-1 font-mono text-xs font-bold text-blue-700 border border-blue-200">
                                    Kursi {{ $detail->seat_number }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Right: QR Code (1 col) --}}
                <div class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border border-slate-200 text-center">
                    <div class="rounded-xl bg-white p-2.5 shadow-sm border border-slate-200 inline-block">
                        <img src="{{ $ticketQrCode }}" alt="QR code e-ticket {{ $order->order_code }}" class="h-32 w-32 object-contain">
                    </div>
                    <span class="mt-2 text-[11px] font-bold text-slate-700 uppercase tracking-wider">Scan untuk Boarding</span>
                    <p class="text-[10px] text-slate-400">Tunjukkan ke petugas loket</p>
                </div>
            </div>

            {{-- Boarding Instructions Notice --}}
            <div class="rounded-xl border border-blue-100 bg-blue-50/60 p-4 text-xs text-slate-600 space-y-1.5">
                <div class="flex items-center gap-1.5 font-bold text-blue-900">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8V5m0 14a9 9 0 110-18 9 9 0 0118 0z"/></svg>
                    <span>Ketentuan Boarding PO CAN Travel</span>
                </div>
                <p class="leading-relaxed">
                    1. Penumpang wajib tiba di terminal keberangkatan paling lambat <strong>30 menit sebelum jadwal bus berangkat</strong>.
                </p>
                <p class="leading-relaxed">
                    2. Tunjukkan e-ticket digital atau cetak beserta kartu identitas resmi (KTP/SIM/Paspor) yang berlaku kepada petugas loket/kru bus.
                </p>
                <p class="leading-relaxed">
                    3. Nomor tiket ini unik dan hanya berlaku untuk satu kali jadwal perjalanan sesuai data manifes di atas.
                </p>
            </div>
        </div>
    </article>
</div>

{{-- Print-friendly CSS rules --}}
<style>
@media print {
    .no-print, header, footer, nav, .bottom-nav {
        display: none !important;
    }
    body {
        background: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
    }
    main {
        padding: 0 !important;
    }
    article {
        border: 1px solid #cbd5e1 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }
}
</style>
@endsection
