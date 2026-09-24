<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600">Direktori Pengguna</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Data Pelanggan</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola akun customer, riwayat kontak, dan aktivitas pemesanan tiket.</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8 space-y-6">
        {{-- Search Bar --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama, email, atau nomor telepon..."
                        class="w-full rounded-lg border-slate-300 pl-10 text-sm focus:border-brand-500 focus:ring-brand-500"
                    >
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary min-h-10 px-5 text-xs font-semibold">
                        Cari Pelanggan
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.customers.index') }}" class="btn-secondary min-h-10 px-3 text-xs" title="Reset">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50/75 text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Nama Pelanggan</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Nomor Telepon</th>
                        <th class="px-6 py-4">Total Pemesanan</th>
                        <th class="px-6 py-4">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 font-bold text-slate-700 text-xs border border-slate-200">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </div>
                                    <p class="font-bold text-slate-900">{{ $customer->name }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                <a href="mailto:{{ $customer->email }}" class="hover:text-slate-900 hover:underline">
                                    {{ $customer->email }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-slate-700">{{ $customer->phone ?: '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-bold text-amber-800 border border-amber-200">
                                    {{ $customer->orders_count }} Pesanan
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $customer->created_at ? $customer->created_at->translatedFormat('d M Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">
                                Tidak ada data pelanggan yang sesuai dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards View --}}
        <div class="md:hidden space-y-4">
            @forelse($customers as $customer)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 font-bold text-slate-700 text-xs border border-slate-200">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">{{ $customer->name }}</h3>
                                <p class="text-xs text-slate-500">{{ $customer->email }}</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-amber-50 px-2 py-0.5 text-xs font-bold text-amber-800 border border-amber-200">
                            {{ $customer->orders_count }} Order
                        </span>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Telepon: <strong class="text-slate-800">{{ $customer->phone ?: '-' }}</strong></span>
                        <span>Bergabung: {{ $customer->created_at ? $customer->created_at->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500">
                    Tidak ada data pelanggan yang sesuai dengan pencarian.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    </div>
</x-app-layout>
