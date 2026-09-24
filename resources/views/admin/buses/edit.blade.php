<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ isset($bus) ? 'Edit Bus' : 'Tambah Bus' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-6">

            <form
                action="{{ isset($bus) ? route('admin.buses.update', $bus) : route('admin.buses.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="bg-white p-6 rounded-xl shadow space-y-5"
            >
                @csrf
                @if (isset($bus))
                    @method('PUT')
                @endif

                <div>
                    <label>Kode Bus</label>
                    <input
                        type="text"
                        name="bus_code"
                        value="{{ old('bus_code', $bus->bus_code ?? '') }}"
                        placeholder="BUS-001"
                        class="w-full rounded-lg border-gray-300"
                    >
                    @error('bus_code')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label>Nama Bus</label>
                    <input
                        type="text"
                        name="bus_name"
                        value="{{ old('bus_name', $bus->bus_name ?? '') }}"
                        class="w-full rounded-lg border-gray-300"
                    >
                    @error('bus_name')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label>Tipe Bus</label>
                    <select
                        name="bus_type"
                        class="w-full rounded-lg border-gray-300"
                    >
                        <option value="">Pilih tipe</option>

                        @foreach([
                            'economy' => 'Economy',
                            'executive' => 'Executive',
                            'vip' => 'VIP',
                            'super_vip' => 'Super VIP',
                        ] as $value => $label)

                            <option
                                value="{{ $value }}"
                                @selected(old('bus_type', $bus->bus_type ?? '') === $value)
                            >
                                {{ $label }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Nomor Plat</label>
                    <input
                        type="text"
                        name="plate_number"
                        value="{{ old('plate_number', $bus->plate_number ?? '') }}"
                        placeholder="K 1234 AB"
                        class="w-full rounded-lg border-gray-300"
                    >
                </div>

                <div>
                    <label>Total Kursi</label>
                    <input
                        type="number"
                        name="total_seats"
                        value="{{ old('total_seats', $bus->total_seats ?? 30) }}"
                        min="1"
                        max="100"
                        class="w-full rounded-lg border-gray-300"
                    >
                </div>

                <div>
                    <label>Fasilitas</label>

                    <div class="grid grid-cols-2 gap-2 mt-2">
                        @foreach(['AC', 'WiFi', 'Toilet', 'TV', 'Charger', 'Bagasi'] as $facility)

                            <label class="flex gap-2">
                                <input
                                    type="checkbox"
                                    name="facilities[]"
                                    value="{{ $facility }}"
                                @checked(in_array($facility, old('facilities', $bus->facilities ?? [])))
                                >

                                {{ $facility }}
                            </label>

                        @endforeach
                    </div>
                </div>

                <div>
                    <label>Deskripsi</label>

                    <textarea
                        name="description"
                        rows="4"
                        class="w-full rounded-lg border-gray-300"
                    >{{ old('description', $bus->description ?? '') }}</textarea>
                </div>

                <div>
                    <label>Gambar Bus</label>

                    @if (isset($bus) && $bus->image)
                        <img
                            src="{{ asset('storage/' . $bus->image) }}"
                            alt="{{ $bus->bus_name }}"
                            class="mt-2 h-24 w-40 rounded-lg border object-cover"
                        >
                    @endif

                    <input
                        type="file"
                        name="image"
                        accept="image/jpeg,image/png,image/webp"
                        class="w-full"
                    >
                </div>

                <div>
                    <label>Status</label>

                    <select
                        name="status"
                        class="w-full rounded-lg border-gray-300"
                    >
                        <option value="active" @selected(old('status', $bus->status ?? 'active') === 'active')>Active</option>
                        <option value="maintenance" @selected(old('status', $bus->status ?? '') === 'maintenance')>Maintenance</option>
                        <option value="inactive" @selected(old('status', $bus->status ?? '') === 'inactive')>Inactive</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3">
                    <a
                        href="{{ route('admin.buses.index') }}"
                        class="px-4 py-2 border rounded-lg"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg"
                    >
                        {{ isset($bus) ? 'Simpan Perubahan' : 'Simpan' }}
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
