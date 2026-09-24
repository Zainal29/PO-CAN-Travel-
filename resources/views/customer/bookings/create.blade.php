@extends('customer.layouts.index')

@section('title', 'Pesan Tiket')

@section('content')

<div
    x-data="bookingForm({
        price: {{ (float) $route->price }},
        maxPassengers: 10,
        totalSeats: {{ (int) $route->bus->total_seats }},
        bookedSeats: @json($bookedSeats),
    })"
    class="mx-auto max-w-6xl space-y-6"
>
    {{-- Header --}}
    <div>
        <a
            href="{{ route('customer.trips.show', $route) }}"
            class="mb-3 inline-flex items-center text-sm font-semibold text-slate-600 hover:text-brand-950"
        >
            &larr; Kembali ke detail perjalanan
        </a>

    <p class="eyebrow">Checkout booking</p>
    <h1 class="mt-2 page-heading">
        Pesan Tiket
    </h1>

    <p class="mt-1 text-sm text-gray-600">
        Isi data penumpang dan pilih kursi yang tersedia.
    </p>
</div>

<ol class="grid grid-cols-4 gap-2 border-y border-slate-200 py-4 text-center text-xs font-semibold text-slate-500"><li class="text-brand-700">1. Perjalanan</li><li class="text-brand-700">2. Kursi & penumpang</li><li>3. Review</li><li>4. Pembayaran</li></ol>

{{-- Validation errors --}}
@if ($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 p-4">
        <div class="font-semibold text-red-800">
            Booking tidak dapat diproses.
        </div>

        <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main form --}}
    <div class="lg:col-span-2">
        <form
            method="POST"
            action="{{ route('customer.bookings.store') }}"
            @submit="prepareSubmit"
            class="space-y-6"
        >
            @csrf

            <input
                type="hidden"
                name="route_id"
                value="{{ $route->id }}"
            >

            {{-- Route summary --}}
            <div class="surface overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-900">
                        Detail Perjalanan
                    </h2>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Rute</p>
                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $route->origin_city }}
                                →
                                {{ $route->destination_city }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Tanggal</p>
                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $route->departure_date->translatedFormat('d F Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Keberangkatan</p>
                            <p class="mt-1 font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Bus</p>
                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $route->bus->bus_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Terminal Asal</p>
                            <p class="mt-1 text-gray-700">
                                {{ $route->origin_terminal }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Terminal Tujuan</p>
                            <p class="mt-1 text-gray-700">
                                {{ $route->destination_terminal }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Seat selection --}}
            <div class="surface overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="font-semibold text-gray-900">
                                Pilih Kursi
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">
                                Pilih satu kursi untuk setiap penumpang.
                            </p>
                        </div>

                        <div class="text-sm font-medium text-gray-700">
                            <span x-text="selectedSeats.length"></span>
                            / <span x-text="passengers.length"></span>
                        </div>
                    </div>
                </div>

                <div class="p-5">
                    <div class="flex items-center gap-4 text-xs text-gray-600 mb-5">
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded border border-gray-300 bg-white"></span>
                            Tersedia
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded border border-gray-900 bg-gray-900"></span>
                            Dipilih
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded border border-slate-300 bg-slate-200"></span>
                            Sudah dipesan
                        </div>
                    </div>

                    <div class="max-w-md mx-auto">
                        <div class="border border-gray-300 rounded-2xl p-4">
                            <div class="text-center text-xs text-gray-500 mb-4">
                                DEPAN BUS
                            </div>

                            <div class="grid grid-cols-4 gap-3">
                                <template
                                    x-for="seat in seats"
                                    :key="seat"
                                >
                                    <button
                                        type="button"
                                        @click="selectSeat(seat)"
                                        :disabled="isBooked(seat)"
                                        :class="isBooked(seat)
                                            ? 'cursor-not-allowed border-slate-200 bg-slate-200 text-slate-400'
                                            : (isSelected(seat)
                                                ? 'bg-gray-900 text-white border-gray-900'
                                                : 'bg-white text-gray-700 border-gray-300 hover:border-gray-900')"
                                        class="aspect-square rounded-lg border text-sm font-semibold transition"
                                        :title="isBooked(seat) ? 'Kursi sudah dipesan' : 'Kursi ' + seat"
                                    >
                                        <span x-text="String(seat).padStart(2, '0')"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <p class="mt-4 text-center text-sm text-gray-500">
                        Kursi akan divalidasi kembali oleh server saat booking dikirim.
                    </p>
                </div>
            </div>

            {{-- Passenger data --}}
            <div class="surface overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="font-semibold text-gray-900">
                                Data Penumpang
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">
                                Maksimal 10 penumpang dalam satu order.
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="addPassenger"
                            :disabled="passengers.length >= maxPassengers"
                            class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            + Tambah
                        </button>
                    </div>
                </div>

                <div class="p-5 space-y-5">
                    <template x-for="(passenger, index) in passengers" :key="passenger.key">
                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-900">
                                    Penumpang <span x-text="index + 1"></span>
                                </h3>

                                <button
                                    type="button"
                                    @click="removePassenger(index)"
                                    x-show="passengers.length > 1"
                                    class="text-sm font-medium text-red-600 hover:text-red-800"
                                >
                                    Hapus
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Lengkap
                                    </label>

                                    <input
                                        type="text"
                                        :name="'passengers[' + index + '][name]'"
                                        x-model="passenger.name"
                                        required
                                        maxlength="255"
                                        class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                        placeholder="Nama penumpang"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nomor Telepon
                                    </label>

                                    <input
                                        type="text"
                                        :name="'passengers[' + index + '][phone]'"
                                        x-model="passenger.phone"
                                        required
                                        maxlength="20"
                                        class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                        placeholder="08xxxxxxxxxx"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Email
                                        <span class="font-normal text-gray-400">(opsional)</span>
                                    </label>

                                    <input
                                        type="email"
                                        :name="'passengers[' + index + '][email]'"
                                        x-model="passenger.email"
                                        maxlength="255"
                                        class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                        placeholder="email@example.com"
                                    >
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Kursi
                                    </label>

                                    <select
                                        :name="'passengers[' + index + '][seat_number]'"
                                        x-model="passenger.seat_number"
                                        required
                                        @change="syncSeats"
                                        class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                    >
                                        <option value="">Pilih kursi</option>

                                        <template x-for="seat in availableSeatsFor(index)" :key="seat">
                                            <option
                                                :value="seat"
                                                x-text="'Kursi ' + String(seat).padStart(2, '0')"
                                            ></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Submit --}}
            <div class="surface p-5">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500">
                            Total pembayaran
                        </p>

                        <p class="text-2xl font-bold text-gray-900">
                            Rp <span x-text="formatRupiah(totalPrice)"></span>
                        </p>
                    </div>

                    <button
                        type="submit"
                        :disabled="passengers.some(passenger => !passenger.seat_number)"
                        class="inline-flex justify-center items-center rounded-lg bg-gray-900 px-6 py-3 text-sm font-semibold text-white hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Lanjutkan Booking
                    </button>
                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Harga pada halaman ini hanya untuk informasi. Total akhir dihitung kembali oleh server berdasarkan harga rute.
                </p>
            </div>
        </form>
    </div>

    {{-- Order summary --}}
    <aside class="lg:col-span-1">
        <div class="surface overflow-hidden lg:sticky lg:top-6">
            <div class="px-5 py-4 border-b border-gray-200">
                <h2 class="font-semibold text-gray-900">
                    Ringkasan Pesanan
                </h2>
            </div>

            <div class="p-5 space-y-4">
                <div>
                    <p class="text-xs text-gray-500">Rute</p>
                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $route->origin_city }}
                        →
                        {{ $route->destination_city }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Tanggal</p>
                    <p class="mt-1 text-sm text-gray-700">
                        {{ $route->departure_date->translatedFormat('d F Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Harga per penumpang</p>
                    <p class="mt-1 font-semibold text-gray-900">
                        Rp {{ number_format((float) $route->price, 0, ',', '.') }}
                    </p>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">
                            Penumpang
                        </span>

                        <span
                            class="font-semibold text-gray-900"
                            x-text="passengers.length"
                        ></span>
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <span class="text-sm text-gray-600">
                            Kursi dipilih
                        </span>

                        <span
                            class="font-semibold text-gray-900"
                            x-text="selectedSeats.length"
                        ></span>
                    </div>

                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                        <span class="font-semibold text-gray-900">
                            Total
                        </span>

                        <span class="text-lg font-bold text-gray-900">
                            Rp <span x-text="formatRupiah(totalPrice)"></span>
                        </span>
                    </div>
                </div>
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
                    alert('Setiap penumpang harus memiliki kursi.');
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
