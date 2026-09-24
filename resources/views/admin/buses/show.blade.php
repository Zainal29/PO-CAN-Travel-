<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('admin.buses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition mb-2">
                    &larr; Kembali ke daftar armada
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        {{ $bus->bus_name }}
                    </h1>
                    <x-status-badge :status="$bus->status" type="bus" />
                </div>
                <p class="mt-1 text-sm text-slate-500">
                    Kode Armada: <span class="font-mono font-semibold text-slate-800">{{ $bus->bus_code }}</span> · Plat Polisi: <span class="font-mono font-semibold text-slate-800">{{ $bus->plate_number ?: '-' }}</span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.buses.edit', $bus) }}" class="btn-primary min-h-10 px-4 py-2 text-sm shadow-sm inline-flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Bus
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-7 sm:px-6 lg:px-8 space-y-6">
        {{-- Overview Card --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @if($bus->image)
                <div class="h-64 w-full bg-slate-900 overflow-hidden relative">
                    <img src="{{ asset('storage/' . $bus->image) }}" alt="{{ $bus->bus_name }}" class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-6 text-white">
                        <span class="rounded bg-amber-400 px-2 py-0.5 text-xs font-bold text-slate-950 uppercase">{{ str_replace('_', ' ', $bus->bus_type) }}</span>
                        <h2 class="text-2xl font-bold mt-1 text-white">{{ $bus->bus_name }}</h2>
                    </div>
                </div>
            @endif

            <div class="p-6 sm:p-8 space-y-6">
                {{-- Specs Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 rounded-xl bg-slate-50 p-5 border border-slate-100">
                    <div>
                        <p class="text-xs font-semibold uppercase text-slate-400">Kelas / Tipe</p>
                        <p class="mt-1 text-base font-bold capitalize text-slate-900">{{ str_replace('_', ' ', $bus->bus_type) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-slate-400">Total Kursi</p>
                        <p class="mt-1 text-base font-bold text-slate-900">{{ $bus->total_seats }} Kursi</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-slate-400">Nomor Plat</p>
                        <p class="mt-1 font-mono text-base font-bold text-slate-900">{{ $bus->plate_number ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-slate-400">Status</p>
                        <div class="mt-1">
                            <x-status-badge :status="$bus->status" type="bus" />
                        </div>
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 mb-3">Fasilitas Armada</h3>
                    @if(!empty($bus->facilities) && is_array($bus->facilities))
                        <div class="flex flex-wrap gap-2">
                            @foreach($bus->facilities as $facility)
                                <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-800">
                                    <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    {{ $facility }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500 italic">Belum ada data fasilitas yang ditambahkan.</p>
                    @endif
                </div>

                {{-- Deskripsi --}}
                <div class="border-t border-slate-100 pt-5">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 mb-2">Deskripsi & Catatan</h3>
                    <p class="text-sm leading-relaxed text-slate-600">
                        {{ $bus->description ?: 'Tidak ada deskripsi tambahan untuk unit armada ini.' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Associated Routes --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-900 mb-4">Jadwal Perjalanan Unit Ini</h3>
            @if($bus->routes->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($bus->routes as $route)
                        <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <p class="font-bold text-slate-900">{{ $route->origin_city }} &rarr; {{ $route->destination_city }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $route->departure_date->format('d/m/Y') }} · {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($route->estimated_arrival_time)->format('H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-semibold text-slate-700">Rp {{ number_format($route->price, 0, ',', '.') }}</span>
                                <x-status-badge :status="$route->status" type="route" />
                                <a href="{{ route('admin.routes.show', $route) }}" class="text-xs font-semibold text-amber-700 hover:text-amber-900">Lihat Rute &rarr;</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-500 py-2">Belum ada rute perjalanan yang dijadwalkan menggunakan bus ini.</p>
            @endif
        </div>
    </div>
</x-app-layout>
