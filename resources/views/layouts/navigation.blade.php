<nav x-data="{ open: false }" class="border-b border-slate-800 bg-slate-950 text-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 font-bold"><div class="h-8 w-8 rounded-lg bg-white overflow-hidden flex items-center justify-center shrink-0 border border-slate-700"><img src="{{ asset('storage/images/LOGO-CAN-TRAVEL.jpeg') }}" alt="Logo PO CAN" class="h-full w-full object-cover scale-135" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"><span class="hidden h-full w-full items-center justify-center font-black text-blue-900 text-xs">C</span></div>PO CAN <span class="font-normal text-slate-400">Admin</span></a>
        <button @click="open = !open" class="rounded-lg border border-slate-700 px-3 py-2 text-sm sm:hidden">Menu</button>
        <div class="hidden items-center gap-1 sm:flex">
            <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800' : 'text-slate-300 hover:bg-slate-800' }}">Ringkasan</a>
            <a href="{{ route('admin.buses.index') }}" class="rounded-lg px-3 py-2 text-sm {{ request()->routeIs('admin.buses.*') ? 'bg-slate-800' : 'text-slate-300 hover:bg-slate-800' }}">Bus</a>
            <a href="{{ route('admin.routes.index') }}" class="rounded-lg px-3 py-2 text-sm {{ request()->routeIs('admin.routes.*') ? 'bg-slate-800' : 'text-slate-300 hover:bg-slate-800' }}">Perjalanan</a>
            <a href="{{ route('admin.orders.index') }}" class="rounded-lg px-3 py-2 text-sm {{ request()->routeIs('admin.orders.*') ? 'bg-slate-800' : 'text-slate-300 hover:bg-slate-800' }}">Order</a>
            <a href="{{ route('admin.payments.index') }}" class="rounded-lg px-3 py-2 text-sm {{ request()->routeIs('admin.payments.*') ? 'bg-slate-800' : 'text-slate-300 hover:bg-slate-800' }}">Pembayaran</a>
            <a href="{{ route('admin.customers.index') }}" class="rounded-lg px-3 py-2 text-sm {{ request()->routeIs('admin.customers.*') ? 'bg-slate-800' : 'text-slate-300 hover:bg-slate-800' }}">Customer</a>
            <form method="POST" action="{{ route('logout') }}" class="ml-2">@csrf<button class="rounded-lg bg-white px-3 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-100">Keluar</button></form>
        </div>
    </div>
    <div x-show="open" x-cloak class="border-t border-slate-800 px-4 py-3 sm:hidden"><div class="grid gap-1 text-sm"><a href="{{ route('admin.dashboard') }}" class="rounded px-3 py-2 hover:bg-slate-800">Ringkasan</a><a href="{{ route('admin.buses.index') }}" class="rounded px-3 py-2 hover:bg-slate-800">Bus</a><a href="{{ route('admin.routes.index') }}" class="rounded px-3 py-2 hover:bg-slate-800">Perjalanan</a><a href="{{ route('admin.orders.index') }}" class="rounded px-3 py-2 hover:bg-slate-800">Order</a><a href="{{ route('admin.payments.index') }}" class="rounded px-3 py-2 hover:bg-slate-800">Pembayaran</a></div></div>
</nav>
