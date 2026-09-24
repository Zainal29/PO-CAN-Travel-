<section>
    <header class="border-b border-slate-100 pb-3">
        <h2 class="text-base font-bold text-slate-900">
            Informasi Profil
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            Perbarui data diri akun Anda dan alamat email aktif.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <x-input-label for="name" value="Nama Lengkap" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" value="Alamat Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-1.5" :messages="$errors->get('email')" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-xs">
                <p class="text-blue-800">
                    Alamat email Anda belum diverifikasi.
                    <button form="send-verification" class="font-bold underline hover:text-blue-950 ml-1">
                        Klik di sini untuk mengirim ulang email verifikasi.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-semibold text-emerald-700">
                        Tautan verifikasi baru telah dikirimkan ke alamat email Anda.
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn-primary min-h-10 px-5 text-xs font-semibold">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-semibold text-emerald-700"
                >Berhasil disimpan.</p>
            @endif
        </div>
    </form>
</section>
