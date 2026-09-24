@props(['status', 'type' => 'order'])

@php
    $status = strtolower((string) ($status ?? ($type === 'payment' ? 'unpaid' : ($type === 'route' ? 'available' : ($type === 'bus' ? 'active' : 'pending')))));
    $labels = [
        'pending' => $type === 'payment' ? 'Menunggu Verifikasi' : 'Menunggu Pembayaran',
        'paid' => 'Dibayar',
        'cancelled' => 'Dibatalkan',
        'completed' => 'Selesai',
        'expired' => 'Kedaluwarsa',
        'unpaid' => 'Belum Dibayar',
        'verified' => 'Terverifikasi',
        'rejected' => 'Ditolak',
        'available' => 'Tersedia',
        'full' => 'Penuh',
        'active' => 'Aktif',
        'maintenance' => 'Perawatan',
        'inactive' => 'Nonaktif',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'status status-' . $status]) }}>{{ $labels[$status] ?? ucfirst($status) }}</span>
