<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600">Jadwal Operasional</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Manajemen Rute Perjalanan</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola rute, jadwal keberangkatan, armada yang ditugaskan, dan harga tiket.</p>
            </div>
            <a href="{{ route('admin.routes.create') }}" class="btn-primary min-h-10 px-4 py-2 text-sm shadow-sm inline-flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Rute
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800 flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800 flex items-center gap-2">
                <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Desktop Table View --}}
        <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50/75 text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Rute Perjalanan</th>
                        <th class="px-6 py-4">Waktu & Jadwal</th>
                        <th class="px-6 py-4">Armada Bus</th>
                        <th class="px-6 py-4">Sisa Kursi</th>
                        <th class="px-6 py-4">Harga Tiket</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($routes as $route)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 text-sm font-bold text-slate-900">
                                        <span>{{ $route->origin_city }}</span>
                                        <span class="text-amber-500">&rarr;</span>
                                        <span>{{ $route->destination_city }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500">
                                        {{ $route->origin_terminal }} &rarr; {{ $route->destination_terminal }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-base font-bold font-mono text-slate-900">
                                            {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }}
                                        </span>
                                        <span class="text-xs text-slate-400">&rarr;</span>
                                        <span class="text-xs font-mono font-medium text-slate-600">
                                            {{ \Carbon\Carbon::parse($route->estimated_arrival_time)->format('H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        {{ $route->departure_date->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $route->bus->bus_name }}</p>
                                    <p class="text-xs text-slate-500 capitalize">{{ str_replace('_', ' ', $route->bus->bus_type) }} · {{ $route->bus->plate_number ?: '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold {{ $route->available_seats > 5 ? 'text-slate-900' : ($route->available_seats > 0 ? 'text-amber-700' : 'text-red-700') }}">
                                    {{ $route->available_seats }}
                                </span>
                                <span class="text-xs text-slate-400">/ {{ $route->bus->total_seats }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                Rp {{ number_format($route->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$route->status" type="route" />
                            </td>
                            <td class="px-6 py-4 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.routes.show', $route) }}" class="rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                    Detail
                                </a>
                                <a href="{{ route('admin.routes.edit', $route) }}" class="rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.routes.destroy', $route) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rute perjalanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md border border-red-200 bg-red-50/50 px-2.5 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    </div>
                                    <h3 class="mt-3 text-sm font-bold text-slate-900">Belum ada rute perjalanan</h3>
                                    <p class="mt-1 text-xs text-slate-500">Buat jadwal rute perjalanan baru untuk mulai menerima pesanan tiket.</p>
                                    <div class="mt-4">
                                        <a href="{{ route('admin.routes.create') }}" class="btn-primary min-h-9 px-3 py-1.5 text-xs">Tambah Rute Sekarang</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards View --}}
        <div class="md:hidden space-y-4">
            @forelse($routes as $route)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2 text-base font-bold text-slate-900">
                                <span>{{ $route->origin_city }}</span>
                                <span class="text-amber-500">&rarr;</span>
                                <span>{{ $route->destination_city }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ $route->origin_terminal }} &rarr; {{ $route->destination_terminal }}
                            </p>
                        </div>
                        <x-status-badge :status="$route->status" type="route" />
                    </div>

                    <div class="grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-3 text-xs">
                        <div>
                            <span class="text-slate-400">Jadwal Keberangkatan</span>
                            <p class="font-bold text-slate-900 mt-0.5">
                                {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }} · {{ $route->departure_date->translatedFormat('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <span class="text-slate-400">Harga Tiket</span>
                            <p class="font-bold text-slate-900 mt-0.5">
                                Rp {{ number_format($route->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>Bus: <strong class="text-slate-900">{{ $route->bus->bus_name }}</strong></span>
                        <span>Sisa: <strong class="text-slate-900">{{ $route->available_seats }} kursi</strong></span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <a href="{{ route('admin.routes.show', $route) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Detail
                        </a>
                        <a href="{{ route('admin.routes.edit', $route) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Edit
                        </a>
                        <form action="{{ route('admin.routes.destroy', $route) }}" method="POST" class="inline" onsubmit="return confirm('Hapus rute ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500">
                    Belum ada rute perjalanan.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $routes->links() }}
        </div>
    </div>
</x-app-layout>
