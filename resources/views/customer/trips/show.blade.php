@extends('customer.layouts.index')

@section('title', 'Detail Perjalanan — PO CAN Travel')

@section('content')
@php
    $canBook = auth()->check() && auth()->user()->role === 'customer';
    $busTypes = [
        'economy' => 'Economy',
        'executive' => 'Executive',
        'vip' => 'VIP',
        'super_vip' => 'Super VIP',
    ];
    $busTypeLabel = $busTypes[$route->bus->bus_type] ?? ucfirst(str_replace('_', ' ', $route->bus->bus_type));
@endphp

<div class="space-y-6">
    {{-- Back Link --}}
    <div>
        <a href="{{ route('customer.trips.index') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Pencarian</span>
        </a>
    </div>

    {{-- Main Header Card --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between border-b border-slate-100 pb-6">
            <div class="flex items-center gap-4">
                {{-- Foto Thumbnail Bus --}}
                <div class="relative h-16 w-24 sm:h-20 sm:w-28 shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-slate-900 shadow-xs">
                    <img src="{{ $route->bus->image_url }}" 
                         alt="{{ $route->bus->bus_name }}" 
                         class="h-full w-full object-cover"
                         onerror="this.src='{{ asset('images/hero-bus.jpg') }}'">
                    <span class="absolute bottom-1 right-1 rounded bg-black/70 px-1.5 py-0.5 text-[9px] font-bold text-white">
                        Armada
                    </span>
                </div>
                <div>
                    <span class="rounded bg-blue-50 px-2.5 py-0.5 text-xs font-bold text-blue-700 border border-blue-200 uppercase tracking-wide">
                        {{ $busTypeLabel }}
                    </span>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1">
                        {{ $route->bus->bus_name }}
                    </h1>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Kode Bus: <span class="font-mono font-semibold text-slate-700">{{ $route->bus->bus_code }}</span>
                        @if($route->bus->plate_number)
                            · Nomor Polisi: <span class="font-mono font-semibold text-slate-700">{{ $route->bus->plate_number }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="rounded-md bg-green-50 px-3 py-1 text-xs font-bold text-green-700 border border-green-200">
                    Tersedia
                </span>
            </div>
        </div>

        {{-- Route Visual Sederhana (Origin → Destination) --}}
        <div class="mt-6 rounded-xl bg-slate-50 p-5 sm:p-6 border border-slate-100">
            <div class="grid gap-6 sm:grid-cols-[1fr_auto_1fr] items-center">
                {{-- Asal --}}
                <div class="space-y-0.5">
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Keberangkatan</span>
                    <p class="text-2xl font-black font-mono text-slate-900">
                        {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }} <span class="text-xs font-sans font-medium text-slate-400">WIB</span>
                    </p>
                    <h3 class="text-base font-bold text-slate-900">{{ $route->origin_city }}</h3>
                    <p class="text-xs text-slate-500">{{ $route->origin_terminal }}</p>
                </div>

                {{-- Connector Arrow --}}
                <div class="flex sm:flex-col items-center justify-center text-blue-600 font-bold">
                    <span class="text-xs text-slate-400 uppercase font-semibold sm:hidden mb-1">Menuju</span>
                    <span class="text-2xl sm:text-3xl leading-none">↓</span>
                </div>

                {{-- Tujuan --}}
                <div class="space-y-0.5 sm:text-right">
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Kedatangan (Estimasi)</span>
                    <p class="text-2xl font-black font-mono text-slate-700">
                        {{ $route->estimated_arrival_time ? \Carbon\Carbon::parse($route->estimated_arrival_time)->format('H:i') : '--:--' }} <span class="text-xs font-sans font-medium text-slate-400">WIB</span>
                    </p>
                    <h3 class="text-base font-bold text-slate-900">{{ $route->destination_city }}</h3>
                    <p class="text-xs text-slate-500">{{ $route->destination_terminal }}</p>
                </div>
            </div>

            <div class="mt-5 border-t border-slate-200/80 pt-3 flex flex-wrap gap-4 text-xs text-slate-600 font-medium">
                <div>
                    <span class="text-slate-400">Tanggal:</span>
                    <strong class="text-slate-800">{{ $route->departure_date->translatedFormat('l, d F Y') }}</strong>
                </div>
                <div>
                    <span class="text-slate-400">Kursi Tersedia:</span>
                    <strong class="text-green-700">{{ $route->available_seats }} dari {{ $route->bus->total_seats }} kursi</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Details & Booking Sidebar Layout --}}
    <div class="grid gap-6 lg:grid-cols-[1fr_340px]">
        {{-- Left Details Column --}}
        <div class="space-y-6">
            {{-- Showcase Visual Bus (Foto Banner Armada) --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs">
                <div class="relative h-56 sm:h-72 w-full overflow-hidden bg-slate-950">
                    <img src="{{ $route->bus->image_url }}" 
                         alt="{{ $route->bus->bus_name }}" 
                         class="h-full w-full object-cover object-center"
                         onerror="this.src='{{ asset('images/hero-bus.jpg') }}'">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-black/30"></div>
                    
                    <div class="absolute top-4 inset-x-4 flex items-center justify-between">
                        <span class="rounded-lg bg-blue-600/90 backdrop-blur-xs px-3 py-1 text-xs font-extrabold uppercase text-white shadow-xs">
                            {{ $busTypeLabel }}
                        </span>
                        <span class="rounded-lg bg-black/60 backdrop-blur-xs px-2.5 py-1 text-xs font-semibold text-white">
                            {{ $route->bus->total_seats }} Total Kursi
                        </span>
                    </div>

                    <div class="absolute bottom-4 inset-x-4 flex items-end justify-between text-white">
                        <div>
                            <p class="text-[11px] uppercase tracking-wider text-blue-300 font-bold">Armada Resmi PO CAN Travel</p>
                            <h3 class="text-xl sm:text-2xl font-black">{{ $route->bus->bus_name }}</h3>
                        </div>
                        @if($route->bus->plate_number)
                            <span class="font-mono text-xs bg-white/20 backdrop-blur-xs px-2.5 py-1 rounded-md font-bold text-slate-100">
                                {{ $route->bus->plate_number }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Detail Fasilitas Bus --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs space-y-4">
                <h2 class="text-base font-bold text-slate-900">Fasilitas Armada</h2>

                @if(!empty($route->bus->facilities))
                    <div class="grid gap-2.5 sm:grid-cols-2">
                        @foreach($route->bus->facilities as $facility)
                            <div class="flex items-center gap-2.5 rounded-lg border border-slate-100 bg-slate-50 px-3.5 py-2.5 text-xs font-semibold text-slate-700">
                                <svg class="h-4 w-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>{{ $facility }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500">Informasi fasilitas bus belum ditambahkan.</p>
                @endif
            </div>

            {{-- Deskripsi Bus --}}
            @if($route->bus->description)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs space-y-2">
                    <h2 class="text-base font-bold text-slate-900">Tentang Bus Ini</h2>
                    <p class="text-xs leading-relaxed text-slate-600">{{ $route->bus->description }}</p>
                </div>
            @endif

            {{-- Reviews & Rating Pelanggan --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Ulasan & Rating Penumpang</h2>
                        <p class="text-xs text-slate-500">
                            @if($route->reviews->isNotEmpty())
                                Rata-rata {{ number_format($route->reviews->avg('rating'), 1, ',', '.') }}/5 dari {{ $route->reviews->count() }} ulasan
                            @else
                                Belum ada ulasan untuk rute perjalanan ini.
                            @endif
                        </p>
                    </div>
                    @if($route->reviews->isNotEmpty())
                        <div class="text-xl font-bold font-mono text-amber-600 bg-amber-50 px-3 py-1 rounded-lg border border-amber-200">
                            ★ {{ number_format($route->reviews->avg('rating'), 1, ',', '.') }}
                        </div>
                    @endif
                </div>

                @if($route->reviews->isNotEmpty())
                    <div class="space-y-3 divide-y divide-slate-100">
                        @foreach($route->reviews->sortByDesc('created_at')->take(5) as $review)
                            <div class="pt-3 first:pt-0 space-y-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-800">{{ $review->user->name }}</span>
                                    <span class="text-amber-500 font-semibold">★ {{ $review->rating }}/5</span>
                                </div>
                                @if($review->comment)
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Sticky Booking Card --}}
        <aside class="h-fit lg:sticky lg:top-20">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs space-y-5">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Harga Tiket Resmi</span>
                    <p class="text-3xl font-black text-slate-900 font-mono mt-1">
                        Rp {{ number_format($route->price, 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Per orang, termasuk jatah bagasi & asuransi</p>
                </div>

                <div class="border-t border-slate-100 pt-4 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Kelas Armada</span>
                        <span class="font-semibold text-slate-800">{{ $busTypeLabel }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Sisa Kursi</span>
                        <span class="font-bold text-green-700">{{ $route->available_seats }} Kursi</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Kapasitas Total</span>
                        <span class="font-semibold text-slate-800">{{ $route->bus->total_seats }} Kursi</span>
                    </div>
                </div>

                {{-- CTA Button --}}
                <div class="border-t border-slate-100 pt-4 space-y-2">
                    @if($route->status === 'available' && $route->available_seats > 0)
                        <a href="{{ $canBook ? route('customer.bookings.create', $route) : route('login') }}" 
                           class="w-full flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 py-3 px-4 text-xs font-bold text-white shadow-xs transition">
                            <span>{{ $canBook ? 'Pesan Sekarang' : 'Masuk untuk Memesan' }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        @if(!$canBook)
                            <p class="text-center text-[11px] text-slate-500 leading-normal">
                                Silakan login dengan akun pelanggan untuk melanjutkan pemilihan kursi.
                            </p>
                        @endif
                    @else
                        <div class="rounded-lg bg-slate-100 py-3 text-center text-xs font-semibold text-slate-500">
                            Perjalanan Tidak Tersedia
                        </div>
                    @endif
                </div>

                <div class="border-t border-slate-100 pt-3 text-[11px] text-slate-500 flex items-start gap-2">
                    <svg class="h-4 w-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8V5m0 14a9 9 0 110-18 9 9 0 010 18z"/>
                    </svg>
                    <span>Konfirmasi nomor kursi akan Anda pilih langsung pada langkah berikutnya.</span>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
