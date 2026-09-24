@extends('customer.layouts.index')

@section('title', 'Pemesanan Tiket Bus — PO CAN Travel')

@section('content')
<div x-data="bookingForm({
        price: {{ (float) $route->price }},
        maxPassengers: 10,
        totalSeats: {{ (int) $route->bus->total_seats }},
        bookedSeats: @json($bookedSeats),
    })"
    class="mx-auto max-w-6xl space-y-6">

    {{-- Back Link & Heading --}}
    <div>
        <a href="{{ route('customer.trips.show', $route) }}" 
           class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-slate-900 transition mb-3">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Detail Perjalanan</span>
        </a>

        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
            Pemesanan Tiket Bus
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Pilih nomor kursi pada denah armada dan lengkapi data identitas setiap penumpang.
        </p>
    </div>

    {{-- 9. Stepper: 01 Perjalanan → 02 Kursi → 03 Penumpang → 04 Konfirmasi --}}
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-xs">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs font-bold">
            <div class="flex items-center justify-center gap-1.5 py-1 text-blue-700">
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-100 text-[11px]">1</span>
                <span>Perjalanan</span>
            </div>
            <div class="flex items-center justify-center gap-1.5 py-1 text-blue-700">
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-[11px]">2</span>
                <span>Pilih Kursi</span>
            </div>
            <div class="flex items-center justify-center gap-1.5 py-1 text-blue-700">
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-[11px]">3</span>
                <span>Data Penumpang</span>
            </div>
            <div class="flex items-center justify-center gap-1.5 py-1 text-slate-400">
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 text-[11px]">4</span>
                <span>Konfirmasi</span>
            </div>
        </div>
    </div>

    {{-- Main Two-Column Layout --}}
    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        {{-- Kiri: Form, Seat Map, Penumpang --}}
        <div class="space-y-6">
            <form method="POST" action="{{ route('customer.bookings.store') }}" @submit="prepareSubmit" class="space-y-6">
                @csrf
                <input type="hidden" name="route_id" value="{{ $route->id }}">

                {{-- Ringkasan Perjalanan Ringkas --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs space-y-4">
                    <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                        Detail Perjalanan Dipilih
                    </h2>
                    <div class="grid gap-4 sm:grid-cols-2 text-xs">
                        <div>
                            <span class="text-slate-400 font-semibold uppercase">Rute</span>
                            <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $route->origin_city }} &rarr; {{ $route->destination_city }}</p>
                            <p class="text-slate-500">{{ $route->origin_terminal }} &rarr; {{ $route->destination_terminal }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold uppercase">Jadwal Keberangkatan</span>
                            <p class="font-bold text-slate-900 text-sm mt-0.5">
                                {{ $route->departure_date->translatedFormat('l, d F Y') }}
                            </p>
                            <p class="text-blue-700 font-mono font-bold">
                                {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }} WIB
                            </p>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold uppercase">Armada Bus</span>
                            <p class="font-bold text-slate-900 mt-0.5">{{ $route->bus->bus_name }}</p>
                            <p class="text-slate-500 capitalize">{{ str_replace('_', ' ', $route->bus->bus_type) }} · {{ $route->bus->bus_code }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold uppercase">Tarif Tiket</span>
                            <p class="font-bold text-slate-900 font-mono mt-0.5 text-sm">
                                Rp {{ number_format((float) $route->price, 0, ',', '.') }} / orang
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 10. PILIH KURSI (Seat Map Bersih & Intuitif) --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs space-y-5">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Pilih Kursi</h2>
                            <p class="text-xs text-slate-500">Klik nomor kursi yang tersedia untuk memilih.</p>
                        </div>
                        <div class="rounded-md bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 border border-blue-200">
                            Terpilih: <span x-text="selectedSeats.length"></span> / <span x-text="passengers.length"></span> kursi
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="flex flex-wrap items-center justify-center gap-5 text-xs font-medium text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="h-4 w-4 rounded border border-slate-300 bg-white"></span>
                            <span>Tersedia</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-4 w-4 rounded border border-blue-600 bg-blue-600"></span>
                            <span>Dipilih</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-4 w-4 rounded border border-slate-300 bg-slate-200"></span>
                            <span>Sudah dipesan</span>
                        </div>
                    </div>

                    {{-- Bus Seat Map Container --}}
                    <div class="max-w-xs mx-auto">
                        <div class="rounded-2xl border-2 border-slate-300 bg-slate-50/50 p-4 space-y-4">
                            {{-- Depan Bus --}}
                            <div class="flex items-center justify-between border-b-2 border-dashed border-slate-300 pb-3 px-2">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pintu / Supir</span>
                                <span class="text-xs font-black uppercase tracking-wider text-blue-900 bg-blue-100 px-2 py-0.5 rounded">
                                    DEPAN BUS
                                </span>
                            </div>

                            {{-- Grid of Seats --}}
                            <div class="grid grid-cols-4 gap-2.5">
                                <template x-for="seat in seats" :key="seat">
                                    <button type="button"
                                            @click="selectSeat(seat)"
                                            :disabled="isBooked(seat)"
                                            :class="isBooked(seat)
                                                ? 'cursor-not-allowed border-slate-200 bg-slate-200 text-slate-400'
                                                : (isSelected(seat)
                                                    ? 'bg-blue-600 text-white border-blue-600 shadow-xs'
                                                    : 'bg-white text-slate-700 border-slate-300 hover:border-blue-600 hover:text-blue-600')"
                                            class="aspect-square rounded-lg border text-xs font-bold transition flex items-center justify-center"
                                            :title="isBooked(seat) ? 'Kursi sudah dipesan' : 'Kursi ' + seat">
                                        <span x-text="String(seat).padStart(2, '0')"></span>
                                    </button>
                                </template>
                            </div>

                            {{-- Belakang Bus --}}
                            <div class="text-center border-t-2 border-dashed border-slate-300 pt-3">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                    BELAKANG
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DATA PENUMPANG --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs space-y-5">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Data Penumpang</h2>
                            <p class="text-xs text-slate-500">Maksimal 10 penumpang per satu kali booking.</p>
                        </div>
                        <button type="button"
                                @click="addPassenger"
                                :disabled="passengers.length >= maxPassengers"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-blue-600 bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100 disabled:opacity-50 transition">
                            <span>+ Tambah Penumpang</span>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(passenger, index) in passengers" :key="passenger.key">
                            <div class="rounded-xl border border-slate-200 p-4 bg-slate-50/50 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-800">
                                        Penumpang <span x-text="index + 1"></span>
                                    </span>
                                    <button type="button" 
                                            @click="removePassenger(index)" 
                                            x-show="passengers.length > 1"
                                            class="text-xs font-semibold text-red-600 hover:text-red-800 transition">
                                        Hapus
                                    </button>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                                            Nama Lengkap Sesuai KTP/Identitas <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               :name="'passengers[' + index + '][name]'"
                                               x-model="passenger.name"
                                               required
                                               maxlength="255"
                                               placeholder="Contoh: Budi Santoso"
                                               class="w-full rounded-lg border-slate-300 py-2 px-3 text-xs focus:border-blue-600 focus:ring-blue-600">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                                            Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               :name="'passengers[' + index + '][phone]'"
                                               x-model="passenger.phone"
                                               required
                                               maxlength="20"
                                               placeholder="Contoh: 081234567890"
                                               class="w-full rounded-lg border-slate-300 py-2 px-3 text-xs focus:border-blue-600 focus:ring-blue-600">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                                            Email <span class="text-slate-400 font-normal">(opsional)</span>
                                        </label>
                                        <input type="email"
                                               :name="'passengers[' + index + '][email]'"
                                               x-model="passenger.email"
                                               maxlength="255"
                                               placeholder="budi@example.com"
                                               class="w-full rounded-lg border-slate-300 py-2 px-3 text-xs focus:border-blue-600 focus:ring-blue-600">
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                                            Nomor Kursi <span class="text-red-500">*</span>
                                        </label>
                                        <select :name="'passengers[' + index + '][seat_number]'"
                                                x-model="passenger.seat_number"
                                                required
                                                @change="syncSeats"
                                                class="w-full rounded-lg border-slate-300 py-2 px-3 text-xs focus:border-blue-600 focus:ring-blue-600 font-semibold">
                                            <option value="">Pilih nomor kursi</option>
                                            <template x-for="seat in availableSeatsFor(index)" :key="seat">
                                                <option :value="seat" x-text="'Kursi ' + String(seat).padStart(2, '0')"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Action Submit Desktop/Mobile --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs text-slate-500 block">Total Pembayaran</span>
                        <span class="text-2xl font-black text-slate-900 font-mono">
                            Rp <span x-text="formatRupiah(totalPrice)"></span>
                        </span>
                    </div>

                    <button type="submit"
                            :disabled="passengers.some(p => !p.seat_number)"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-6 py-3 text-xs font-bold text-white disabled:opacity-50 disabled:cursor-not-allowed shadow-xs transition">
                        <span>Lanjutkan ke Pembayaran</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        {{-- Kanan: Order Summary Sticky Sidebar --}}
        <aside class="h-fit lg:sticky lg:top-20">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs space-y-4">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                    Ringkasan Booking
                </h2>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Rute</span>
                        <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $route->origin_city }} &rarr; {{ $route->destination_city }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Tanggal & Jam</span>
                        <p class="font-bold text-slate-900 mt-0.5">{{ $route->departure_date->translatedFormat('d M Y') }} · {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }} WIB</p>
                    </div>

                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Armada</span>
                        <p class="font-bold text-slate-900 mt-0.5">{{ $route->bus->bus_name }}</p>
                    </div>

                    <div class="border-t border-slate-100 pt-3 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tarif per orang</span>
                            <span class="font-mono font-semibold text-slate-800">Rp {{ number_format((float) $route->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Jumlah Penumpang</span>
                            <span class="font-bold text-slate-900"><span x-text="passengers.length"></span> Orang</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Kursi Dipilih</span>
                            <span class="font-bold text-blue-700 font-mono" x-text="selectedSeats.join(', ') || '-'"></span>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-3 flex justify-between items-baseline">
                        <span class="text-sm font-bold text-slate-900">Total Biaya</span>
                        <span class="text-xl font-black text-slate-900 font-mono">
                            Rp <span x-text="formatRupiah(totalPrice)"></span>
                        </span>
                    </div>
                </div>

                <div class="rounded-lg bg-slate-50 p-3 text-[11px] text-slate-500 border border-slate-100">
                    Ketersediaan kursi akan dikunci dan diverifikasi oleh sistem saat booking dikonfirmasi.
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function bookingForm(config) {
        return {
            price: config.price,
            maxPassengers: config.maxPassengers,
            totalSeats: config.totalSeats,
            bookedSeats: config.bookedSeats.map(Number),

            passengers: [
                {
                    key: Date.now(),
                    name: '',
                    phone: '',
                    email: '',
                    seat_number: '',
                }
            ],

            seats: [],

            init() {
                this.seats = Array.from(
                    { length: this.totalSeats },
                    (_, index) => index + 1
                );
            },

            get selectedSeats() {
                return this.passengers
                    .map(passenger => Number(passenger.seat_number))
                    .filter(Boolean);
            },

            get totalPrice() {
                return this.price * this.passengers.length;
            },

            addPassenger() {
                if (this.passengers.length >= this.maxPassengers) {
                    return;
                }

                this.passengers.push({
                    key: Date.now() + Math.random(),
                    name: '',
                    phone: '',
                    email: '',
                    seat_number: '',
                });
            },

            removePassenger(index) {
                if (this.passengers.length <= 1) {
                    return;
                }

                this.passengers.splice(index, 1);
            },

            isSelected(seat) {
                return this.selectedSeats.includes(Number(seat));
            },

            isBooked(seat) {
                return this.bookedSeats.includes(Number(seat));
            },

            deselectSeat(seat) {
                const selectedSeat = String(seat);
                const passenger = this.passengers.find(
                    passenger => passenger.seat_number === selectedSeat
                );

                if (passenger) {
                    passenger.seat_number = '';
                }
            },

            availableSeatsFor(index) {
                const currentSeat = Number(this.passengers[index].seat_number);

                return this.seats.filter(seat => {
                    return !this.isBooked(seat)
                        && (!this.selectedSeats.includes(Number(seat))
                            || Number(seat) === currentSeat);
                });
            },

            selectSeat(seat) {
                if (this.isSelected(seat)) {
                    this.deselectSeat(seat);
                    return;
                }

                const target = this.passengers.find(
                    passenger => !passenger.seat_number
                ) || this.passengers[this.passengers.length - 1];

                target.seat_number = String(seat);
            },

            syncSeats() {
                this.passengers.forEach((passenger, index) => {
                    const duplicate = this.passengers.some(
                        (other, otherIndex) =>
                            otherIndex !== index &&
                            other.seat_number !== '' &&
                            other.seat_number === passenger.seat_number
                    );

                    if (duplicate) {
                        passenger.seat_number = '';
                    }
                });
            },

            prepareSubmit(event) {
                this.syncSeats();

                if (this.passengers.some(passenger => !passenger.seat_number)) {
                    event.preventDefault();
                    alert('Setiap penumpang harus memiliki nomor kursi.');
                    return;
                }

                if (new Set(this.selectedSeats).size !== this.passengers.length) {
                    event.preventDefault();
                    alert('Setiap penumpang harus menggunakan kursi yang berbeda.');
                }
            },

            formatRupiah(value) {
                return new Intl.NumberFormat('id-ID').format(value);
            }
        };
    }
</script>
@endpush
