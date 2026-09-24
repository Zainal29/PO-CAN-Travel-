<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PO CAN Travel — Pesan tiket bus antarkota dengan jadwal terpercaya, pilihan kursi nyaman, dan harga transparan.">
    <title>{{ $settings['app_name'] ?? 'PO CAN Travel' }} — Pemesanan Tiket Bus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-800 antialiased">
    {{-- Header / Navigation --}}
    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
        <nav class="site-shell flex h-16 items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-bold tracking-tight text-brand-950">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-950 text-sm font-black text-amber-300 shadow-sm">C</span>
                <span class="text-base">{{ $settings['app_name'] ?? 'PO CAN Travel' }}</span>
            </a>

            <div class="flex items-center gap-2 text-sm font-semibold">
                <a href="{{ route('customer.trips.index') }}" class="hidden rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition sm:block">
                    Jadwal Bus
                </a>
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('customer.dashboard') }}" class="btn-primary min-h-9 px-4 py-1.5 text-xs shadow-sm">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary min-h-9 px-4 py-1.5 text-xs shadow-sm">
                        Daftar
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        {{-- Hero & Booking Search --}}
        <section class="border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white py-12 sm:py-16 lg:py-20">
            <div class="site-shell grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                {{-- Hero Copy --}}
                <div class="max-w-2xl space-y-4">
                    <p class="eyebrow text-amber-700">TIKET BUS RESMI & TERPERCAYA</p>
                    <h1 class="text-4xl font-extrabold tracking-tight text-brand-950 sm:text-5xl sm:leading-[1.12]">
                        Perjalanan Anda, dimulai dari jadwal yang tepat.
                    </h1>
                    <p class="text-base leading-relaxed text-slate-600 max-w-xl">
                        Cari rute perjalanan bus antarkota, pilih nomor kursi favorit Anda secara transparan, dan dapatkan e-ticket instan langsung dari PO CAN Travel.
                    </p>

                    <div class="pt-2 flex flex-wrap gap-4 text-xs font-semibold text-slate-500">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Pilihan Kursi Nyata
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            E-Ticket QR Code
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Armada Bus Executive
                        </span>
                    </div>
                </div>

                {{-- Search Box Card --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                    <div class="mb-5">
                        <h2 class="text-lg font-bold text-brand-950">Cari perjalanan</h2>
                        <p class="mt-1 text-xs text-slate-500">Tentukan rute dan tanggal keberangkatan Anda.</p>
                    </div>

                    <form method="GET" action="{{ route('customer.trips.index') }}" class="space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="origin_city" class="block text-xs font-semibold uppercase text-slate-700 mb-1.5">Kota Asal</label>
                                <input id="origin_city" name="origin_city" required placeholder="Contoh: Jepara" class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                            </div>
                            <div>
                                <label for="destination_city" class="block text-xs font-semibold uppercase text-slate-700 mb-1.5">Kota Tujuan</label>
                                <input id="destination_city" name="destination_city" required placeholder="Contoh: Semarang" class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                            </div>
                            <div>
                                <label for="departure_date" class="block text-xs font-semibold uppercase text-slate-700 mb-1.5">Tanggal Berangkat</label>
                                <input id="departure_date" name="departure_date" type="date" min="{{ today()->toDateString() }}" required class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                            </div>
                            <div>
                                <label for="passengers" class="block text-xs font-semibold uppercase text-slate-700 mb-1.5">Jumlah Penumpang</label>
                                <select id="passengers" name="passengers" class="w-full rounded-lg border-slate-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                                    <option value="1">1 Penumpang</option>
                                    <option value="2">2 Penumpang</option>
                                    <option value="3">3 Penumpang</option>
                                    <option value="4">4 Penumpang</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary w-full min-h-11 text-sm font-semibold shadow-sm mt-2">
                            Cari jadwal perjalanan
                        </button>
                    </form>
                </div>
            </div>
        </section>

        {{-- 3 Value Props --}}
        <section class="border-b border-slate-200 bg-white">
            <div class="site-shell grid sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-slate-200">
                @foreach([
                    ['01', 'Pilih Jadwal Tepat', 'Rute antarkota terjadwal dengan waktu keberangkatan dan estimasi tiba yang jelas.'],
                    ['02', 'Pilih Kursi Langsung', 'Denah kursi interaktif memungkinkan Anda memilih posisi duduk sebelum membayar.'],
                    ['03', 'E-Ticket & Boarding Mudah', 'Dapatkan QR code e-ticket langsung setelah pembayaran untuk check-in di terminal.']
                ] as [$number, $title, $copy])
                    <div class="py-8 sm:px-8 sm:first:pl-0 sm:last:pr-0">
                        <p class="font-mono text-xs font-bold text-amber-600">{{ $number }}</p>
                        <h3 class="mt-2 text-base font-bold text-slate-900">{{ $title }}</h3>
                        <p class="mt-1 text-xs leading-5 text-slate-500">{{ $copy }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Featured Routes Section --}}
        <section class="site-shell py-14 sm:py-20">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow text-amber-700">JADWAL TERDEKAT</p>
                    <h2 class="mt-1 text-3xl font-bold tracking-tight text-brand-950">Perjalanan yang dapat dipesan</h2>
                </div>
                <a href="{{ route('customer.trips.index') }}" class="text-xs font-semibold text-amber-700 hover:text-amber-900">
                    Lihat semua rute & jadwal &rarr;
                </a>
            </div>

            <div class="mt-8 divide-y divide-slate-200 border-y border-slate-200">
                @forelse($featuredRoutes as $route)
                    <div class="grid gap-4 py-5 sm:grid-cols-[100px_minmax(0,1fr)_auto] sm:items-center">
                        <div>
                            <p class="text-2xl font-bold tracking-tight text-brand-950 font-mono">
                                {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }}
                            </p>
                            <p class="mt-0.5 text-xs text-slate-500 font-medium">
                                {{ \Carbon\Carbon::parse($route->departure_date)->translatedFormat('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 font-bold text-slate-900">
                                <span>{{ $route->origin_city }}</span>
                                <span class="text-amber-500">&rarr;</span>
                                <span>{{ $route->destination_city }}</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $route->bus->bus_name }} · <span class="capitalize">{{ str_replace('_', ' ', $route->bus->bus_type) }}</span> · <span class="text-emerald-700 font-semibold">{{ $route->available_seats }} kursi tersedia</span>
                            </p>
                        </div>
                        <div class="flex items-center justify-between gap-5 sm:block sm:text-right">
                            <p class="text-lg font-bold text-brand-950">
                                Rp {{ number_format($route->price, 0, ',', '.') }}
                            </p>
                            <a href="{{ route('customer.trips.show', $route) }}" class="mt-1 inline-block text-xs font-bold text-amber-700 hover:text-amber-900">
                                Lihat Detail &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-sm text-slate-500">
                        Belum ada jadwal yang tersedia saat ini.
                    </div>
                @endforelse
            </div>
        </section>

        {{-- How to Book Section --}}
        <section id="cara-memesan" class="bg-slate-50 py-14 sm:py-20 border-t border-slate-200">
            <div class="site-shell">
                <p class="eyebrow text-amber-700">CARA BOOKING</p>
                <div class="mt-2 grid gap-8 lg:grid-cols-[0.8fr_1.2fr]">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-brand-950">Empat langkah mudah menuju perjalanan Anda.</h2>
                        <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                            Pemesanan tiket bus di PO CAN Travel dirancang cepat dan tanpa antrean di loket fisik.
                        </p>
                    </div>
                    <ol class="grid gap-6 sm:grid-cols-2">
                        @foreach([
                            'Cari rute berdasarkan kota asal, tujuan, dan tanggal keberangkatan.',
                            'Pilih jadwal perjalanan serta nomor kursi bus yang tersedia.',
                            'Lengkapi identitas penumpang dan periksa ringkasan pesanan.',
                            'Selesaikan pembayaran simulasi, lalu simpan e-ticket resmi Anda.'
                        ] as $step => $copy)
                            <li class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm space-y-2">
                                <span class="font-mono text-sm font-bold text-amber-600">0{{ $step + 1 }}</span>
                                <p class="text-sm font-medium leading-relaxed text-slate-900">{{ $copy }}</p>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </section>

        {{-- Cancellation Policy Section --}}
        <section id="kebijakan-pembatalan" class="site-shell grid gap-8 py-14 sm:py-20 lg:grid-cols-2 items-center border-t border-slate-200">
            <div>
                <p class="eyebrow text-amber-700">INFORMASI & KEBIJAKAN</p>
                <h2 class="mt-1 text-3xl font-bold tracking-tight text-brand-950">
                    Rencanakan perjalanan dengan informasi yang transparan.
                </h2>
                <p class="mt-4 text-sm text-slate-600 leading-relaxed">
                    Setelah pembayaran berhasil terverifikasi, e-ticket digital dapat langsung ditunjukkan kepada petugas saat proses boarding. Kami menyarankan penumpang untuk tiba di terminal setidaknya 30 menit sebelum jadwal keberangkatan.
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 sm:p-8 space-y-3">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800">Kebijakan Pembatalan</h3>
                <p class="text-xs leading-relaxed text-slate-600">
                    {{ $settings['cancellation_policy'] ?? 'Untuk pembatalan atau pertanyaan seputar pesanan tiket, silakan hubungi pusat bantuan kami.' }}
                </p>
            </div>
        </section>

        {{-- Ready to Travel CTA --}}
        <section class="bg-brand-950 py-12 text-white">
            <div class="site-shell flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-300">SIAP BERANGKAT?</p>
                    <h2 class="mt-1 text-2xl font-bold tracking-tight">Cari tiket perjalanan bus Anda sekarang.</h2>
                </div>
                <a href="{{ route('customer.trips.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-lg bg-amber-400 px-6 py-2.5 text-xs font-bold text-brand-950 hover:bg-amber-300 transition shadow-sm">
                    Cari Perjalanan
                </a>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer id="kontak" class="border-t border-slate-200 bg-white">
        <div class="site-shell grid gap-8 py-12 text-sm sm:grid-cols-3">
            <div>
                <div class="flex items-center gap-2.5 font-bold tracking-tight text-brand-950">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-950 text-xs font-black text-amber-300">C</span>
                    <span>{{ $settings['app_name'] ?? 'PO CAN Travel' }}</span>
                </div>
                <p class="mt-3 text-xs leading-relaxed text-slate-500">
                    {{ $settings['footer_address'] ?? 'Layanan transportasi bus antarkota dengan kenyamanan dan ketepatan waktu terdepan.' }}
                </p>
            </div>

            <div>
                <p class="font-bold text-slate-900">Tautan Layanan</p>
                <div class="mt-3 grid gap-2 text-xs text-slate-600">
                    <a href="{{ route('customer.trips.index') }}" class="hover:text-slate-900">Jadwal Perjalanan</a>
                    <a href="#cara-memesan" class="hover:text-slate-900">Cara Memesan</a>
                    <a href="#kebijakan-pembatalan" class="hover:text-slate-900">Kebijakan Pembatalan</a>
                    <a href="#kontak" class="hover:text-slate-900">Kontak</a>
                </div>
            </div>

            <div>
                <p class="font-bold text-slate-900">Hubungi Kami</p>
                <div class="mt-3 grid gap-2 text-xs text-slate-600">
                    <a href="mailto:{{ $settings['contact_email'] ?? 'support@pocantravel.com' }}" class="hover:text-slate-900">
                        {{ $settings['contact_email'] ?? 'support@pocantravel.com' }}
                    </a>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['contact_phone'] ?? '081234567890') }}" class="hover:text-slate-900">
                        {{ $settings['contact_phone'] ?? '081234567890' }}
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>