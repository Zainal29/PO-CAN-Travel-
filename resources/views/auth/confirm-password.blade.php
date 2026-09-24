<x-guest-layout>
    <div>
        <p class="eyebrow">Keamanan Akun</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-brand-950">Konfirmasi Kata Sandi</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            Ini adalah area aman aplikasi. Harap masukkan kata sandi akun Anda sebelum melanjutkan.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 space-y-4">
            @csrf

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Kata Sandi" />
                <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <button type="submit" class="btn-primary w-full">
                Konfirmasi
            </button>
        </form>
    </div>
</x-guest-layout>
