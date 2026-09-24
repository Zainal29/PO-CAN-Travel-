@csrf
@if(isset($route))
    @method('PUT')
@endif

<div class="space-y-6">
    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-bold mb-1">Periksa kembali data formulir rute:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 1. Penugasan Armada Bus --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900">Armada yang Ditugaskan</h2>
            <p class="text-xs text-slate-500">Pilih armada bus aktif untuk melayani rute perjalanan ini.</p>
        </div>

        <div>
            <label for="bus_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                Armada Bus <span class="text-red-500">*</span>
            </label>
            <select
                id="bus_id"
                name="bus_id"
                class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                required
            >
                <option value="">Pilih armada bus</option>
                @foreach($buses as $bus)
                    <option
                        value="{{ $bus->id }}"
                        @selected(old('bus_id', $route->bus_id ?? '') == $bus->id)
                    >
                        {{ $bus->bus_name }} ({{ $bus->bus_code }}) · {{ $bus->plate_number ?: 'Tanpa Plat' }} · {{ $bus->total_seats }} Kursi · {{ ucfirst(str_replace('_', ' ', $bus->bus_type)) }}
                    </option>
                @endforeach
            </select>
            @error('bus_id')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- 2. Asal dan Tujuan --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
        <div class="border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900">Rute & Titik Terminal</h2>
            <p class="text-xs text-slate-500">Kota dan terminal titik awal pemberangkatan serta tujuan akhir.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="origin_city" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Kota Asal <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="origin_city"
                    name="origin_city"
                    value="{{ old('origin_city', $route->origin_city ?? '') }}"
                    placeholder="Contoh: Jepara"
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                    required
                >
                @error('origin_city')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="origin_terminal" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Terminal Asal (Titik Naik) <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="origin_terminal"
                    name="origin_terminal"
                    value="{{ old('origin_terminal', $route->origin_terminal ?? '') }}"
                    placeholder="Contoh: Terminal Jepara"
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                    required
                >
                @error('origin_terminal')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="destination_city" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Kota Tujuan <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="destination_city"
                    name="destination_city"
                    value="{{ old('destination_city', $route->destination_city ?? '') }}"
                    placeholder="Contoh: Semarang"
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                    required
                >
                @error('destination_city')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="destination_terminal" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Terminal Tujuan (Titik Turun) <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="destination_terminal"
                    name="destination_terminal"
                    value="{{ old('destination_terminal', $route->destination_terminal ?? '') }}"
                    placeholder="Contoh: Terminal Terboyo"
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                    required
                >
                @error('destination_terminal')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- 3. Tanggal & Waktu --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
        <div class="border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900">Jadwal Keberangkatan & Kedatangan</h2>
            <p class="text-xs text-slate-500">Tentukan tanggal dan estimasi waktu tempuh perjalanan.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            <div>
                <label for="departure_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Tanggal Keberangkatan <span class="text-red-500">*</span>
                </label>
                <input
                    type="date"
                    id="departure_date"
                    name="departure_date"
                    value="{{ old('departure_date', isset($route) ? $route->departure_date->format('Y-m-d') : '') }}"
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                    required
                >
                @error('departure_date')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="departure_time" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Waktu Berangkat <span class="text-red-500">*</span>
                </label>
                <input
                    type="time"
                    id="departure_time"
                    name="departure_time"
                    value="{{ old('departure_time', isset($route) ? substr($route->departure_time, 0, 5) : '') }}"
                    class="w-full rounded-lg border-slate-300 text-sm font-mono focus:border-brand-500 focus:ring-brand-500"
                    required
                >
                @error('departure_time')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="estimated_arrival_time" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Estimasi Tiba <span class="text-red-500">*</span>
                </label>
                <input
                    type="time"
                    id="estimated_arrival_time"
                    name="estimated_arrival_time"
                    value="{{ old('estimated_arrival_time', isset($route) ? substr($route->estimated_arrival_time, 0, 5) : '') }}"
                    class="w-full rounded-lg border-slate-300 text-sm font-mono focus:border-brand-500 focus:ring-brand-500"
                    required
                >
                @error('estimated_arrival_time')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- 4. Harga & Kapasitas Kursi --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
        <div class="border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900">Tarif Tiket & Alokasi Kursi</h2>
            <p class="text-xs text-slate-500">Harga per kursi dan status pembukaan pemesanan tiket.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            <div>
                <label for="price" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Harga Tiket (Rp) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-bold text-slate-400">Rp</span>
                    <input
                        type="number"
                        id="price"
                        min="0"
                        name="price"
                        value="{{ old('price', $route->price ?? '') }}"
                        placeholder="75000"
                        class="w-full rounded-lg border-slate-300 pl-9 text-sm focus:border-brand-500 focus:ring-brand-500"
                        required
                    >
                </div>
                @error('price')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="available_seats" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Kursi Tersedia <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    id="available_seats"
                    min="0"
                    name="available_seats"
                    value="{{ old('available_seats', $route->available_seats ?? '') }}"
                    placeholder="30"
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                    required
                >
                <p class="mt-1 text-xs text-slate-400">Tidak boleh melebihi kapasitas bus.</p>
                @error('available_seats')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Status Rute <span class="text-red-500">*</span>
                </label>
                <select
                    id="status"
                    name="status"
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                    required
                >
                    @foreach(['available' => 'Tersedia (Dibuka)', 'full' => 'Penuh', 'cancelled' => 'Dibatalkan'] as $status => $label)
                        <option
                            value="{{ $status }}"
                            @selected(old('status', $route->status ?? 'available') === $status)
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3 pt-2">
        <a
            href="{{ route('admin.routes.index') }}"
            class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition"
        >
            Batal
        </a>
        <button
            type="submit"
            class="btn-primary min-h-10 px-6 py-2.5 text-sm font-semibold shadow-sm"
        >
            {{ isset($route) ? 'Simpan Perubahan' : 'Simpan Rute' }}
        </button>
    </div>
</div>
