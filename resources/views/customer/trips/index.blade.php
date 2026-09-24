@extends('customer.layouts.index')

@section('title', 'Cari Perjalanan — PO CAN Travel')

@section('content')

@php
$busTypes = [
'economy' => 'Economy',
'executive' => 'Executive',
'vip' => 'VIP',
'super_vip' => 'Super VIP',
];

$departurePeriods = [
    'morning' => 'Pagi',
    'afternoon' => 'Siang',
    'evening' => 'Sore',
    'night' => 'Malam',
];

$hasSearch = request()->anyFilled([
    'origin_city',
    'destination_city',
    'departure_date',
    'passengers',
    'bus_type',
    'departure_period',
    'max_price',
]);

$hasCompleteSearch =
    request()->filled('origin_city') &&
    request()->filled('destination_city') &&
    request()->filled('departure_date');


@endphp

<div class="space-y-6">


{{-- Header --}}
<div>
    <p class="text-sm font-semibold text-gray-500">
        PO CAN Travel
    </p>

    <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 md:text-3xl">
        Cari perjalanan bus
    </h1>

    <p class="mt-2 text-sm text-gray-500">
        Temukan jadwal bus dan pilih kursi sesuai perjalanan Anda.
    </p>
</div>

{{-- Search Card --}}
<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

    <div class="border-b border-gray-100 px-5 py-4 md:px-6">
        <h2 class="font-bold text-gray-900">
            Mau pergi ke mana?
        </h2>

        <p class="mt-1 text-xs text-gray-500">
            Masukkan tujuan perjalanan Anda.
        </p>
    </div>

    <form method="GET" action="{{ route('customer.trips.index') }}" class="p-5 md:p-6" x-data="cityAutocomplete()">

        <div class="grid gap-4 lg:grid-cols-[1fr_1fr_180px_150px_auto]">

            {{-- Origin --}}
            <div>
                <label for="origin_city"
                       class="mb-2 block text-xs font-semibold text-gray-600">
                    Kota asal
                </label>

                <div class="relative" @click.outside="closeSuggestions('origin_city')">
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="h-4 w-4"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <circle cx="12" cy="10" r="3"/>
                            <path d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0z"/>
                        </svg>
                    </span>

                    <input
                        id="origin_city"
                        name="origin_city"
                        type="text"
                        value="{{ request('origin_city') }}"
                        autocomplete="off"
                        x-on:input="searchCities('origin_city', $event.target.value)"
                        x-on:focus="searchCities('origin_city', $event.target.value)"
                        placeholder="Contoh: Jepara"
                        class="w-full rounded-xl border-gray-300 py-3 pl-10 pr-3 text-sm focus:border-gray-900 focus:ring-gray-900"
                    >
                    <div x-cloak x-show="activeField === 'origin_city' && suggestions.length" class="absolute inset-x-0 top-full z-10 mt-1 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
                        <template x-for="city in suggestions" :key="city">
                            <button type="button" x-on:click="selectCity('origin_city', city)" class="block w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-gray-50" x-text="city"></button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Destination --}}
            <div>
                <label for="destination_city"
                       class="mb-2 block text-xs font-semibold text-gray-600">
                    Kota tujuan
                </label>

                <div class="relative" @click.outside="closeSuggestions('destination_city')">
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="h-4 w-4"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <circle cx="12" cy="10" r="3"/>
                            <path d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0z"/>
                        </svg>
                    </span>

                    <input
                        id="destination_city"
                        name="destination_city"
                        type="text"
                        value="{{ request('destination_city') }}"
                        autocomplete="off"
                        x-on:input="searchCities('destination_city', $event.target.value)"
                        x-on:focus="searchCities('destination_city', $event.target.value)"
                        placeholder="Contoh: Semarang"
                        class="w-full rounded-xl border-gray-300 py-3 pl-10 pr-3 text-sm focus:border-gray-900 focus:ring-gray-900"
                    >
                    <div x-cloak x-show="activeField === 'destination_city' && suggestions.length" class="absolute inset-x-0 top-full z-10 mt-1 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
                        <template x-for="city in suggestions" :key="city">
                            <button type="button" x-on:click="selectCity('destination_city', city)" class="block w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-gray-50" x-text="city"></button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Date --}}
            <div>
                <label for="departure_date"
                       class="mb-2 block text-xs font-semibold text-gray-600">
                    Tanggal berangkat
                </label>

                <input
                    id="departure_date"
                    name="departure_date"
                    type="date"
                    min="{{ now()->timezone('Asia/Jakarta')->toDateString() }}"
                    value="{{ request('departure_date') }}"
                    class="w-full rounded-xl border-gray-300 py-3 text-sm focus:border-gray-900 focus:ring-gray-900"
                >
            </div>

            <div>
                <label for="passengers" class="mb-2 block text-xs font-semibold text-gray-600">
                    Penumpang
                </label>

                <input
                    id="passengers"
                    name="passengers"
                    type="number"
                    min="1"
                    max="10"
                    value="{{ request('passengers', 1) }}"
                    class="w-full rounded-xl border-gray-300 py-3 text-sm focus:border-gray-900 focus:ring-gray-900"
                >
            </div>

            {{-- Submit --}}
            <div class="flex items-end">
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gray-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-gray-700 lg:w-auto"
                >
                    <svg class="h-4 w-4"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-3.5-3.5"/>
                    </svg>

                    Cari
                </button>
            </div>

        </div>

        {{-- Advanced filters --}}
        <details
            class="mt-5 rounded-xl border border-gray-200 bg-gray-50"
            @if(request()->filled('bus_type') || request()->filled('departure_period') || request()->filled('max_price'))
                open
            @endif
        >
            <summary class="cursor-pointer select-none px-4 py-3 text-sm font-semibold text-gray-700">
                Filter perjalanan
            </summary>

            <div class="grid gap-4 border-t border-gray-200 p-4 md:grid-cols-3">

                <div>
                    <label for="bus_type"
                           class="mb-2 block text-xs font-semibold text-gray-600">
                        Kelas bus
                    </label>

                    <select
                        id="bus_type"
                        name="bus_type"
                        class="w-full rounded-xl border-gray-300 bg-white text-sm focus:border-gray-900 focus:ring-gray-900"
                    >
                        <option value="">Semua kelas</option>

                        @foreach($busTypes as $value => $label)
                            <option
                                value="{{ $value }}"
                                @selected(request('bus_type') === $value)
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sort_by" class="mb-2 block text-xs font-semibold text-gray-600">Urutkan</label>
                    <select id="sort_by" name="sort_by" class="w-full rounded-xl border-gray-300 bg-white text-sm focus:border-gray-900 focus:ring-gray-900">
                        <option value="departure_earliest" @selected(request('sort_by', 'departure_earliest') === 'departure_earliest')>Keberangkatan terpagi</option>
                        <option value="departure_latest" @selected(request('sort_by') === 'departure_latest')>Keberangkatan terbaru</option>
                        <option value="price_asc" @selected(request('sort_by') === 'price_asc')>Harga termurah</option>
                        <option value="price_desc" @selected(request('sort_by') === 'price_desc')>Harga termahal</option>
                    </select>
                </div>

                <div>
                    <label for="departure_period"
                           class="mb-2 block text-xs font-semibold text-gray-600">
                        Waktu berangkat
                    </label>

                    <select
                        id="departure_period"
                        name="departure_period"
                        class="w-full rounded-xl border-gray-300 bg-white text-sm focus:border-gray-900 focus:ring-gray-900"
                    >
                        <option value="">Semua waktu</option>

                        @foreach($departurePeriods as $value => $label)
                            <option
                                value="{{ $value }}"
                                @selected(request('departure_period') === $value)
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="max_price"
                           class="mb-2 block text-xs font-semibold text-gray-600">
                        Harga maksimal
                    </label>

                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                            Rp
                        </span>

                        <input
                            id="max_price"
                            name="max_price"
                            type="number"
                            min="0"
                            value="{{ request('max_price') }}"
                            placeholder="100000"
                            class="w-full rounded-xl border-gray-300 bg-white py-2.5 pl-9 text-sm focus:border-gray-900 focus:ring-gray-900"
                        >
                    </div>
                </div>

            </div>
        </details>

    </form>
</div>

{{-- Results --}}
@if ($routes->count())

    <div class="grid gap-6 lg:grid-cols-[230px_minmax(0,1fr)]">

        {{-- Filter Summary --}}
        <aside class="h-fit rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">
                <h2 class="font-bold text-gray-900">
                    Pencarian
                </h2>

                <a
                    href="{{ route('customer.trips.index') }}"
                    class="text-xs font-semibold text-gray-500 hover:text-gray-900"
                >
                    Reset
                </a>
            </div>

            <div class="mt-5 space-y-4">

                <div>
                    <p class="text-xs text-gray-400">
                        Rute
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ request('origin_city') }}
                        →
                        {{ request('destination_city') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-400">
                        Tanggal
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse(request('departure_date'))->locale('id')->translatedFormat('d M Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-400">
                        Penumpang
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ request('passengers', 1) }} orang
                    </p>
                </div>

                @if(request('bus_type'))
                    <div>
                        <p class="text-xs text-gray-400">
                            Kelas
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $busTypes[request('bus_type')] ?? request('bus_type') }}
                        </p>
                    </div>
                @endif

                @if(request('departure_period'))
                    <div>
                        <p class="text-xs text-gray-400">
                            Waktu
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $departurePeriods[request('departure_period')] ?? request('departure_period') }}
                        </p>
                    </div>
                @endif

            </div>

        </aside>

        {{-- Trip List --}}
        <section class="min-w-0">

            <div class="mb-4 flex flex-col justify-between gap-2 sm:flex-row sm:items-end">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        Jadwal perjalanan
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $routes->total() }} perjalanan ditemukan.
                    </p>
                </div>
            </div>

            <div class="space-y-4">

                @foreach ($routes as $route)

                    <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:border-gray-300 hover:shadow-md">

                        <div class="p-5 md:p-6">

                            {{-- Operator --}}
                            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-sm font-bold text-gray-700">
                                        {{ strtoupper(substr($route->bus->bus_name, 0, 2)) }}
                                    </div>

                                    <div>
                                        <h3 class="font-bold text-gray-900">
                                            {{ $route->bus->bus_name }}
                                        </h3>

                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                            <span>
                                                {{ $busTypes[$route->bus->bus_type] ?? ucfirst($route->bus->bus_type) }}
                                            </span>

                                            <span>•</span>

                                            <span>
                                                {{ $route->bus->bus_code }}
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <span class="inline-flex w-fit rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                    {{ $route->available_seats }} kursi tersedia
                                </span>

                            </div>

                            {{-- Schedule --}}
                            <div class="mt-6 grid items-center gap-5 md:grid-cols-[90px_minmax(80px,1fr)_90px]">

                                <div>
                                    <p class="text-2xl font-bold tracking-tight text-gray-900">
                                        {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }}
                                    </p>

                                    <p class="mt-1 text-xs font-medium text-gray-500">
                                        {{ $route->origin_city }}
                                    </p>
                                </div>

                                <div class="relative">

                                    <div class="flex items-center">
                                        <div class="h-px flex-1 bg-gray-200"></div>

                                        <div class="mx-3 flex h-7 w-7 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-400">
                                            <svg class="h-3.5 w-3.5"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2">
                                                <path d="M5 12h14"/>
                                                <path d="m13 6 6 6-6 6"/>
                                            </svg>
                                        </div>

                                        <div class="h-px flex-1 bg-gray-200"></div>
                                    </div>

                                    <p class="mt-2 text-center text-xs text-gray-400">
                                        Perjalanan
                                    </p>

                                </div>

                                <div class="text-right">
                                    <p class="text-2xl font-bold tracking-tight text-gray-900">
                                        {{ \Carbon\Carbon::parse($route->estimated_arrival_time)->format('H:i') }}
                                    </p>

                                    <p class="mt-1 text-xs font-medium text-gray-500">
                                        {{ $route->destination_city }}
                                    </p>
                                </div>

                            </div>

                            {{-- Information --}}
                            <div class="mt-6 grid gap-3 border-t border-gray-100 pt-5 text-xs text-gray-500 sm:grid-cols-2 lg:grid-cols-3">

                                <div>
                                    <p class="font-medium text-gray-400">
                                        Berangkat dari
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-700">
                                        {{ $route->origin_terminal }}
                                    </p>
                                </div>

                                <div>
                                    <p class="font-medium text-gray-400">
                                        Tiba di
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-700">
                                        {{ $route->destination_terminal }}
                                    </p>
                                </div>

                                <div>
                                    <p class="font-medium text-gray-400">
                                        Fasilitas
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-700">
                                        @if(!empty($route->bus->facilities))
                                            {{ collect($route->bus->facilities)->take(3)->join(', ') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>

                            </div>

                        </div>

                        {{-- Price / CTA --}}
                        <div class="flex flex-col gap-4 border-t border-gray-100 bg-gray-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between md:px-6">

                            <div>
                                <p class="text-xs text-gray-500">
                                    Harga per penumpang
                                </p>

                                <p class="mt-1 text-xl font-bold text-gray-900">
                                    Rp {{ number_format($route->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <a
                                href="{{ route('customer.trips.show', $route) }}"
                                class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-gray-700"
                            >
                                Lihat Detail
                            </a>

                        </div>

                    </article>

                @endforeach

            </div>

            <div class="mt-6">
                {{ $routes->links() }}
            </div>

        </section>

    </div>

@elseif ($hasCompleteSearch)

    {{-- No Result --}}
    <div class="rounded-2xl border border-gray-200 bg-white px-6 py-14 text-center shadow-sm">

        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-500">
            <svg class="h-6 w-6"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <circle cx="11" cy="11" r="7"/>
                <path d="m20 20-3.5-3.5"/>
            </svg>
        </div>

        <h2 class="mt-5 text-lg font-bold text-gray-900">
            Perjalanan tidak ditemukan
        </h2>

        <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
            Tidak ada jadwal yang sesuai dengan pencarian Anda.
            Coba ubah tanggal, waktu, kelas bus, atau harga maksimal.
        </p>

        @if (($matchingSchedules ?? 0) > 0)
            <p class="mx-auto mt-3 max-w-md text-sm font-medium text-amber-700">
                {{ $matchingSchedules }} jadwal tersedia untuk rute ini, tetapi tidak memenuhi filter tambahan yang dipilih.
                Coba hapus filter kelas, waktu, harga maksimal, atau jumlah penumpang.
            </p>
        @endif

        <a
            href="{{ route('customer.trips.index') }}"
            class="mt-6 inline-flex rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
        >
            Ubah pencarian
        </a>

    </div>

@else

    {{-- Initial State --}}
    <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-14 text-center">

        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-500">
            <svg class="h-6 w-6"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <path d="M3 17h18"/>
                <path d="M5 17V9l7-5 7 5v8"/>
                <path d="M9 17v-4h6v4"/>
            </svg>
        </div>

        <h2 class="mt-5 text-lg font-bold text-gray-900">
            Siap mencari perjalanan?
        </h2>

        <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
            Masukkan kota asal, kota tujuan, dan tanggal keberangkatan
            untuk melihat jadwal yang tersedia.
        </p>

    </div>

@endif

{{-- Informasi perjalanan tersedia, tetap tampil meskipun pencarian kosong. --}}
@if ($availableRoutes->isNotEmpty())
    <section class="border-t border-gray-200 pt-10">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold tracking-wide text-amber-700">JADWAL TERSEDIA</p>
                <h2 class="mt-1 text-xl font-bold text-gray-900">Perjalanan yang dapat dipesan</h2>
                <p class="mt-1 text-sm text-gray-500">Hanya bus aktif dengan kursi dan jadwal yang masih tersedia.</p>
            </div>
            <span class="text-sm text-gray-500">{{ $availableRoutes->count() }} jadwal terdekat</span>
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($availableRoutes as $availableRoute)
                <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <span class="rounded bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-600">{{ $busTypes[$availableRoute->bus->bus_type] ?? $availableRoute->bus->bus_type }}</span>
                        <span class="text-xs font-semibold text-emerald-700">{{ $availableRoute->available_seats }} kursi</span>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-gray-900">{{ $availableRoute->origin_city }} <span class="text-amber-600">→</span> {{ $availableRoute->destination_city }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $availableRoute->bus->bus_name }}</p>
                    <div class="mt-4 grid grid-cols-2 border-y border-gray-100 py-3 text-sm">
                        <div><p class="text-xs text-gray-400">Berangkat</p><p class="mt-1 font-semibold">{{ \Carbon\Carbon::parse($availableRoute->departure_time)->format('H:i') }}</p></div>
                        <div><p class="text-xs text-gray-400">Tanggal</p><p class="mt-1 font-semibold">{{ $availableRoute->departure_date->translatedFormat('d M') }}</p></div>
                    </div>
                    <div class="mt-4 flex items-end justify-between gap-3"><p class="font-bold text-gray-900">Rp {{ number_format($availableRoute->price, 0, ',', '.') }}</p><a href="{{ route('customer.trips.show', $availableRoute) }}" class="text-sm font-semibold text-gray-900 underline decoration-amber-400 decoration-2 underline-offset-4">Detail</a></div>
                </article>
            @endforeach
        </div>
    </section>
@endif

{{-- Armada hanya muncul apabila mempunyai relasi jadwal yang bisa dipesan. --}}
@if ($availableBuses->isNotEmpty())
    <section class="border-t border-gray-200 pt-10">
        <div>
            <p class="text-xs font-bold tracking-wide text-amber-700">ARMADA AKTIF</p>
            <h2 class="mt-1 text-xl font-bold text-gray-900">Bus yang memiliki perjalanan tersedia</h2>
            <p class="mt-1 text-sm text-gray-500">Armada ditampilkan hanya jika terhubung dengan setidaknya satu jadwal yang dapat dipesan.</p>
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($availableBuses as $bus)
                <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between"><span class="text-xs font-bold text-amber-700">{{ $bus->bus_code }}</span><span class="text-xs text-gray-500">{{ $bus->total_seats }} kursi</span></div>
                    <h3 class="mt-4 font-bold text-gray-900">{{ $bus->bus_name }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $busTypes[$bus->bus_type] ?? $bus->bus_type }} · {{ $bus->plate_number }}</p>
                    <p class="mt-4 border-t border-gray-100 pt-4 text-sm font-semibold text-emerald-700">{{ $bus->available_routes_count }} jadwal tersedia</p>
                    <p class="mt-2 text-xs text-gray-500">{{ !empty($bus->facilities) ? collect($bus->facilities)->take(3)->join(' · ') : 'Fasilitas belum diinformasikan.' }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endif

</div>

@endsection
