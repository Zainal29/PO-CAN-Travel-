# PO CAN Travel

Aplikasi pemesanan tiket bus berbasis Laravel untuk admin dan customer. Fitur utama mencakup pencarian perjalanan, pemilihan kursi, booking transaksional, pembayaran dengan unggah bukti, verifikasi admin, pembatalan, dan kedaluwarsa order.

## Menjalankan aplikasi

Prasyarat: PHP 8.1+, Composer, Node.js, dan MySQL.

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
```

Atur kredensial MySQL pada `.env`, lalu jalankan:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Akun demo

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@pocantravel.test` | `password` |
| Customer | `customer@pocantravel.test` | `password` |

## Perintah penting

```bash
# Menjalankan test otomatis (menggunakan SQLite in-memory)
php artisan test

# Memproses order yang melewati expired_at dan mengembalikan kursi
php artisan orders:expire

# Menjalankan scheduler lokal agar expiry dipanggil otomatis
php artisan schedule:work
```

Untuk produksi, jadwalkan `php artisan schedule:run` setiap menit melalui cron.
