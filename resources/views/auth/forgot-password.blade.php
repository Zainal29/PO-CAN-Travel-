<x-guest-layout>
    <div>
        <p class="eyebrow">Pemulihan Akun</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-brand-950">Lupa kata sandi?</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            Tidak masalah. Masukkan alamat email akun PO CAN Travel Anda, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mt-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="Alamat Email" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <button type="submit" class="btn-primary w-full">
                Kirim Tautan Reset Kata Sandi
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">
            Ingat kata sandi Anda? <a href="{{ route('login') }}" class="font-bold text-amber-700 hover:text-amber-800">Kembali untuk masuk</a>
        </p>
    </div>
</x-guest-layout>
