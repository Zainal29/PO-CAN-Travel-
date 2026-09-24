<x-guest-layout>
    <div>
        <p class="eyebrow">Verifikasi Akun</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-brand-950">Verifikasi Email Anda</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            Terima kasih telah mendaftar di PO CAN Travel! Sebelum memulai pemesanan tiket, silakan verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan ke email Anda.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mt-4 rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-800 font-medium">
                Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda daftarkan.
            </div>
        @endif

        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary w-full sm:w-auto">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-semibold text-slate-600 hover:text-brand-950 underline">
                    Keluar Akun
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
