<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="eyebrow">Konfigurasi</p>
                <h1 class="mt-1 page-heading">Pengaturan Sistem</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola informasi dasar aplikasi, kontak, dan aturan bisnis.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-7 sm:px-6 lg:px-8 space-y-6">

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- 1. Informasi Aplikasi --}}
            <div class="settings-card">
                <h3 class="section-title mb-4">Informasi Aplikasi</h3>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="app_name" class="settings-label">Nama Aplikasi</label>
                        <input id="app_name" type="text" name="app_name"
                               value="{{ old('app_name', $data['app_name']) }}"
                               class="settings-input" required>
                        @error('app_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="payment_expiry_hours" class="settings-label">Batas Waktu Pembayaran (Jam)</label>
                        <input id="payment_expiry_hours" type="number" name="payment_expiry_hours"
                               value="{{ old('payment_expiry_hours', $data['payment_expiry_hours']) }}"
                               class="settings-input" required>
                        <p class="mt-1 text-xs text-slate-500">Order akan otomatis expired setelah batas waktu ini.</p>
                        @error('payment_expiry_hours')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 2. Hero Banner Beranda (Tampilan Utama Customer) --}}
            <div class="settings-card" x-data="{ imagePreview: null }">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="section-title">Hero Banner Beranda</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Kustomisasi teks headline dan foto latar belakang utama pada halaman beranda pengunjung.</p>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 border border-blue-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Tampilan Depan</span>
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="hero_badge" class="settings-label">Badge Label Hero</label>
                            <input id="hero_badge" type="text" name="hero_badge"
                                   value="{{ old('hero_badge', $data['hero_badge']) }}"
                                   placeholder="Contoh: Tiket Resmi Bus Antarkota"
                                   class="settings-input">
                            <p class="mt-1 text-xs text-slate-500">Teks label kecil di atas judul utama.</p>
                            @error('hero_badge')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="hero_title" class="settings-label">Judul Utama (Headline)</label>
                            <input id="hero_title" type="text" name="hero_title"
                                   value="{{ old('hero_title', $data['hero_title']) }}"
                                   placeholder="Contoh: Perjalanan Anda, dimulai dari jadwal yang tepat."
                                   class="settings-input" required>
                            @error('hero_title')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="hero_subtitle" class="settings-label">Deskripsi Singkat (Subtitle)</label>
                        <textarea id="hero_subtitle" name="hero_subtitle" rows="2"
                                  placeholder="Deskripsi singkat layanan di bawah headline hero..."
                                  class="settings-textarea">{{ old('hero_subtitle', $data['hero_subtitle']) }}</textarea>
                        @error('hero_subtitle')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="settings-label">Foto Background Hero Bus</label>
                        <div class="mt-2 flex flex-col sm:flex-row gap-5 items-start">
                            {{-- Preview Gambar --}}
                            <div class="relative w-full sm:w-64 h-36 rounded-xl border border-slate-200 bg-slate-100 overflow-hidden shrink-0 shadow-inner group">
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" alt="Preview Foto Hero Baru" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!imagePreview">
                                    <img src="{{ !empty($data['hero_image']) ? asset('storage/' . $data['hero_image']) : asset('images/hero-bus.jpg') }}" 
                                         alt="Foto Hero Saat Ini" 
                                         class="w-full h-full object-cover">
                                </template>
                                <div class="absolute inset-0 bg-slate-900/40 flex items-end p-2 opacity-0 group-hover:opacity-100 transition">
                                    <span class="text-[10px] text-white font-medium bg-black/60 px-2 py-0.5 rounded">Preview Background</span>
                                </div>
                            </div>

                            <div class="flex-1 space-y-2">
                                <input id="hero_image" type="file" name="hero_image"
                                       accept="image/jpeg,image/png,image/webp"
                                       @change="const file = $event.target.files[0]; if(file) { imagePreview = URL.createObjectURL(file); }"
                                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                <p class="text-xs text-slate-500">
                                    Format: JPG, PNG, atau WEBP. Maksimal 5MB. Rekomendasi rasio 16:9 (resolusi minimal 1280x720 piksel) agar gambar bus tampil jernih di semua ukuran layar monitor & smartphone.
                                </p>
                                @error('hero_image')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Informasi Kontak & Footer --}}
            <div class="settings-card">
                <h3 class="section-title mb-4">Informasi Kontak & Footer</h3>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="contact_email" class="settings-label">Email Kontak</label>
                        <input id="contact_email" type="email" name="contact_email"
                               value="{{ old('contact_email', $data['contact_email']) }}"
                               class="settings-input" required>
                        @error('contact_email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_phone" class="settings-label">Nomor Telepon / WhatsApp</label>
                        <input id="contact_phone" type="text" name="contact_phone"
                               value="{{ old('contact_phone', $data['contact_phone']) }}"
                               class="settings-input" required>
                        @error('contact_phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="footer_address" class="settings-label">Alamat (Untuk Footer)</label>
                        <textarea id="footer_address" name="footer_address" rows="2"
                                  class="settings-textarea" required>{{ old('footer_address', $data['footer_address']) }}</textarea>
                        @error('footer_address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 4. Kebijakan --}}
            <div class="settings-card">
                <h3 class="section-title mb-4">Kebijakan</h3>
                <div>
                    <label for="cancellation_policy" class="settings-label">Aturan Pembatalan</label>
                    <textarea id="cancellation_policy" name="cancellation_policy" rows="3"
                              class="settings-textarea" required>{{ old('cancellation_policy', $data['cancellation_policy']) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Teks ini akan ditampilkan kepada customer saat membatalkan pesanan.</p>
                    @error('cancellation_policy')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex justify-end pt-4">
                <button type="submit" class="settings-submit">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
