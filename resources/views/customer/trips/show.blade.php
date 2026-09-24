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

$busTypeLabel = $busTypes[$route->bus->bus_type]
    ?? ucfirst(str_replace('_', ' ', $route->bus->bus_type));


@endphp

<div class="space-y-5">


{{-- Back --}}
<a
    href="{{ route('customer.trips.index') }}"
    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 transition hover:text-gray-900"
>
    <svg class="h-4 w-4"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="2">
        <path d="m15 18-6-6 6-6"/>
    </svg>

    Kembali ke pencarian
</a>

{{-- Header --}}
<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

    <div class="p-6 md:p-8">

        <div class="flex flex-col justify-between gap-5 md:flex-row md:items-start">

            <div class="flex gap-4">

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gray-100 text-lg font-bold text-gray-700">
                    {{ strtoupper(substr($route->bus->bus_name, 0, 2)) }}
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        {{ $busTypeLabel }}
                    </p>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                        {{ $route->bus->bus_name }}
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $route->bus->bus_code }}
                        @if($route->bus->plate_number)
                            · {{ $route->bus->plate_number }}
                        @endif
                    </p>
                </div>

            </div>

            <span class="inline-flex w-fit rounded-full bg-green-50 px-3 py-1.5 text-xs font-bold text-green-700">
                Tersedia
            </span>

        </div>

        {{-- Route --}}
        <div class="mt-8 rounded-2xl bg-gray-50 p-5 md:p-6">

            <div class="grid gap-6 md:grid-cols-[1fr_auto_1fr] md:items-center">

                <div>
                    <p class="text-xs font-medium text-gray-400">
                        Berangkat
                    </p>

                    <p class="mt-1 text-3xl font-bold tracking-tight text-gray-900">
                        {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }}
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-700">
                        {{ $route->origin_city }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        {{ $route->origin_terminal }}
                    </p>
                </div>

                <div class="flex items-center justify-center">

                    <div class="hidden h-px w-20 bg-gray-300 md:block"></div>

                    <div class="mx-3 flex h-9 w-9 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-500">
                        <svg class="h-4 w-4"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path d="M5 12h14"/>
                            <path d="m13 6 6 6-6 6"/>
                        </svg>
                    </div>

                    <div class="hidden h-px w-20 bg-gray-300 md:block"></div>

                </div>

                <div class="md:text-right">

                    <p class="text-xs font-medium text-gray-400">
                        Perkiraan tiba
                    </p>

                    <p class="mt-1 text-3xl font-bold tracking-tight text-gray-900">
                        {{ \Carbon\Carbon::parse($route->estimated_arrival_time)->format('H:i') }}
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-700">
                        {{ $route->destination_city }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        {{ $route->destination_terminal }}
                    </p>

                </div>

            </div>

            <div class="mt-6 border-t border-gray-200 pt-5">

                <div class="flex flex-wrap gap-x-6 gap-y-3 text-xs text-gray-500">

                    <span>
                        <strong class="text-gray-700">Tanggal:</strong>
                        {{ $route->departure_date->locale('id')->translatedFormat('d F Y') }}
                    </span>

                    <span>
                        <strong class="text-gray-700">Kursi:</strong>
                        {{ $route->available_seats }} tersedia
                    </span>

                    <span>
                        <strong class="text-gray-700">Kapasitas:</strong>
                        {{ $route->bus->total_seats }} kursi
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- Content --}}
<div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_340px]">

    {{-- Information --}}
    <div class="space-y-5">

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Detail perjalanan
            </h2>

            <div class="mt-5 grid gap-5 sm:grid-cols-2">

                <div>
                    <p class="text-xs font-medium text-gray-400">
                        Kota asal
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $route->origin_city }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-400">
                        Kota tujuan
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $route->destination_city }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-400">
                        Terminal keberangkatan
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $route->origin_terminal }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-400">
                        Terminal tujuan
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $route->destination_terminal }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-400">
                        Kelas bus
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $busTypeLabel }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-400">
                        Nomor kendaraan
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $route->bus->plate_number ?: '-' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Facilities --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Fasilitas bus
            </h2>

            @if(!empty($route->bus->facilities))

                <div class="mt-5 grid gap-3 sm:grid-cols-2">

                    @foreach($route->bus->facilities as $facility)

                        <div class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">

                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-gray-500">
                                <svg class="h-4 w-4"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path d="M5 12h14"/>
                                    <path d="m13 6 6 6-6 6"/>
                                </svg>
                            </div>

                            <span class="text-sm font-medium text-gray-700">
                                {{ $facility }}
                            </span>

                        </div>

                    @endforeach

                </div>

            @else

                <p class="mt-4 text-sm text-gray-500">
                    Informasi fasilitas belum tersedia.
                </p>

            @endif

        </div>

        {{-- Description --}}
        @if($route->bus->description)

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-bold text-gray-900">
                    Tentang bus
                </h2>

                <p class="mt-4 text-sm leading-6 text-gray-600">
                    {{ $route->bus->description }}
                </p>

            </div>

        @endif

    </div>

    {{-- Booking Card --}}
    <aside class="h-fit lg:sticky lg:top-5">

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="p-6">

                <p class="text-xs font-medium text-gray-500">
                    Harga tiket
                </p>

                <p class="mt-1 text-3xl font-bold tracking-tight text-gray-900">
                    Rp {{ number_format($route->price, 0, ',', '.') }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    per penumpang
                </p>

                <div class="my-6 border-t border-gray-100"></div>

                <div class="flex items-center justify-between text-sm">

                    <span class="text-gray-500">
                        Kursi tersedia
                    </span>

                    <span class="font-bold text-gray-900">
                        {{ $route->available_seats }}
                    </span>

                </div>

                <div class="mt-3 flex items-center justify-between text-sm">

                    <span class="text-gray-500">
                        Kelas bus
                    </span>

                    <span class="font-semibold text-gray-900">
                        {{ $busTypeLabel }}
                    </span>

                </div>

                @if($route->status === 'available' && $route->available_seats > 0)

                    <a
                        href="{{ $canBook ? route('customer.bookings.create', $route) : route('login') }}"
                        class="mt-6 block rounded-xl bg-gray-900 px-4 py-3.5 text-center text-sm font-bold text-white transition hover:bg-gray-700"
                    >
                        {{ $canBook ? 'Pilih Kursi' : 'Masuk untuk Memesan' }}
                    </a>

                    @if(!$canBook)

                        <p class="mt-3 text-center text-xs leading-5 text-gray-500">
                            Silakan masuk menggunakan akun customer
                            untuk melanjutkan pemesanan.
                        </p>

                    @endif

                @else

                    <div class="mt-6 rounded-xl bg-gray-100 px-4 py-3.5 text-center text-sm font-semibold text-gray-500">
                        Perjalanan tidak tersedia
                    </div>

                @endif

            </div>

            <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">

                <div class="flex gap-3">

                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-gray-400"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 8v4"/>
                        <path d="M12 16h.01"/>
                    </svg>

                    <p class="text-xs leading-5 text-gray-500">
                        Harga yang ditampilkan adalah harga tiket
                        untuk satu penumpang.
                    </p>

                </div>

            </div>

        </div>

    </aside>

</div>


</div>

@endsection
