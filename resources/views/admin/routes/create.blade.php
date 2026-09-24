<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('admin.routes.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition mb-2">
                &larr; Kembali ke daftar rute
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tambah Rute</h1>
            <p class="mt-1 text-sm text-slate-500">Jadwalkan perjalanan baru untuk rute antarkota PO CAN Travel.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-7 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.routes.store') }}">
            @include('admin.routes.form')
        </form>
    </div>
</x-app-layout>
