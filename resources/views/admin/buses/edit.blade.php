<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('admin.buses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition mb-2">
                    &larr; Kembali ke daftar bus
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ isset($bus) ? 'Edit Unit Armada' : 'Tambah Bus' }}
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    {{ isset($bus) ? 'Perbarui data spesifikasi dan status operasional bus ' . $bus->bus_name : 'Daftarkan unit bus baru ke dalam armada PO CAN Travel.' }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-7 sm:px-6 lg:px-8">
        <form
            action="{{ isset($bus) ? route('admin.buses.update', $bus) : route('admin.buses.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf
            @if (isset($bus))
                @method('PUT')
            @endif

            {{-- 1. Identitas Bus --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900">Identitas Kendaraan</h2>
                    <p class="text-xs text-slate-500">Informasi utama unit bus untuk identifikasi tiket & jadwal.</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="bus_code" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Kode Bus <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="bus_code"
                            name="bus_code"
                            value="{{ old('bus_code', $bus->bus_code ?? '') }}"
                            placeholder="Contoh: CAN-01"
                            class="w-full rounded-lg border-slate-300 text-sm font-mono focus:border-brand-500 focus:ring-brand-500"
                            required
                        >
                        @error('bus_code')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bus_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nama Bus <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="bus_name"
                            name="bus_name"
                            value="{{ old('bus_name', $bus->bus_name ?? '') }}"
                            placeholder="Contoh: CAN Royal Executive"
                            class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                            required
                        >
                        @error('bus_name')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bus_type" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Tipe / Kelas Bus <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="bus_type"
                            name="bus_type"
                            class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                            required
                        >
                            <option value="">Pilih kelas bus</option>
                            @foreach([
                                'economy' => 'Economy Class',
                                'executive' => 'Executive Class',
                                'vip' => 'VIP Class',
                                'super_vip' => 'Super VIP Class',
                            ] as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @selected(old('bus_type', $bus->bus_type ?? '') === $value)
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('bus_type')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="plate_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nomor Plat Polisi
                        </label>
                        <input
                            type="text"
                            id="plate_number"
                            name="plate_number"
                            value="{{ old('plate_number', $bus->plate_number ?? '') }}"
                            placeholder="Contoh: K 1234 AB"
                            class="w-full rounded-lg border-slate-300 text-sm font-mono uppercase focus:border-brand-500 focus:ring-brand-500"
                        >
                        @error('plate_number')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 2. Kapasitas & Status --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900">Kapasitas & Status Operasional</h2>
                    <p class="text-xs text-slate-500">Konfigurasi tempat duduk dan kesiapan armada beroperasi.</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="total_seats" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Total Kursi Penumpang <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            id="total_seats"
                            name="total_seats"
                            value="{{ old('total_seats', $bus->total_seats ?? 30) }}"
                            min="1"
                            max="100"
                            class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                            required
                        >
                        <p class="mt-1 text-xs text-slate-400">Standar bus antarkota: 28-36 kursi.</p>
                        @error('total_seats')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Status Operasional <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="status"
                            name="status"
                            class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                            required
                        >
                            <option value="active" @selected(old('status', $bus->status ?? 'active') === 'active')>Active (Siap Beroperasi)</option>
                            <option value="maintenance" @selected(old('status', $bus->status ?? '') === 'maintenance')>Maintenance (Dalam Perawatan)</option>
                            <option value="inactive" @selected(old('status', $bus->status ?? '') === 'inactive')>Inactive (Tidak Aktif)</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Fasilitas Checkboxes --}}
                <div class="pt-3 border-t border-slate-100">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Fasilitas Bus
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach(['AC', 'WiFi', 'Toilet', 'TV', 'Charger', 'Bagasi'] as $facility)
                            <label class="flex items-center gap-2.5 rounded-lg border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition text-sm text-slate-800">
                                <input
                                    type="checkbox"
                                    name="facilities[]"
                                    value="{{ $facility }}"
                                    class="rounded border-slate-300 text-brand-950 focus:ring-brand-500"
                                    @checked(in_array($facility, old('facilities', $bus->facilities ?? [])))
                                >
                                <span class="font-medium">{{ $facility }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('facilities')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- 3. Foto & Keterangan --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-base font-bold text-slate-900">Foto & Keterangan Tambahan</h2>
                    <p class="text-xs text-slate-500">Visual unit bus dan catatan fasilitas untuk penumpang.</p>
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Deskripsi / Catatan Bus
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Contoh: Unit Scania K360IB dengan suspensi udara, konfigurasi kursi 2-2..."
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                    >{{ old('description', $bus->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Foto Armada Bus
                    </label>
                    @if (isset($bus) && $bus->image)
                        <div class="mb-3 flex items-center gap-4 p-3 rounded-xl bg-slate-50 border border-slate-200">
                            <img
                                src="{{ asset('storage/' . $bus->image) }}"
                                alt="{{ $bus->bus_name }}"
                                class="h-20 w-32 rounded-lg border object-cover shadow-sm"
                            >
                            <div class="text-xs text-slate-600">
                                <p class="font-medium text-slate-900">Foto saat ini</p>
                                <p class="text-slate-500 mt-0.5">Unggah file baru jika ingin mengganti foto di atas.</p>
                            </div>
                        </div>
                    @endif
                    <input
                        type="file"
                        name="image"
                        accept="image/jpeg,image/png,image/webp"
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200 transition"
                    >
                    @error('image')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a
                    href="{{ route('admin.buses.index') }}"
                    class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="btn-primary min-h-10 px-6 py-2.5 text-sm font-semibold shadow-sm"
                >
                    {{ isset($bus) ? 'Simpan Perubahan' : 'Simpan Bus' }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
