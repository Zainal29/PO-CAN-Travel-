<section class="space-y-6">
    <header class="border-b border-red-100 pb-3">
        <h2 class="text-base font-bold text-red-900">
            Hapus Akun
        </h2>
        <p class="mt-1 text-xs text-red-600">
            Setelah akun Anda dihapus, seluruh data pemesanan dan riwayat tiket akan dinonaktifkan secara permanen.
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex min-h-10 items-center justify-center rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 transition"
    >
        Hapus Akun Saya
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-900">
                Apakah Anda yakin ingin menghapus akun?
            </h2>

            <p class="text-xs leading-5 text-slate-600">
                Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi akun Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen.
            </p>

            <div class="mt-4">
                <x-input-label for="password" value="Kata Sandi" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full sm:w-3/4 text-sm"
                    placeholder="Masukkan kata sandi Anda untuk konfirmasi"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-2">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="btn-secondary min-h-10 px-4 text-xs font-semibold"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="inline-flex min-h-10 items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700 transition"
                >
                    Hapus Akun Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>
