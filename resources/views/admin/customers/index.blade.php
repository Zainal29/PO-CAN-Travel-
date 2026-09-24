<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Data Customer</h2></x-slot>
    <div class="py-6"><div class="max-w-7xl mx-auto px-6 space-y-6">
        <form method="GET" class="flex gap-3">
            <input name="search" value="{{ request('search') }}" class="rounded-lg border-gray-300" placeholder="Cari nama, email, atau telepon">
            <button class="rounded-lg bg-indigo-600 px-4 py-2 text-white">Cari</button>
        </form>
        <div class="overflow-x-auto rounded-xl bg-white shadow"><table class="w-full text-left">
            <thead class="bg-gray-50"><tr><th class="px-6 py-3">Nama</th><th class="px-6 py-3">Email</th><th class="px-6 py-3">Telepon</th><th class="px-6 py-3">Jumlah Order</th></tr></thead>
            <tbody>@forelse($customers as $customer)<tr class="border-t"><td class="px-6 py-4">{{ $customer->name }}</td><td class="px-6 py-4">{{ $customer->email }}</td><td class="px-6 py-4">{{ $customer->phone ?: '-' }}</td><td class="px-6 py-4">{{ $customer->orders_count }}</td></tr>@empty<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada customer.</td></tr>@endforelse</tbody>
        </table><div class="p-6">{{ $customers->links() }}</div></div>
    </div></div>
</x-app-layout>
