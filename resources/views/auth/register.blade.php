<x-guest-layout>
    <div>
        <p class="eyebrow">Buat akun customer</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-brand-950">Pesan tiket lebih cepat</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Data ini digunakan untuk mengelola pesanan dan informasi perjalanan Anda.</p>
        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">@csrf
            <div><x-input-label for="name" value="Nama lengkap" /><x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" /><x-input-error :messages="$errors->get('name')" class="mt-2" /></div>
            <div><x-input-label for="email" value="Email" /><x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" /><x-input-error :messages="$errors->get('email')" class="mt-2" /></div>
            <div><x-input-label for="phone" value="Nomor telepon" /><x-text-input id="phone" class="mt-1 block w-full" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" /><x-input-error :messages="$errors->get('phone')" class="mt-2" /></div>
            <div><x-input-label for="password" value="Password" /><x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" /><x-input-error :messages="$errors->get('password')" class="mt-2" /></div>
            <div><x-input-label for="password_confirmation" value="Konfirmasi password" /><x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" /><x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" /></div>
            <button class="btn-primary w-full">Buat akun customer</button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-600">Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-amber-700 hover:text-amber-800">Masuk</a></p>
    </div>
</x-guest-layout>
