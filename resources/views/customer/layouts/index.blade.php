
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PO CAN Travel')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="min-h-screen text-slate-800">

    <nav
        x-data="{ open: false }"
        class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur"
    >
        <div class="site-shell flex h-16 items-center justify-between">

            {{-- Brand --}}
            <a
                href="{{
                    auth()->check() && auth()->user()->role === 'customer'
                        ? route('customer.dashboard')
                        : route('home')
                }}"
                class="flex items-center gap-2.5 font-bold tracking-tight text-brand-950"
            >
                <span class="flex h-8 w-8 items-center justify-center rounded-md bg-brand-950 text-sm font-black text-amber-300">
                    C
                </span>

                <span>PO CAN Travel</span>
            </a>

            {{-- Mobile Button --}}
            <button
                @click="open = !open"
                :aria-expanded="open"
                aria-label="Buka menu"
                class="btn-secondary min-h-9 px-3 py-1.5 sm:hidden"
                type="button"
            >
                Menu
            </button>

            {{-- Desktop Navigation --}}
            <div class="hidden items-center gap-1 sm:flex">

                @guest

                    <a
                        href="{{ route('customer.trips.index') }}"
                        class="rounded-md px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                    >
                        Jadwal Bus
                    </a>

                    <a
                        href="{{ route('login') }}"
                        class="rounded-md px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                    >
                        Masuk
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="btn-primary min-h-9 px-3 py-1.5"
                    >
                        Daftar
                    </a>

                @else

                    @if(auth()->user()->role === 'customer')

                        <a
                            href="{{ route('customer.trips.index') }}"
                            class="rounded-md px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                        >
                            Jadwal Bus
                        </a>

                        <a
                            href="{{ route('customer.dashboard') }}"
                            class="rounded-md px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                        >
                            Beranda
                        </a>

                        <a
                            href="{{ route('customer.orders.index') }}"
                            class="rounded-md px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                        >
                            Pesanan
                        </a>

                        <a
                            href="{{ route('profile.edit') }}"
                            class="rounded-md px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                        >
                            Profil
                        </a>

                    @elseif(auth()->user()->role === 'admin')

                        <a
                            href="{{ route('customer.trips.index') }}"
                            class="rounded-md px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                        >
                            Jadwal Bus
                        </a>

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="btn-primary min-h-9 px-3 py-1.5"
                        >
                            Kembali ke Admin
                        </a>

                    @endif

                @endguest

            </div>
        </div>

        {{-- Mobile Navigation --}}
        <div
            x-show="open"
            x-cloak
            class="border-t border-slate-200 sm:hidden"
        >
            <div class="site-shell grid gap-1 py-3">

                {{-- Public --}}
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="rounded-md px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                >
                    Cari perjalanan
                </a>

                @guest

                    <a
                        href="{{ route('login') }}"
                        class="rounded-md px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                    >
                        Masuk
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="rounded-md px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                    >
                        Daftar
                    </a>

                @else

                    @if(auth()->user()->role === 'customer')

                        <a
                            href="{{ route('customer.dashboard') }}"
                            class="rounded-md px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                        >
                            Beranda
                        </a>

                        <a
                            href="{{ route('customer.orders.index') }}"
                            class="rounded-md px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                        >
                            Pesanan saya
                        </a>

                        <a
                            href="{{ route('profile.edit') }}"
                            class="rounded-md px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                        >
                            Profil
                        </a>

                    @elseif(auth()->user()->role === 'admin')

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="rounded-md px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                        >
                            Kembali ke Admin
                        </a>

                    @endif

                @endguest

            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="site-shell min-h-[calc(100vh-65px)] py-8 sm:py-10">

        @if(session('success'))
            <div
                role="status"
                class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
            >
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div
                role="alert"
                class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
            >
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div
                role="alert"
                class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
            >
                <ul class="list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-8 text-sm sm:px-6 md:grid-cols-3 lg:px-8">

            <div>
                <p class="font-bold text-slate-900">
                    {{ \App\Models\Setting::get('app_name', 'PO CAN Travel') }}
                </p>

                <p class="mt-2 text-slate-500">
                    {{ \App\Models\Setting::get('footer_address', 'Alamat belum tersedia.') }}
                </p>
            </div>

            <div>
                <p class="font-bold text-slate-900">
                    Tautan
                </p>

                <div class="mt-2 grid gap-1 text-slate-600">
                    <a
                        href="{{ route('home') }}#cara-memesan"
                        class="hover:text-slate-900"
                    >
                        Cara Memesan
                    </a>

                    <a
                        href="{{ route('home') }}#kebijakan-pembatalan"
                        class="hover:text-slate-900"
                    >
                        Kebijakan Pembatalan
                    </a>

                    <a
                        href="{{ route('home') }}#kontak"
                        class="hover:text-slate-900"
                    >
                        Kontak
                    </a>
                </div>
            </div>

            <div>
                <p class="font-bold text-slate-900">
                    Hubungi kami
                </p>

                <div class="mt-2 grid gap-1 text-slate-600">

                    <a
                        href="mailto:{{ \App\Models\Setting::get('contact_email', 'support@pocantravel.com') }}"
                        class="hover:text-slate-900"
                    >
                        {{ \App\Models\Setting::get('contact_email', 'support@pocantravel.com') }}
                    </a>

                    <a
                        href="tel:{{ \App\Models\Setting::get('contact_phone', '081234567890') }}"
                        class="hover:text-slate-900"
                    >
                        {{ \App\Models\Setting::get('contact_phone', '081234567890') }}
                    </a>

                </div>
            </div>

        </div>
    </footer>

    @stack('scripts')

</body>
</html>

