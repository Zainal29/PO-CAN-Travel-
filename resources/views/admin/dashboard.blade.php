<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-amber-600">OPERASIONAL</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-950">Dashboard Admin</h1>
                <p class="mt-1 text-sm text-slate-500">Pantau performa, transaksi, dan pengaturan sistem PO CAN Travel.</p>
            </div>
            <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Pengaturan Sistem
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8 space-y-8">
        
        {{-- 1. Statistik Utama --}}
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Pendapatan (Verified)</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                <div class="mt-3 flex items-center gap-2 text-xs font-semibold text-emerald-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pembayaran Terverifikasi
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Menunggu Verifikasi</p>
                <h2 class="mt-2 text-2xl font-bold text-amber-600">{{ $pendingVerifications }} Pembayaran</h2>
                <div class="mt-3 flex items-center gap-2 text-xs font-semibold text-amber-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Perlu tindakan admin
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Order Belum Bayar</p>
                <h2 class="mt-2 text-2xl font-bold text-blue-600">{{ $pendingOrders }} Order</h2>
                <div class="mt-3 flex items-center gap-2 text-xs font-semibold text-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Menunggu pembayaran
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Armada Aktif</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $activeBuses }} Bus</h2>
                <div class="mt-3 flex items-center gap-2 text-xs font-semibold text-slate-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    Siap beroperasi
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Order</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $totalOrders }}</h2>
                <p class="mt-3 text-xs font-semibold text-slate-600">Semua status</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Rute Aktif</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $activeRoutes }}</h2>
                <p class="mt-3 text-xs font-semibold text-slate-600">Dapat dipesan</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-bold text-slate-900">Pendapatan 6 Bulan Terakhir</h3>
                <div class="mt-4 h-72"><canvas id="revenueChart"></canvas></div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-bold text-slate-900">Okupansi Top 5 Rute</h3>
                <div class="mt-4 h-72"><canvas id="occupancyChart"></canvas></div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-bold text-slate-900">Distribusi Order per Status</h3>
                <div class="mt-4 h-72"><canvas id="orderStatusChart"></canvas></div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-bold text-slate-900">Trend Order 7 Hari Terakhir</h3>
                <div class="mt-4 h-72"><canvas id="trendChart"></canvas></div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="font-bold text-slate-900">Ringkasan Pembayaran</h3>
            <div class="mt-4 grid gap-3 sm:grid-cols-4">
                @foreach(['verified' => 'Verified', 'pending' => 'Pending', 'rejected' => 'Rejected', 'unpaid' => 'Unpaid'] as $status => $label)
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase text-slate-500">{{ $label }}</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">{{ $paymentCounts[$status] ?? 0 }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 2. Tabel Aktivitas Terkini --}}
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Recent Orders --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="font-bold text-slate-900">Order Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                            <tr>
                                <th class="px-6 py-3">Kode Order</th>
                                <th class="px-6 py-3">Penumpang</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $order->order_code }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $order->user->name }}</td>
                                <td class="px-6 py-4 text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold 
                                        {{ $order->order_status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada data order.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pending Payments --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="font-bold text-slate-900">Pembayaran Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                            <tr>
                                <th class="px-6 py-3">Kode Order</th>
                                <th class="px-6 py-3">Metode</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pendingPayments as $payment)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $payment->order->order_code }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td class="px-6 py-4 text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="text-sm font-semibold text-amber-700 hover:text-amber-900 underline decoration-amber-300 underline-offset-2">Lihat Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">Tidak ada pembayaran yang menunggu.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="font-bold text-slate-900">10 Pembayaran Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Kode Order</th>
                            <th class="px-6 py-3">Penumpang</th>
                            <th class="px-6 py-3">Metode</th>
                            <th class="px-6 py-3">Jumlah</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentPayments as $payment)
                            @php
                                $paymentStatusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'verified' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    'unpaid' => 'bg-slate-100 text-slate-700',
                                ];
                            @endphp
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $payment->order?->order_code ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $payment->order?->details?->pluck('passenger_name')->join(', ') ?: '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td class="px-6 py-4 text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $paymentStatusClasses[$payment->status] ?? 'bg-slate-100 text-slate-700' }}">{{ ucfirst($payment->status) }}</span></td>
                                <td class="px-6 py-4 text-slate-600">{{ $payment->created_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="px-6 py-4"><a href="{{ route('admin.payments.show', $payment) }}" class="font-semibold text-amber-700 hover:text-amber-900">Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada data pembayaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="font-bold text-slate-900">Detail Armada Aktif</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Bus</th>
                            <th class="px-6 py-3">Tipe</th>
                            <th class="px-6 py-3">Kursi</th>
                            <th class="px-6 py-3">Rute Aktif</th>
                            <th class="px-6 py-3">Penumpang</th>
                            <th class="px-6 py-3">Sisa Kursi</th>
                            <th class="px-6 py-3">Okupansi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($busStats as $bus)
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $bus['name'] }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ ucfirst(str_replace('_', ' ', $bus['type'])) }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $bus['total_seats'] }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $bus['routes'] }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $bus['passengers'] }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $bus['available_seats'] }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ number_format($bus['occupancy'], 2, ',', '.') }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada armada aktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="font-bold text-slate-900">Detail Rute</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Rute</th>
                            <th class="px-6 py-3">Jadwal</th>
                            <th class="px-6 py-3">Harga</th>
                            <th class="px-6 py-3">Kursi</th>
                            <th class="px-6 py-3">Order</th>
                            <th class="px-6 py-3">Penumpang</th>
                            <th class="px-6 py-3">Pendapatan</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($routeDetails as $route)
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $route['route'] }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $route['departure'] }}</td>
                                <td class="px-6 py-4 text-slate-600">Rp {{ number_format($route['price'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $route['available_seats'] }} / {{ $route['total_seats'] }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $route['orders'] }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $route['passengers'] }}</td>
                                <td class="px-6 py-4 text-slate-900">Rp {{ number_format($route['revenue'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $route['status'] === 'available' ? 'bg-emerald-100 text-emerald-700' : ($route['status'] === 'full' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">{{ ucfirst($route['status']) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada data rute.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 3. Quick Action --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-bold text-slate-900">Mulai dari data perjalanan</h2>
                    <p class="mt-1 text-sm text-slate-600">Tambahkan bus dan jadwal terlebih dahulu agar customer dapat melakukan pencarian.</p>
                </div>
                <a href="{{ route('admin.routes.create') }}" class="w-fit rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                    Tambah perjalanan
                </a>
            </div>
        </div>

    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const chartOptions = { responsive: true, maintainAspectRatio: false };
    const revenue = @json($revenueByMonth);
    const occupancy = @json($routeStats);
    const statuses = @json($statusLabels);
    const statusCounts = @json($ordersByStatus);
    const trend = @json($ordersTrend);

    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: revenue.map(item => item.label),
            datasets: [{ label: 'Pendapatan (Rp)', data: revenue.map(item => item.total), backgroundColor: '#10b981' }],
        },
        options: chartOptions,
    });

    new Chart(document.getElementById('occupancyChart'), {
        type: 'bar',
        data: {
            labels: occupancy.map(item => item.route),
            datasets: [{ label: 'Okupansi (%)', data: occupancy.map(item => item.occupancy), backgroundColor: '#3b82f6' }],
        },
        options: { ...chartOptions, scales: { y: { beginAtZero: true, max: 100 } } },
    });

    new Chart(document.getElementById('orderStatusChart'), {
        type: 'pie',
        data: {
            labels: statuses,
            datasets: [{ data: statuses.map(status => statusCounts[status] ?? 0), backgroundColor: ['#fbbf24', '#3b82f6', '#10b981', '#ef4444', '#6b7280', '#8b5cf6'] }],
        },
        options: chartOptions,
    });

    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: trend.map(item => item.label),
            datasets: [{ label: 'Jumlah Order', data: trend.map(item => item.total), borderColor: '#8b5cf6', backgroundColor: '#8b5cf6', tension: 0.2 }],
        },
        options: { ...chartOptions, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
    });
</script>
@endpush
</x-app-layout>