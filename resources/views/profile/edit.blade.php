<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Pengaturan Akun</p>
                <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Profil Saya</h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">Kelola identitas personal, nomor kontak, dan keamanan akun Anda.</p>
            </div>
            @if(auth()->user()->role === 'customer')
                <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-blue-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-blue-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Dashboard Admin
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            {{-- User Avatar Card --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col sm:flex-row sm:items-center gap-5">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-blue-600 font-extrabold text-2xl text-white shadow-sm ring-4 ring-blue-50">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-xl font-bold text-slate-900 truncate">{{ auth()->user()->name }}</h2>
                        <span class="rounded-full bg-blue-50 border border-blue-200/60 px-2.5 py-0.5 text-xs font-bold uppercase tracking-wide text-blue-700">
                            {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Customer' }}
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->phone)
                        <p class="text-xs font-mono text-slate-600 mt-1">Telp: {{ auth()->user()->phone }}</p>
                    @endif
                </div>
            </div>

            {{-- 1. Edit Information Form --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- 2. Update Password Form --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                @include('profile.partials.update-password-form')
            </div>

            {{-- 3. Delete Account Form --}}
            <div class="rounded-2xl border border-red-200 bg-white p-6 sm:p-8 shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
