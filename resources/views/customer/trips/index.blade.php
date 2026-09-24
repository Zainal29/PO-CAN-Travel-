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
        'morning' => 'Pagi (00:00 - 12:00)',
        'afternoon' => 'Siang (12:00 - 18:00)',
        'evening' => 'Sore (18:00 - 22:00)',
        'night' => 'Malam (22:00 - 24:00)',
    ];

    $hasCompleteSearch = request()->filled('origin_city') 
        && request()->filled('destination_city') 
        && request()->filled('departure_date');

    $jakartaNow = \Carbon\Carbon::now('Asia/Jakarta');
@endphp

<div class="space-y-6 sm:space-y-8">
    {{-- 1. Search Box Widget --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-7 shadow-xs" x-data="cityAutocomplete()">
        <div class="mb-5 border-b border-slate-100 pb-3">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">
                Cari Perjalanan Bus
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Tentukan rute asal, tujuan, dan tanggal perjalanan untuk menemukan jadwal resmi.
            </p>
        </div>

        <form method="GET" action="{{ route('customer.trips.index') }}" class="space-y-4">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Kota Asal --}}
                <div class="relative" @click.outside="closeSuggestions('origin_city')">
                    <label for="origin_city" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Kota Asal
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="10" r="3"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0z"/>
                            </svg>
                        </span>
                        <input id="origin_city" name="origin_city" type="text" required
                               value="{{ request('origin_city') }}"
                               placeholder="Contoh: Jepara"
                               autocomplete="off"
                               x-on:input="searchCities('origin_city', $event.target.value)"
                               x-on:focus="searchCities('origin_city', $event.target.value)"
                               class="w-full rounded-lg border-slate-300 py-2.5 pl-9 pr-3 text-sm focus:border-blue-600 focus:ring-blue-600">
                    </div>
                    <div x-cloak x-show="activeField === 'origin_city' && suggestions.length" 
                         class="absolute inset-x-0 top-full z-20 mt-1 max-h-48 overflow-y-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                        <template x-for="city in suggestions" :key="city">
                            <button type="button" x-on:click="selectCity('origin_city', city)" 
                                    class="block w-full px-3 py-2 text-left text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700" 
                                    x-text="city"></button>
                        </template>
                    </div>
                </div>

                {{-- Kota Tujuan --}}
                <div class="relative" @click.outside="closeSuggestions('destination_city')">
                    <label for="destination_city" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Kota Tujuan
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <input id="destination_city" name="destination_city" type="text" required
                               value="{{ request('destination_city') }}"
                               placeholder="Contoh: Semarang"
                               autocomplete="off"
                               x-on:input="searchCities('destination_city', $event.target.value)"
                               x-on:focus="searchCities('destination_city', $event.target.value)"
                               class="w-full rounded-lg border-slate-300 py-2.5 pl-9 pr-3 text-sm focus:border-blue-600 focus:ring-blue-600">
                    </div>
                    <div x-cloak x-show="activeField === 'destination_city' && suggestions.length" 
                         class="absolute inset-x-0 top-full z-20 mt-1 max-h-48 overflow-y-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                        <template x-for="city in suggestions" :key="city">
                            <button type="button" x-on:click="selectCity('destination_city', city)" 
                                    class="block w-full px-3 py-2 text-left text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700" 
                                    x-text="city"></button>
                        </template>
                    </div>
                </div>

                {{-- Tanggal Berangkat --}}
                <div>
                    <label for="departure_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Tanggal Berangkat
                    </label>
                    <input id="departure_date" name="departure_date" type="date" required
                           min="{{ $jakartaNow->toDateString() }}"
                           value="{{ request('departure_date', $jakartaNow->toDateString()) }}"
                           class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-blue-600 focus:ring-blue-600">
                </div>

                {{-- Penumpang --}}
                <div>
                    <label for="passengers" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Penumpang
                    </label>
                    <select id="passengers" name="passengers" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-blue-600 focus:ring-blue-600">
                        <option value="1" @selected(request('passengers', 1) == 1)>1 Penumpang</option>
                        <option value="2" @selected(request('passengers') == 2)>2 Penumpang</option>
                        <option value="3" @selected(request('passengers') == 3)>3 Penumpang</option>
                        <option value="4" @selected(request('passengers') == 4)>4 Penumpang</option>
                        <option value="5" @selected(request('passengers') == 5)>5 Penumpang</option>
                    </select>
                </div>
            </div>

            {{-- Filter Tambahan: Kelas Bus, Waktu, Harga, Urutkan --}}
            <details class="rounded-xl border border-slate-200 bg-slate-50/70" @if(request()->anyFilled(['bus_type', 'departure_period', 'max_price', 'sort_by'])) open @endif>
                <summary class="cursor-pointer select-none px-4 py-2.5 text-xs font-bold text-slate-700 hover:text-blue-600 transition flex items-center justify-between">
                    <span>Filter Tambahan (Kelas Armada, Waktu, Harga, Urutan)</span>
                    <span class="text-xs text-slate-400">Klik untuk buka/tutup</span>
                </summary>

                <div class="grid gap-3 border-t border-slate-200 p-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="bus_type" class="block text-xs font-semibold text-slate-600 mb-1">Kelas Bus</label>
                        <select id="bus_type" name="bus_type" class="w-full rounded-lg border-slate-300 bg-white text-xs py-2 focus:border-blue-600 focus:ring-blue-600">
                            <option value="">Semua Kelas</option>
                            @foreach($busTypes as $val => $lbl)
                                <option value="{{ $val }}" @selected(request('bus_type') === $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="departure_period" class="block text-xs font-semibold text-slate-600 mb-1">Waktu Berangkat</label>
                        <select id="departure_period" name="departure_period" class="w-full rounded-lg border-slate-300 bg-white text-xs py-2 focus:border-blue-600 focus:ring-blue-600">
                            <option value="">Semua Waktu</option>
                            @foreach($departurePeriods as $val => $lbl)
                                <option value="{{ $val }}" @selected(request('departure_period') === $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="max_price" class="block text-xs font-semibold text-slate-600 mb-1">Harga Maksimal</label>
                        <input id="max_price" name="max_price" type="number" min="0" 
                               value="{{ request('max_price') }}"
                               placeholder="Contoh: 100000"
                               class="w-full rounded-lg border-slate-300 bg-white text-xs py-2 focus:border-blue-600 focus:ring-blue-600">
                    </div>

                    <div>
                        <label for="sort_by" class="block text-xs font-semibold text-slate-600 mb-1">Urutan</label>
                        <select id="sort_by" name="sort_by" class="w-full rounded-lg border-slate-300 bg-white text-xs py-2 focus:border-blue-600 focus:ring-blue-600">
                            <option value="departure_earliest" @selected(request('sort_by', 'departure_earliest') === 'departure_earliest')>Keberangkatan Terpagi</option>
                            <option value="departure_latest" @selected(request('sort_by') === 'departure_latest')>Keberangkatan Terbaru</option>
                            <option value="price_asc" @selected(request('sort_by') === 'price_asc')>Harga Termurah</option>
                            <option value="price_desc" @selected(request('sort_by') === 'price_desc')>Harga Termahal</option>
                        </select>
                    </div>
                </div>
            </details>

            <div class="flex items-center justify-end gap-3 pt-1">
                @if(request()->anyFilled(['origin_city', 'destination_city', 'departure_date', 'bus_type', 'max_price']))
                    <a href="{{ route('customer.trips.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" 
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-6 py-2.5 text-xs font-bold text-white shadow-xs transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35"/>
                    </svg>
                    <span>Cari Perjalanan</span>
                </button>
            </div>
        </form>
    </div>

    {{-- 2. Hasil Pencarian --}}
    @if ($routes->count())
        <div>
            <div class="mb-4 flex flex-col justify-between gap-2 sm:flex-row sm:items-end">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">
                        Hasil Pencarian Tiket
                    </h2>
                    <p class="text-xs text-slate-500">
                        {{ $routes->total() }} perjalanan ditemukan. Menampilkan jadwal bus untuk rute {{ request('origin_city') }} &rarr; {{ request('destination_city') }}.
                    </p>
                </div>
            </div>

            {{-- Grid Tiket Bus --}}
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($routes as $route)
                    @php
                        $depDateStr = $route->departure_date instanceof \Carbon\Carbon 
                            ? $route->departure_date->format('Y-m-d') 
                            : \Carbon\Carbon::parse($route->departure_date)->format('Y-m-d');
                        
                        $departureDt = \Carbon\Carbon::parse($depDateStr . ' ' . $route->departure_time, 'Asia/Jakarta');
                        $arrivalDt = $route->estimated_arrival_time 
                            ? \Carbon\Carbon::parse($depDateStr . ' ' . $route->estimated_arrival_time, 'Asia/Jakarta')
                            : (clone $departureDt)->addHours(3);

                        if ($arrivalDt->lessThan($departureDt)) { $arrivalDt->addDay(); }

                        if ($jakartaNow->greaterThan($arrivalDt)) {
                            $timeContext = 'Keberangkatan telah lewat';
                            $timeBadgeClass = 'bg-slate-100 text-slate-600 border border-slate-200';
                        } elseif ($jakartaNow->greaterThanOrEqualTo($departureDt) && $jakartaNow->lessThanOrEqualTo($arrivalDt)) {
                            $timeContext = 'Perjalanan telah dimulai';
                            $timeBadgeClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                        } elseif ($jakartaNow->diffInHours($departureDt, false) >= 1 && $jakartaNow->diffInHours($departureDt, false) <= 12) {
                            $diffHours = (int) $jakartaNow->diffInHours($departureDt, false);
                            $timeContext = "Berangkat dalam {$diffHours} jam";
                            $timeBadgeClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                        } elseif ($departureDt->isToday('Asia/Jakarta')) {
                            $timeContext = 'Berangkat hari ini';
                            $timeBadgeClass = 'bg-blue-50 text-blue-700 border border-blue-200';
                        } elseif ($departureDt->isTomorrow('Asia/Jakarta')) {
                            $timeContext = 'Berangkat besok';
                            $timeBadgeClass = 'bg-blue-50 text-blue-700 border border-blue-200';
                        } else {
                            $timeContext = 'Berangkat ' . $departureDt->locale('id')->isoFormat('D MMM Y');
                            $timeBadgeClass = 'bg-slate-100 text-slate-700 border border-slate-200';
                        }
                    @endphp

                    {{-- Tiket Bus Card (Gaya Vertikal Sesuai Referensi PRD) --}}
                    <article class="flex flex-col justify-between rounded-xl border border-slate-200 bg-white p-5 shadow-xs transition hover:border-blue-300 hover:shadow-sm">
                        <div>
                            {{-- Header Meta --}}
                            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3 text-xs">
                                <span class="font-bold text-slate-700">
                                    {{ $route->departure_date->translatedFormat('d M Y') }}
                                </span>
                                <div class="flex items-center gap-1.5">
                                    <span class="rounded px-2 py-0.5 text-[11px] font-semibold {{ $timeBadgeClass }}">
                                        {{ $timeContext }}
                                    </span>
                                    <span class="rounded bg-green-50 px-2 py-0.5 text-[11px] font-semibold text-green-700 border border-green-200">
                                        Tersedia
                                    </span>
                                </div>
                            </div>

                            {{-- Route Visual Vertikal (07:30 Jepara │ Semarang 09:30) --}}
                            <div class="py-4 space-y-1">
                                <div class="flex items-start gap-3">
                                    <div class="w-14 shrink-0 text-right pt-0.5 font-mono text-base font-extrabold text-slate-900">
                                        {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }}
                                    </div>
                                    <div class="relative flex flex-col items-center">
                                        <span class="h-2.5 w-2.5 rounded-full border-2 border-blue-600 bg-white"></span>
                                        <span class="h-10 w-0.5 bg-slate-300"></span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-bold text-slate-900 leading-tight">
                                            {{ $route->origin_city }}
                                        </h4>
                                        <p class="text-xs text-slate-500 truncate">
                                            {{ $route->origin_terminal }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="w-14 shrink-0 text-right pt-0.5 font-mono text-base font-extrabold text-slate-600">
                                        {{ $route->estimated_arrival_time ? \Carbon\Carbon::parse($route->estimated_arrival_time)->format('H:i') : '--:--' }}
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-bold text-slate-900 leading-tight">
                                            {{ $route->destination_city }}
                                        </h4>
                                        <p class="text-xs text-slate-500 truncate">
                                            {{ $route->destination_terminal }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Info Bus & Kursi --}}
                            <div class="border-t border-slate-100 pt-3 flex items-center justify-between text-xs text-slate-600">
                                <div>
                                    <span class="font-bold text-slate-900">{{ $route->bus->bus_name }}</span>
                                    <span class="text-slate-300">·</span>
                                    <span class="capitalize text-slate-600">{{ str_replace('_', ' ', $route->bus->bus_type) }}</span>
                                </div>
                                <div class="font-semibold text-green-700">
                                    {{ $route->available_seats }} kursi tersedia
                                </div>
                            </div>
                        </div>

                        {{-- Harga & Tombol Lihat Detail --}}
                        <div class="border-t border-slate-100 pt-4 mt-4 flex items-center justify-between gap-3">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Tarif</span>
                                <span class="text-lg font-black text-slate-900 font-mono">
                                    Rp {{ number_format($route->price, 0, ',', '.') }}
                                </span>
                            </div>

                            <a href="{{ route('customer.trips.show', $route) }}" 
                               class="inline-flex items-center justify-center rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2 text-xs font-bold text-white shadow-xs transition">
                                Lihat Detail
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $routes->links() }}
            </div>
        </div>

    @elseif ($hasCompleteSearch)
        {{-- Empty Search Result --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-xs space-y-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 mx-auto">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">Perjalanan tidak ditemukan</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                Tidak ada jadwal bus untuk rute {{ request('origin_city') }} &rarr; {{ request('destination_city') }} pada tanggal yang dipilih.
            </p>
            @if (($matchingSchedules ?? 0) > 0)
                <p class="text-xs font-medium text-amber-700 max-w-md mx-auto">
                    {{ $matchingSchedules }} jadwal tersedia untuk rute ini, namun terfilter oleh filter kelas armada, jam, atau harga.
                </p>
            @endif
            <div class="pt-2">
                <a href="{{ route('customer.trips.index') }}" 
                   class="inline-flex rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-xs">
                    Reset Pencarian
                </a>
            </div>
        </div>
    @endif

    {{-- 3. Rekomendasi Jadwal Terdekat (Tampil jika pencarian kosong atau pelengkap) --}}
    @if ($availableRoutes->isNotEmpty())
        <section class="border-t border-slate-200 pt-8 space-y-4">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Perjalanan yang dapat dipesan</h2>
                    <p class="text-xs text-slate-500">Bus yang memiliki perjalanan tersedia dan siap dipesan langsung.</p>
                </div>
                <span class="text-xs font-semibold text-slate-500">{{ $availableRoutes->count() }} perjalanan terdekat</span>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($availableRoutes as $availableRoute)
                    <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs transition hover:border-blue-300">
                        <div class="flex items-center justify-between text-xs">
                            <span class="rounded bg-slate-100 px-2 py-0.5 font-bold uppercase text-[11px] text-slate-700">
                                {{ $busTypes[$availableRoute->bus->bus_type] ?? $availableRoute->bus->bus_type }}
                            </span>
                            <span class="font-semibold text-green-700">{{ $availableRoute->available_seats }} kursi</span>
                        </div>

                        <h3 class="mt-3 text-base font-bold text-slate-900">
                            {{ $availableRoute->origin_city }} <span class="text-blue-600 font-normal">→</span> {{ $availableRoute->destination_city }}
                        </h3>
                        <p class="text-xs text-slate-500">{{ $availableRoute->bus->bus_name }}</p>

                        <div class="mt-4 grid grid-cols-2 border-y border-slate-100 py-2.5 text-xs">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Jam Berangkat</span>
                                <span class="font-mono font-bold text-slate-900">{{ \Carbon\Carbon::parse($availableRoute->departure_time)->format('H:i') }} WIB</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Tanggal</span>
                                <span class="font-bold text-slate-900">{{ $availableRoute->departure_date->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="font-mono font-bold text-base text-slate-900">
                                Rp {{ number_format($availableRoute->price, 0, ',', '.') }}
                            </span>
                            <a href="{{ route('customer.trips.show', $availableRoute) }}" 
                               class="inline-flex rounded-lg bg-blue-600 hover:bg-blue-700 px-3.5 py-1.5 text-xs font-bold text-white shadow-xs transition">
                                Lihat Detail
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
