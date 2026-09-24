<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Manajemen Bus
            </h2>

            <a
                href="{{ route('admin.buses.create') }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg"
            >
                Tambah Bus
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-xl overflow-hidden">

                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">Kode</th>
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3 text-left">Tipe</th>
                            <th class="px-6 py-3 text-left">Plat</th>
                            <th class="px-6 py-3 text-left">Kursi</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($buses as $bus)
                            <tr class="border-t">

                                <td class="px-6 py-4">
                                    {{ $bus->bus_code }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $bus->bus_name }}
                                </td>

                                <td class="px-6 py-4 capitalize">
                                    {{ str_replace('_', ' ', $bus->bus_type) }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $bus->plate_number }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $bus->total_seats }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ ucfirst($bus->status) }}
                                </td>

                                <td class="px-6 py-4 text-right space-x-2">

                                    <a
                                        href="{{ route('admin.buses.show', $bus) }}"
                                        class="text-blue-600"
                                    >
                                        Detail
                                    </a>

                                    <a
                                        href="{{ route('admin.buses.edit', $bus) }}"
                                        class="text-indigo-600"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.buses.destroy', $bus) }}"
                                        method="POST"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-red-600"
                                            onclick="return confirm('Hapus bus ini?')"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td
                                    colspan="7"
                                    class="px-6 py-8 text-center text-gray-500"
                                >
                                    Belum ada data bus.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-6">
                    {{ $buses->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>