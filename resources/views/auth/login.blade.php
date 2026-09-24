<x-guest-layout>
    <div class="rounded-2xl bg-white p-6 shadow-sm sm:p-8">
        <p class="text-sm font-semibold text-amber-600">SELAMAT DATANG</p>
        <h1 class="mt-2 text-2xl font-bold">Masuk ke akun Anda</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Gunakan email dan password yang telah terdaftar.</p>
        <x-auth-session-status class="mt-5" :status="session('status')" />
        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">@csrf
            <div><x-input-label for="email" value="Email" /><x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" /><x-input-error :messages="$errors->get('email')" class="mt-2" /></div>
            <div><div class="flex items-center justify-between"><x-input-label for="password" value="Password" />@if(Route::has('password.request'))<a href="{{ route('password.request') }}" class="text-xs font-semibold text-amber-700 hover:text-amber-800">Lupa password?</a>@endif</div><x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" /><x-input-error :messages="$errors->get('password')" class="mt-2" /></div>
            <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" class="rounded border-slate-300 text-amber-500 focus:ring-amber-500" name="remember"> Ingat saya di perangkat ini</label>
            <button class="w-full rounded-lg bg-slate-900 px-4 py-3 text-sm font-bold text-white hover:bg-slate-700">Masuk</button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-600">Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-amber-700 hover:text-amber-800">Daftar sebagai customer</a></p>
    </div>
</x-guest-layout>
