<x-guest-layout>
    <div>
        <p class="eyebrow">Pemulihan Akun</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-brand-950">Atur ulang kata sandi</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            Buat kata sandi baru yang aman untuk akun PO CAN Travel Anda.
        </p>

        <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="Alamat Email" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Kata Sandi Baru" />
                <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi Baru" />
                <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <button type="submit" class="btn-primary w-full">
                Simpan Kata Sandi Baru
            </button>
        </form>
    </div>
</x-guest-layout>
