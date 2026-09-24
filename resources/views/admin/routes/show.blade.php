<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('admin.routes.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition mb-2">
                    &larr; Kembali ke daftar rute
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        {{ $route->origin_city }} &rarr; {{ $route->destination_city }}
                    </h1>
                    <x-status-badge :status="$route->status" type="route" />
                </div>
                <p class="mt-1 text-sm text-slate-500">
                    Jadwal Keberangkatan: {{ $route->departure_date->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.routes.edit', $route) }}" class="btn-primary min-h-10 px-4 py-2 text-sm shadow-sm inline-flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Rute
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-7 sm:px-6 lg:px-8 space-y-6">
        {{-- Visual Timeline Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-6">
            <h2 class="text-base font-bold text-slate-900">Visual Rute Perjalanan</h2>

            <div class="relative pl-6 sm:pl-8 border-l-2 border-dashed border-amber-400 space-y-8 my-2">
                {{-- Origin --}}
                <div class="relative">
                    <span class="absolute -left-[31px] sm:-left-[39px] top-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-brand-950 text-white ring-4 ring-white">
                        <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                    </span>
                    <div>
                        <span class="inline-block font-mono text-xs font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                            {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }} WIB
                        </span>
                        <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $route->origin_city }}</h3>
                        <p class="text-sm text-slate-500">{{ $route->origin_terminal }}</p>
                    </div>
                </div>

                {{-- Destination --}}
                <div class="relative">
                    <span class="absolute -left-[31px] sm:-left-[39px] top-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-white ring-4 ring-white">
                        <span class="h-2 w-2 rounded-full bg-white"></span>
                    </span>
                    <div>
                        <span class="inline-block font-mono text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                            {{ \Carbon\Carbon::parse($route->estimated_arrival_time)->format('H:i') }} WIB (Estimasi)
                        </span>
                        <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $route->destination_city }}</h3>
                        <p class="text-sm text-slate-500">{{ $route->destination_terminal }}</p>
                    </div>
                </div>
            </div>

            {{-- Summary Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 rounded-xl bg-slate-50 p-5 border border-slate-100">
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-400">Tarif per Penumpang</p>
                    <p class="mt-1 text-lg font-bold text-slate-900">Rp {{ number_format($route->price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-400">Sisa Kursi</p>
                    <p class="mt-1 text-lg font-bold text-slate-900">{{ $route->available_seats }} <span class="text-xs font-normal text-slate-500">/ {{ $route->bus->total_seats }}</span></p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-400">Status Rute</p>
                    <div class="mt-1">
                        <x-status-badge :status="$route->status" type="route" />
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-400">Armada Ditugaskan</p>
                    <p class="mt-1 text-sm font-bold text-slate-900 truncate">{{ $route->bus->bus_name }}</p>
                </div>
            </div>
        </div>

        {{-- Bus Info Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900">Unit Bus Operasional</h3>
                <a href="{{ route('admin.buses.show', $route->bus) }}" class="text-xs font-semibold text-amber-700 hover:text-amber-900">
                    Lihat Unit Bus &rarr;
                </a>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 font-bold text-slate-700 text-sm border border-slate-200">
                    {{ strtoupper(substr($route->bus->bus_name, 0, 2)) }}
                </div>
                <div>
                    <h4 class="font-bold text-slate-900">{{ $route->bus->bus_name }}</h4>
                    <p class="text-xs text-slate-500">
                        Kode: <span class="font-mono text-slate-700 font-semibold">{{ $route->bus->bus_code }}</span> ·
                        Plat: <span class="font-mono text-slate-700 font-semibold">{{ $route->bus->plate_number ?: '-' }}</span> ·
                        Kelas: <span class="capitalize text-slate-700 font-semibold">{{ str_replace('_', ' ', $route->bus->bus_type) }}</span>
                    </p>
                </div>
            </div>

            @if(!empty($route->bus->facilities))
                <div class="pt-3 border-t border-slate-100">
                    <p class="text-xs font-semibold text-slate-500 mb-2">Fasilitas Bus:</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($route->bus->facilities as $facility)
                            <span class="rounded bg-slate-100 px-2 py-0.5 text-xs text-slate-700 font-medium">{{ $facility }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
