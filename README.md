# PO CAN Travel — Sistem Pemesanan Tiket Bus Antarkota

Sistem informasi dan platform pemesanan tiket bus antarkota berbasis web modern yang dibangun menggunakan **Laravel 10**, **Tailwind CSS**, **Alpine.js**, dan **MySQL**. Aplikasi ini menyediakan pengalaman pemesanan tiket yang transparan dan praktis untuk penumpang (Customer) serta portal manajemen operasional dan keuangan komprehensif untuk pengelola bus (Admin).

---

## Daftar Isi
- [Fitur Utama](#fitur-utama)
  - [1. Halaman Publik & Beranda](#1-halaman-publik--beranda)
  - [2. Portal Pelanggan (Customer)](#2-portal-pelanggan-customer)
  - [3. Portal Pengelola (Admin)](#3-portal-pengelola-admin)
  - [4. Otomasi & Sinkronisasi Sistem](#4-otomasi--sinkronisasi-sistem)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Prasyarat Sistem](#prasyarat-sistem)
- [Panduan Instalasi & Menjalankan](#panduan-instalasi--menjalankan)
- [Akun Demo](#akun-demo)
- [Perintah Artisan Penting](#perintah-artisan-penting)
- [Pengujian Otomatis (Testing)](#pengujian-otomatis-testing)
- [Struktur Direktori Proyek](#struktur-direktori-proyek)

---

## Fitur Utama

### 1. Halaman Publik & Beranda
- **Hero Banner Dinamis**: Judul, subjudul, badge, dan foto bus armada dapat diatur langsung dari menu pengaturan admin.
- **Pencarian Jadwal Perjalanan Cepat**: Filter pencarian rute asal, kota tujuan, tanggal keberangkatan, jumlah penumpang, dan kelas armada bus.
- **Autocomplete Kota Rute**: Rekomendasi kota asal dan tujuan secara otomatis via API (`/api/cities/suggestion`).
- **Katalog Jadwal Bus Real-Time**: Tampilan kartu jadwal bus dengan visual rute vertikal (terminal asal ke terminal tujuan), foto armada, konteks status waktu keberangkatan (*Berangkat hari ini*, *Berangkat besok*, *Berangkat dalam X jam*, atau *Telah lewat*), ketersediaan sisa kursi, dan fasilitas bus.
- **Panduan & Edukasi Pemesanan**: 5 langkah pemesanan tiket resmi, garansi kepastian kursi 100%, tarif transparan tanpa biaya siluman, dan CS siaga.
- **Keunggulan Layanan**: Menampilkan 4 pilar kenyamanan (denah kursi interaktif, pembayaran otomatis, e-ticket QR resmi, dan armada terawat).
- **Statistik Kepercayaan & Kepuasan**: Counter metrik tiket terpesan, konfirmasi jadwal, dan rating ulasan penumpang.
- **Informasi Kebijakan Resmi**: Ketentuan pembatalan tiket (maksimal 3 jam sebelum berangkat), ketentuan boarding di terminal, dan jatah bagasi penumpang (20 kg).
- **Desain Responsif Mobile**: Dilengkapi tombol **Hamburger Menu Drawer** dan **Bottom Navigation Bar** sticky untuk navigasi layar smartphone yang nyaman dan rapi.

### 2. Portal Pelanggan (Customer)
- **Autentikasi & Profil**: Registrasi akun pelanggan, login aman, lupa/reset password, edit informasi profil, ubah password, dan penghapusan akun.
- **Dashboard Pelanggan**: Ringkasan status pesanan (Total Pesanan, Menunggu Pembayaran, Tiket Siap Berangkat, dan Tiket Selesai) serta kartu jadwal perjalanan terdekat.
- **Pencarian & Filter Rute Lengkap**: Filter berdasarkan kota, tanggal, kelas bus (*Economy*, *Executive*, *VIP*, *Super VIP*), dan pengurutan harga termurah.
- **Pemilihan Kursi Interaktif (Interactive Seat Map)**:
  - Denah kursi bus format 2-2 interaktif dengan visual posisi supir dan pintu bus.
  - Indikator status kursi: *Tersedia*, *Sedang Dipilih*, dan *Sudah Dipesan*.
  - Proteksi anti-dobel kursi (*seat conflict prevention*) pada level transaksi database.
  - Otomatis memblokir nomor kursi tertentu saat kuota rute tersisa lebih sedikit dari jumlah kursi fisik.
- **Input Data Manifes Penumpang**: Pengisian nama lengkap sesuai identitas resmi (KTP/SIM), nomor WhatsApp aktif, dan alokasi nomor kursi per penumpang.
- **Metode Pembayaran Lengkap**:
  - Simulasi pembayaran Transfer Bank (BCA, BRI), QRIS Instan, Virtual Account, E-Wallet, atau Tunai.
  - Unggah bukti transfer pembayaran.
  - Batas waktu pembayaran otomatis (*auto-expiry timer*).
- **E-Ticket Digital & QR Code Boarding**:
  - Penerbitan kode tiket dan QR Code resmi secara otomatis menggunakan generator `endroid/qr-code`.
  - Tampilan e-ticket digital lengkap yang ramah ponsel (tanpa perlu cetak kertas fisik).
  - Fitur unduh file gambar QR Code tiket untuk persiapan boarding di terminal.
- **Riwayat Pesanan & Pembatalan Mandiri**:
  - Pelanggan dapat memantau status pesanan (*Pending*, *Paid*, *Cancelled*, *Completed*, *Expired*).
  - Pembatalan pesanan mandiri dengan pengembalian kursi ke sistem sesuai ketentuan kebijakan waktu.
- **Ulasan & Rating Perjalanan**: Pelanggan dapat memberikan rating bintang dan review pengalaman perjalanan setelah tiket berstatus *Completed*.
- **Akses Cepat Kembali ke Home**: Tombol navigasi **Home / Web Utama** tersedia di navbar atas, hamburger menu, dan bottom bar mobile agar pelanggan dapat kembali ke beranda kapan saja.

### 3. Portal Pengelola (Admin)
- **Dashboard Analitik & Monitoring**:
  - Metrik utama: Total Pendapatan Terverifikasi, Pembayaran Menunggu Verifikasi, Order Belum Bayar, Armada Bus Aktif, Total Pesanan, dan Rute Aktif.
  - Grafik visual interaktif dengan **Chart.js**:
    - Tren Pendapatan 6 Bulan Terakhir (Bar Chart).
    - Persentase Okupansi Penumpang Top 5 Rute (Bar Chart).
    - Proporsi Distribusi Order per Status (Doughnut Chart).
    - Tren Pesanan Harian 7 Hari Terakhir (Line Chart).
  - Tabel ringkasan performa dan okupansi setiap armada bus aktif.
- **Manajemen Armada Bus (Buses)**:
  - Tambah, edit, dan kelola bus: nama bus, kode bus unik, nomor plat kendaraan, kapasitas kursi, kelas/tipe armada, status armada (*Active*, *Maintenance*, *Inactive*), foto bus, dan checklist fasilitas armada.
- **Manajemen Jadwal & Rute (Travel Routes)**:
  - Tambah, edit, dan kelola rute perjalanan: armada bus yang ditugaskan, kota & terminal asal, kota & terminal tujuan, tanggal dan jam keberangkatan, estimasi jam tiba, tarif tiket per kursi, sisa kursi, dan status rute (*Available*, *Full*, *Cancelled*, *Completed*).
- **Manajemen Pesanan Tiket (Orders)**:
  - Monitoring seluruh tiket yang masuk dari seluruh pelanggan.
  - Rincian manifes penumpang dan nomor kursi tiap order.
  - Aksi admin: konfirmasi manual, check-in penumpang, rilis kursi, batalkan order, dan arsip data.
- **Finansial, Pembayaran & Cetak Excel (Payments)**:
  - Monitoring riwayat pembayaran masuk (Transfer/QRIS/VA) beserta foto bukti bayar.
  - Verifikasi pembayaran (Setujui / Tolak dengan catatan alasan penolakan).
  - **Cetak / Export Excel Keuangan Pendapatan**:
    - Tombol *Cetak Excel Keuangan* di header Riwayat Pembayaran dan Dashboard Admin.
    - Format CSV/Excel berstandar UTF-8 BOM yang dapat langsung dibuka di Microsoft Excel, Google Sheets, dan LibreOffice Calc.
    - Berisi detail transaksi: ID Transaksi, Kode Order, Tanggal, Nama Pelanggan, Kontak, Rute, Armada, Jadwal, Metode Bayar, Status, Nominal (Rp), dan baris Total Rekapitulasi Keuangan.
    - Mendukung ekspor berdasarkan filter pencarian aktif (status pembayaran, metode pembayaran, tanggal, dan kata kunci).
- **Scanner E-Ticket QR Code (Scanner)**:
  - Fitur pemindaian QR code tiket penumpang langsung dari layar untuk validasi boarding cepat di terminal.
- **Data Pelanggan (Customers)**:
  - Direktori seluruh pengguna terdaftar, total akumulasi transaksi tiket, dan total belanja perjalanan.
- **Pengaturan Sistem (Settings)**:
  - Pengaturan nama PO / aplikasi, teks badge hero, headline & subheadline landing page, gambar background bus hero, batas kedaluwarsa tagihan (*payment expiry hours*), teks kebijakan pembatalan, alamat kantor, email, dan telepon operasional.

### 4. Otomasi & Sinkronisasi Sistem
- **Order Expiration (`orders:expire`)**: Membatalkan pesanan yang melewati batas waktu pembayaran secara otomatis dan mengembalikan kursi ke kuota rute.
- **Schedule Sync Service (`travel:sync-statuses`)**: Otomatis memperbarui status pesanan menjadi *Checked-in* saat waktu keberangkatan tiba, dan *Completed* setelah bus melewati estimasi waktu kedatangan.

---

## Teknologi yang Digunakan

| Kategori | Teknologi / Library |
| --- | --- |
| **Framework Backend** | [Laravel 10](https://laravel.com/) (PHP 8.1+) |
| **Database** | MySQL (Produksi & Lokal), SQLite (Unit & Feature Testing) |
| **Styling & UI** | [Tailwind CSS v3](https://tailwindcss.com/) dengan skema warna profesional |
| **Interaktivitas Frontend** | [Alpine.js](https://alpinejs.dev/) (Dropdown, modal, filter, hamburger menu, autocomplete) |
| **Grafik & Visualisasi** | [Chart.js](https://www.chartjs.org/) |
| **QR Code Generator** | [Endroid QR Code](https://github.com/endroid/qr-code) |
| **Asset Bundler** | [Vite 5](https://vitejs.dev/) |
| **Testing** | PHPUnit & Laravel Testing Tools (75+ Test Assertions) |

---

## Prasyarat Sistem
Pastikan perangkat Anda telah terinstal:
- PHP >= 8.1 dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `gd` / `imagick`
- Composer >= 2.0
- Node.js >= 18.x dan npm
- MySQL Server >= 8.0 atau MariaDB >= 10.4

---

## Panduan Instalasi & Menjalankan

### 1. Kloning Repositori
```bash
git clone https://github.com/Zainal29/PO-CAN-Travel-.git
cd PO-CAN-Travel-
```

### 2. Pasang Dependensi PHP & Node.js
```bash
composer install
npm install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan koneksi database MySQL pada file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=po_can_travel
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi Database & Seeding Data Awal
Jalankan migrasi tabel dan seeding data armada, rute, akun, dan demonstrasi transaksi:
```bash
php artisan migrate --seed
```

### 5. Buat Symbolic Link Storage
Pastikan file upload (foto bus, bukti pembayaran, logo) dapat diakses publik:
```bash
php artisan storage:link
```

### 6. Kompilasi Aset Frontend
Untuk mode produksi:
```bash
npm run build
```
Atau untuk mode pengembangan (*hot reload*):
```bash
npm run dev
```

### 7. Jalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui peramban di: **`http://127.0.0.1:8000`**

---

## Akun Demo

Database seeder secara otomatis menyediakan dua akun untuk pengujian:

| Peran (Role) | Alamat Email | Password | Hak Akses |
| --- | --- | --- | --- |
| **Administrator** | `admin@pocantravel.test` | `password` | Portal Admin (`/admin/dashboard`), manajemen bus, rute, pesanan, verifikasi dana, cetak Excel, scanner QR, dan pengaturan. |
| **Customer** | `customer@pocantravel.test` | `password` | Portal Pelanggan (`/customer/dashboard`), pemesanan tiket, denah kursi interaktif, upload bukti bayar, cetak e-ticket QR, dan ulasan. |

---

## Perintah Artisan Penting

### 1. Pembatalan Otomatis Tagihan Kedaluwarsa
Mengecek seluruh pesanan pending yang melewati batas waktu pembayaran (`expired_at`) dan mengembalikan kursi ke rute terkait:
```bash
php artisan orders:expire
```

### 2. Sinkronisasi Status Jadwal Perjalanan
Memperbarui status perjalanan secara otomatis (check-in saat waktu berangkat tiba dan selesai saat estimasi waktu tiba telah lewat):
```bash
php artisan travel:sync-statuses
```

### 3. Menjalankan Task Scheduler Lokal
Untuk menjalankan scheduler secara lokal agar perintah di atas dieksekusi berkala setiap menit:
```bash
php artisan schedule:work
```

> **Untuk Lingkungan Produksi**: Tambahkan entri cron berikut pada server Linux Anda:
> ```cron
> * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
> ```

---

## Pengujian Otomatis (Testing)

Proyek ini telah dilengkapi dengan rangkaian pengujian fitur dan unit (*Feature & Unit Tests*) lengkap yang mencakup otorisasi admin, manajemen bus & rute, proses booking, pembayaran, kedaluwarsa order, ulasan tiket, pencarian rute, dan ekspor laporan keuangan:

```bash
# Menjalankan seluruh test suite otomatis (menggunakan SQLite in-memory)
php artisan test

# Menjalankan pengujian fitur export excel admin secara spesifik
php artisan test --filter=AdminPaymentExportTest
```

---

## Struktur Direktori Proyek

```text
po-can-travel/
├── app/
│   ├── Console/Commands/       # Custom Artisan commands (orders:expire, travel:sync-statuses)
│   ├── Http/Controllers/
│   │   ├── Admin/              # Controller portal admin (Dashboard, Bus, Route, Order, Payment, dll)
│   │   ├── Customer/           # Controller portal pelanggan (Dashboard, Trip, Booking, Order, Payment)
│   │   ├── Auth/               # Controller autentikasi Laravel Breeze
│   │   └── HomeController.php  # Controller halaman landing publik
│   ├── Models/                 # Eloquent models (Bus, TravelRoute, Order, Payment, User, Setting, dll)
│   └── Services/               # Layanan bisnis (BookingService, OrderScheduleSyncService)
├── database/
│   ├── migrations/             # Migrasi skema database
│   └── seeders/                # Data seeder demo perjalanan dan akun
├── resources/
│   ├── css/app.css             # Styling Tailwind CSS & kustom layer
│   ├── js/app.js               # Inisialisasi Alpine.js & integrasi modul
│   └── views/
│       ├── admin/              # Tampilan Blade portal pengelola
│       ├── customer/           # Tampilan Blade portal pelanggan
│       ├── layouts/            # Layout utama aplikasi
│       └── welcome.blade.php   # Landing page utama PO CAN Travel
├── routes/
│   ├── web.php                 # Rute utama web, admin, dan customer
│   └── auth.php                # Rute autentikasi
└── tests/
    └── Feature/                # Pengujian fungsional dan integritas sistem
```

---

## Lisensi
Proyek ini dikembangkan di bawah lisensi [MIT License](LICENSE).
