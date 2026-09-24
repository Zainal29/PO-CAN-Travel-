<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Detail Bus</h2></x-slot>
    <div class="py-6"><div class="max-w-4xl mx-auto px-6"><div class="rounded-xl bg-white p-6 shadow space-y-3">
        <h3 class="text-xl font-bold">{{ $bus->bus_name }} ({{ $bus->bus_code }})</h3>
        <p>Plat: {{ $bus->plate_number }} · Kapasitas: {{ $bus->total_seats }} kursi · Status: {{ ucfirst($bus->status) }}</p>
        <p>Tipe: {{ ucfirst(str_replace('_', ' ', $bus->bus_type)) }}</p>
        <p>Fasilitas: {{ implode(', ', $bus->facilities ?? []) ?: '-' }}</p>
        <p>{{ $bus->description ?: '-' }}</p>
        <a class="text-indigo-600" href="{{ route('admin.buses.index') }}">Kembali</a>
    </div></div></div>
</x-app-layout>
