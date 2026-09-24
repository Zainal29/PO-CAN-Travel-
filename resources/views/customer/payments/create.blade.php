{{-- resources/views/customer/payments/create.blade.php --}}
<x-guest-layout title="Pembayaran">
    {{-- Header with trip overview --}}
    <section class="bg-brand-950 text-white py-8 px-4 md:px-12 rounded-b-2xl">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl font-semibold">Pembayaran Tiket</h1>
                <p class="text-sm opacity-80">
                    {{ $order->route->origin_city }} → {{ $order->route->destination_city }}
                </p>
                <p class="text-sm opacity-80">
                    {{ $order->route->departure_date->format('d M Y') }}
                    {{ $order->route->departure_time }} – {{ $order->route->estimated_arrival_time }}
                </p>
            </div>

            <div class="flex items-center gap-2 text-sm">
                <x-status-badge :status="$order->payment_status"/>
                <span class="opacity-70">Total: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></span>
            </div>
        </div>
    </section>

    {{-- Payment form --}}
    <section class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg -mt-8 md:-mt-12 p-6 md:p-10">
        <form method="POST" action="{{ route('customer.payments.store', $order) }}">
            @csrf
            <div class="grid gap-6 md:grid-cols-2">
                {{-- Payment method selector --}}
                <div>
                    <label for="payment_method" class="block font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <select id="payment_method" name="payment_method"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500"
                            required>
                        <option value="">Pilih metode</option>
                        <option value="bca">BCA</option>
                        <option value="bri">BRI</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                {{-- Payer name --}}
                <div>
                    <label for="payer_name" class="block font-medium text-gray-700 mb-1">Nama Pembayar</label>
                    <input type="text" id="payer_name" name="payer_name"
                           class="block w-full border-gray-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500"
                           maxlength="100" required>
                </div>

                {{-- Payer phone --}}
                <div>
                    <label for="payer_phone" class="block font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="tel" id="payer_phone" name="payer_phone"
                           class="block w-full border-gray-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500"
                           pattern="[0-9]{10,13}" required>
                </div>

                {{-- Simulation notice --}}
                <div class="col-span-2 flex items-start text-sm text-gray-600">
                    <svg class="w-5 h-5 mr-2 mt-0.5 text-amber-500 flex-shrink-0"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round"
                         stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8V5m0 14a9 9 0 110-18 9 9 0 010 18z"/></svg>
                    <p>
                        <strong>simulasi pembayaran internal:</strong> pembayaran ini tidak terhubung ke bank nyata.
                        Hanya untuk demonstrasi aplikasi.
                    </p>
                </div>
            </div>

            {{-- CTA --}}
            <div class="mt-8 flex justify-end">
                <button type="submit"
                        class="px-6 py-3 bg-amber-600 text-white rounded-2xl hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition">
                    Bayar Sekarang
                </button>
            </div>
        </form>
    </section>
</x-guest-layout>
