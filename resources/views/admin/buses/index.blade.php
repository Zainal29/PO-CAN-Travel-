<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600">Manajemen Armada</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Armada Bus</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola informasi unit bus, kapasitas kursi, dan fasilitas operasional.</p>
            </div>
            <a href="{{ route('admin.buses.create') }}" class="btn-primary min-h-10 px-4 py-2 text-sm shadow-sm inline-flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Bus
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
                        <th class="px-6 py-4">Armada Bus</th>
                        <th class="px-6 py-4">Tipe & Kelas</th>
                        <th class="px-6 py-4">Nomor Plat</th>
                        <th class="px-6 py-4">Kapasitas</th>
                        <th class="px-6 py-4">Fasilitas</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($buses as $bus)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($bus->image)
                                        <img src="{{ asset('storage/' . $bus->image) }}" alt="{{ $bus->bus_name }}" class="h-12 w-14 rounded-lg object-cover border border-slate-200">
                                    @else
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 font-bold border border-slate-200 text-sm">
                                            {{ strtoupper(substr($bus->bus_name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $bus->bus_name }}</p>
                                        <p class="font-mono text-xs text-slate-500">{{ $bus->bus_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="capitalize text-slate-700 font-medium">{{ str_replace('_', ' ', $bus->bus_type) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block rounded border border-slate-300 bg-slate-50 px-2 py-0.5 font-mono text-xs font-semibold text-slate-800">{{ $bus->plate_number ?: '-' }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-900">
                                {{ $bus->total_seats }} Kursi
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 max-w-[200px]">
                                    @if(!empty($bus->facilities) && is_array($bus->facilities))
                                        @foreach(array_slice($bus->facilities, 0, 3) as $facility)
                                            <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[11px] font-medium text-slate-600">{{ $facility }}</span>
                                        @endforeach
                                        @if(count($bus->facilities) > 3)
                                            <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[11px] font-medium text-slate-500">+{{ count($bus->facilities) - 3 }}</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$bus->status" type="bus" />
                            </td>
                            <td class="px-6 py-4 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.buses.show', $bus) }}" class="rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition">
                                    Detail
                                </a>
                                <a href="{{ route('admin.buses.edit', $bus) }}" class="rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.buses.destroy', $bus) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bus {{ $bus->bus_name }}?')">
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
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    </div>
                                    <h3 class="mt-3 text-sm font-bold text-slate-900">Belum ada armada bus</h3>
                                    <p class="mt-1 text-xs text-slate-500">Mulai tambahkan unit bus pertama untuk membuka rute perjalanan.</p>
                                    <div class="mt-4">
                                        <a href="{{ route('admin.buses.create') }}" class="btn-primary min-h-9 px-3 py-1.5 text-xs">Tambah Bus Sekarang</a>
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
            @forelse($buses as $bus)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            @if($bus->image)
                                <img src="{{ asset('storage/' . $bus->image) }}" alt="{{ $bus->bus_name }}" class="h-12 w-12 rounded-lg object-cover border border-slate-200">
                            @else
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 font-bold text-slate-700 border border-slate-200 text-sm">
                                    {{ strtoupper(substr($bus->bus_name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-slate-900">{{ $bus->bus_name }}</h3>
                                <p class="font-mono text-xs text-slate-500">{{ $bus->bus_code }} · {{ $bus->plate_number ?: '-' }}</p>
                            </div>
                        </div>
                        <x-status-badge :status="$bus->status" type="bus" />
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs border-y border-slate-100 py-3">
                        <div>
                            <span class="text-slate-500">Tipe Kelas</span>
                            <p class="font-semibold text-slate-900 capitalize">{{ str_replace('_', ' ', $bus->bus_type) }}</p>
                        </div>
                        <div>
                            <span class="text-slate-500">Kapasitas</span>
                            <p class="font-semibold text-slate-900">{{ $bus->total_seats }} Kursi</p>
                        </div>
                    </div>

                    @if(!empty($bus->facilities) && is_array($bus->facilities))
                        <div class="flex flex-wrap gap-1">
                            @foreach($bus->facilities as $facility)
                                <span class="rounded bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600">{{ $facility }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-100">
                        <a href="{{ route('admin.buses.show', $bus) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Detail
                        </a>
                        <a href="{{ route('admin.buses.edit', $bus) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Edit
                        </a>
                        <form action="{{ route('admin.buses.destroy', $bus) }}" method="POST" class="inline" onsubmit="return confirm('Hapus bus {{ $bus->bus_name }}?')">
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
                    Belum ada armada bus.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $buses->links() }}
        </div>
    </div>
</x-app-layout>