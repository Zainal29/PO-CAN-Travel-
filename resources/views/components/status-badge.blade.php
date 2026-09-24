@props(['status', 'type' => 'order'])

@php
    $status = strtolower($status ?? ($type === 'payment' ? 'unpaid' : 'pending'));
    $labels = [
        'pending' => $type === 'payment' ? 'Menunggu verifikasi' : 'Menunggu pembayaran',
        'paid' => 'Dibayar',
        'cancelled' => 'Dibatalkan',
        'completed' => 'Selesai',
        'expired' => 'Kedaluwarsa',
        'unpaid' => 'Belum dibayar',
        'verified' => 'Terverifikasi',
        'rejected' => 'Ditolak',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'status status-' . $status]) }}>{{ $labels[$status] ?? ucfirst($status) }}</span>
