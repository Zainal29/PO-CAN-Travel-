# PRODUCT REQUIREMENT DOCUMENT (PRD)

# PO CAN Travel

**Version:** 4.0
**Product:** PO CAN Travel — Sistem Pemesanan Tiket Bus
**Platform:** Web Application
**Stack:** Laravel 10, PHP 8.4, MySQL, Blade, Tailwind CSS, Alpine.js, Vite
**Timezone Bisnis:** Asia/Jakarta

---

## 1. Product Overview

PO CAN Travel adalah aplikasi web untuk pemesanan tiket bus antarkota yang memungkinkan customer mencari perjalanan, memilih jadwal dan kursi, mengisi data penumpang, melakukan pembayaran internal, memperoleh e-ticket QR, dan melakukan boarding melalui proses scan QR.

Admin menggunakan sistem untuk mengelola armada, jadwal, customer, pesanan, pembayaran, serta proses validasi e-ticket.

Sistem mengutamakan proses yang sederhana:

**Cari → Pilih → Pesan → Bayar → Dapatkan E-Ticket → Scan → Check-in → Perjalanan → Selesai**

---

# 2. Product Goals

Sistem harus:

1. Mempermudah customer mencari dan memesan tiket bus.
2. Menampilkan informasi perjalanan secara jelas.
3. Memungkinkan customer memilih nomor kursi.
4. Menyediakan pembayaran internal BCA, BRI, dan QRIS.
5. Menghasilkan e-ticket dengan QR Code.
6. Mempermudah proses boarding melalui scanner kamera.
7. Mencatat check-in berdasarkan hasil scan QR.
8. Mengotomatisasi status perjalanan berdasarkan waktu.
9. Mengurangi pekerjaan manual admin.
10. Memberikan UI profesional, responsif, dan konsisten.

---

# 3. User Roles

## 3.1 Guest

Guest dapat:

* melihat homepage;
* melihat jadwal perjalanan;
* mencari perjalanan;
* melihat detail perjalanan;
* login;
* register.

Guest tidak dapat:

* membuat booking;
* melihat order;
* melakukan pembayaran;
* mengakses e-ticket.

---

## 3.2 Customer

Customer dapat:

* kembali ke homepage;
* mencari perjalanan;
* melihat jadwal;
* melihat detail perjalanan;
* memilih kursi;
* membuat order;
* melakukan pembayaran;
* melihat order;
* membatalkan order sesuai aturan;
* melihat e-ticket;
* menampilkan QR Code;
* melihat countdown perjalanan;
* memberikan review setelah perjalanan selesai;
* mengelola profil.

---

## 3.3 Admin

Admin dapat:

* mengelola bus;
* mengelola rute;
* mengelola customer;
* melihat order;
* melihat pembayaran;
* memonitor perjalanan;
* membuka scanner e-ticket;
* melakukan validasi QR;
* melakukan check-in melalui hasil scan;
* melakukan sinkronisasi status perjalanan;
* mengarsipkan order sesuai aturan.

Admin tidak menggunakan halaman customer untuk operasional administrasi.

---

# 4. Customer Navigation

Navigasi harus selalu menyediakan jalan kembali ke homepage.

## Guest

```text
PO CAN Travel
├── Jadwal Bus
├── Masuk
└── Daftar
```

## Customer

```text
PO CAN Travel
├── Beranda
├── Jadwal Bus
├── Pesanan
└── Profil
```

Logo **PO CAN Travel** selalu mengarah ke:

```text
/
```

Customer tidak boleh kehilangan akses ke homepage ketika berada di halaman booking, pembayaran, order, ticket, atau profile.

---

# 5. Admin Navigation

Admin memiliki navigasi terpisah:

```text
Dashboard
Bus
Jadwal / Rute
Customer
Pesanan
Pembayaran
Scan E-Ticket
Pengaturan
```

Scanner e-ticket menjadi fitur operasional utama untuk proses boarding.

---

# 6. Homepage

Homepage harus memiliki identitas visual sebagai platform tiket bus, bukan template SaaS.

Struktur:

```text
Navbar
↓
Hero + Search
↓
Jadwal Bus
↓
Visualisasi Rute
↓
Cara Booking
↓
Benefit
↓
CTA
↓
Footer
```

## Hero

Hero menampilkan:

* visual armada bus;
* headline utama;
* deskripsi singkat;
* benefit utama;
* form pencarian perjalanan.

## Search

Field:

* Kota Asal;
* Kota Tujuan;
* Tanggal Berangkat;
* Jumlah Penumpang;
* Tipe Bus.

Search menggunakan endpoint dan request validation yang sudah tersedia.

---

# 7. Search Trip

Customer dapat melakukan pencarian berdasarkan:

* origin;
* destination;
* departure date;
* passengers;
* bus type;
* departure period;
* maximum price;
* sorting.

Hasil pencarian menampilkan:

* waktu keberangkatan;
* kota asal;
* terminal asal;
* kota tujuan;
* terminal tujuan;
* estimasi tiba;
* bus;
* tipe bus;
* kursi tersedia;
* harga;
* status perjalanan.

---

# 8. Trip Context

Sistem menghitung konteks perjalanan berdasarkan waktu `Asia/Jakarta`.

Contoh:

```text
Berangkat dalam 2 jam
Berangkat hari ini
Berangkat besok
Berangkat 27 Sep 2026
Perjalanan telah dimulai
Keberangkatan telah lewat
```

Konteks waktu merupakan informasi UI dan tidak membutuhkan status database baru.

---

# 9. Booking

Flow booking:

```text
01 Cari
 ↓
02 Pilih Kursi
 ↓
03 Isi Data Penumpang
 ↓
04 Konfirmasi
 ↓
05 Bayar
 ↓
06 E-Ticket
```

Customer dapat memesan maksimal 10 penumpang dalam satu order sesuai validasi sistem.

---

# 10. Seat Selection

Customer dapat melihat denah kursi.

Status kursi:

```text
Tersedia
Dipilih
Sudah Dipesan
```

Sistem wajib:

* mencegah pemesanan kursi ganda;
* memvalidasi nomor kursi;
* mengunci proses booking secara transaksional;
* mengurangi available seats;
* mengembalikan kursi ketika order dibatalkan atau expired sesuai business rule.

---

# 11. Payment

Payment merupakan simulasi internal.

Metode:

```text
BCA
BRI
QRIS
```

Tidak menggunakan payment gateway eksternal.

Tidak menggunakan:

* Midtrans;
* Xendit;
* kartu kredit;
* OTP eksternal;
* virtual payment gateway nyata.

Payment berhasil menghasilkan:

* transaction ID;
* paid_at;
* payment verified;
* order paid;
* ticket code.

---

# 12. Order Lifecycle

Lifecycle utama:

```text
PENDING
   │
   ├── timeout → EXPIRED
   │
   ├── customer cancel → CANCELLED
   │
   └── payment → PAID
                     │
                     │
                     ▼
                  E-TICKET
                     │
                     │ QR SCAN
                     ▼
                  CHECK-IN
                     │
                     │ estimasi tiba terlewati
                     ▼
                 COMPLETED
```

`checked_in_at` merupakan timestamp check-in, bukan status order baru.

---

# 13. E-Ticket

E-ticket wajib menampilkan:

* ticket code;
* order code;
* nama customer;
* nama penumpang;
* nomor kursi;
* bus;
* origin;
* destination;
* terminal;
* tanggal;
* jam keberangkatan;
* estimasi tiba;
* QR Code.

E-ticket harus memiliki desain seperti boarding pass dan tetap nyaman digunakan pada perangkat mobile.

---

# 14. Scanner E-Ticket

## 14.1 Prinsip Utama

Halaman scanner tidak menggunakan pencarian manual sebagai flow utama.

Tidak menyediakan:

```text
Cari Order Code
Cari Ticket Code
Input nomor tiket
```

Scanner langsung menggunakan kamera perangkat.

---

## 14.2 Flow Scanner

```text
Admin
 ↓
Scan E-Ticket
 ↓
Browser meminta izin kamera
 ↓
Kamera langsung terbuka
 ↓
QR Code diarahkan ke kamera
 ↓
QR terbaca
 ↓
Server melakukan validasi
 ↓
Hasil validasi ditampilkan
```

Scanner harus menggunakan kamera perangkat yang tersedia dan mendukung mobile maupun desktop dengan kamera.

---

# 15. QR Validation

Ketika QR terbaca, sistem harus melakukan validasi server-side.

Validasi minimal:

* QR/token valid;
* ticket ditemukan;
* order ditemukan;
* order telah dibayar;
* order belum dibatalkan;
* order belum expired;
* order belum completed;
* perjalanan sesuai dengan tiket;
* tiket belum check-in.

Jangan hanya melakukan validasi di JavaScript/browser.

Validasi final harus dilakukan di server.

---

# 16. Scan Tidak Langsung Menandai Check-in

Membaca QR tidak langsung mengubah database.

Flow:

```text
QR Scan
 ↓
Validasi
 ↓
Tampilkan detail tiket
 ↓
Tiket Valid
 ↓
[Konfirmasi Check-in]
 ↓
checked_in_at = now()
```

Dengan demikian:

**Scan = validasi tiket**

**Konfirmasi Check-in = tindakan boarding**

---

# 17. Hasil Scan Valid

Tampilan hasil scan:

```text
TIKET VALID

Ticket Code
CAN-XXXXXXXX

Jepara → Semarang

Penumpang
Nama Penumpang

Kursi
A1

Keberangkatan
07:30 WIB

Status
BELUM CHECK-IN

[ Konfirmasi Check-in ]
[ Scan Tiket Lain ]
```

Setelah konfirmasi:

```text
CHECK-IN BERHASIL

Check-in:
07:18 WIB

Penumpang telah tervalidasi.

[ Scan Tiket Lain ]
```

---

# 18. Tiket Sudah Check-in

Jika QR dipindai kembali:

```text
SUDAH CHECK-IN

Check-in:
07:18 WIB

Tiket ini sudah tervalidasi.

[ Scan Tiket Lain ]
```

Sistem tidak boleh membuat timestamp check-in baru.

---

# 19. QR Tidak Valid

Jika QR tidak valid:

```text
TIKET TIDAK VALID

Tiket tidak ditemukan atau sudah tidak aktif.

[ Scan Ulang ]
```

Tidak menampilkan data internal yang tidak diperlukan.

---

# 20. Auto Check-in Dihapus

Sistem **tidak melakukan check-in otomatis hanya karena waktu keberangkatan telah tiba**.

Aturan:

```text
Waktu keberangkatan tercapai
        ↓
Tiket tetap PAID
        ↓
Belum check-in
        ↓
Customer melakukan boarding
        ↓
QR di-scan
        ↓
Konfirmasi check-in
```

`checked_in_at` hanya diisi melalui proses validasi dan konfirmasi scan QR.

Hal ini memastikan data check-in merepresentasikan kejadian boarding yang benar-benar terjadi.

---

# 21. Auto Completion

Check-in tetap dilakukan melalui scan.

Namun status perjalanan dapat selesai secara otomatis berdasarkan estimasi waktu tiba.

```text
estimated_arrival_time terlewati
        ↓
Sinkronisasi status
        ↓
order_status = completed
```

Tidak diperlukan tombol:

**"Tandai Perjalanan Selesai"**

Admin tidak perlu menandai perjalanan selesai secara manual.

---

# 22. Sinkronisasi Status Perjalanan

Sistem menyediakan:

```bash
php artisan travel:sync-statuses
```

Command digunakan untuk memperbarui status order berdasarkan jadwal perjalanan.

Sinkronisasi dapat dipicu pada halaman operasional yang membutuhkan status terbaru.

Sistem tidak boleh mengklaim menggunakan background worker/realtime scheduler apabila mekanisme tersebut belum benar-benar tersedia.

---

# 23. Countdown

Countdown digunakan untuk memberikan informasi perjalanan kepada customer.

Fase:

```text
UPCOMING
↓
Countdown menuju keberangkatan

IN TRANSIT
↓
Countdown menuju estimasi tiba

COMPLETED
↓
Perjalanan telah selesai
```

Countdown ditampilkan pada:

* Customer Dashboard;
* Customer Order Detail;
* Customer E-Ticket;
* Admin Order Detail bila diperlukan.

Countdown hanya merupakan informasi UI.

---

# 24. Customer Dashboard

Dashboard customer berisi:

* greeting;
* tiket aktif;
* perjalanan terdekat;
* countdown;
* status perjalanan;
* order terbaru;
* quick access ke pencarian perjalanan.

Dashboard harus terasa seperti **travel dashboard**, bukan admin dashboard.

---

# 25. Customer Orders

Customer dapat melihat:

* semua order;
* pending;
* paid;
* completed;
* cancelled;
* expired.

Order card menampilkan:

* order code;
* ticket code;
* rute;
* tanggal;
* jam;
* bus;
* kursi;
* total;
* status;
* action yang tersedia.

---

# 26. Order Detail

Order detail harus memprioritaskan:

1. status order;
2. rute;
3. jadwal;
4. penumpang;
5. kursi;
6. payment;
7. ticket;
8. countdown;
9. cancellation/review sesuai kondisi.

Tidak menampilkan action yang sudah tidak relevan dengan kondisi order.

---

# 27. Cancellation

Customer dapat membatalkan order sesuai business rule.

Cancellation harus:

* mengubah order menjadi cancelled;
* mencatat cancellation note bila diperlukan;
* mengembalikan kursi;
* memperbarui payment sesuai aturan;
* mencegah booking tetap memblokir seat.

---

# 28. Review

Review hanya tersedia ketika order telah selesai.

Review dapat berisi:

* rating;
* komentar.

Customer tidak dapat memberikan review untuk perjalanan yang belum selesai.

---

# 29. Notification & Alert System

SweetAlert tidak lagi menjadi mekanisme utama untuk feedback aplikasi.

Feedback harus menggunakan UI notification yang konsisten.

Contoh:

```text
Success
✓ Pembayaran berhasil.

Error
! Tiket tidak dapat diproses.

Info
i Pesanan menunggu pembayaran.

Warning
! Pesanan akan segera kedaluwarsa.
```

Notification dapat berupa:

* inline alert;
* toast;
* banner kontekstual.

Tidak menggunakan popup besar untuk feedback sederhana.

---

# 30. Reusable Alert Component

Sistem harus memiliki komponen reusable untuk notification.

Contoh konsep:

```text
components/alert.blade.php
components/toast.blade.php
```

Komponen harus mendukung minimal:

```text
success
error
warning
info
```

Visual harus konsisten di:

* customer;
* admin;
* authentication;
* booking;
* payment;
* order;
* scanner.

---

# 31. Admin Order Detail

Admin order detail menampilkan:

* order code;
* ticket code;
* status;
* payment;
* rute;
* jadwal;
* penumpang;
* kursi;
* check-in;
* countdown bila relevan.

Tidak menyediakan tombol:

```text
Tandai Perjalanan Selesai
```

Karena completion mengikuti sistem status otomatis.

---

# 32. Admin Operational Flow

Flow operasional utama:

```text
Admin membuka Scanner
        ↓
Kamera terbuka
        ↓
Scan QR
        ↓
Validasi Server
        ↓
Detail Tiket
        ↓
Konfirmasi Check-in
        ↓
checked_in_at
```

Setelah selesai:

```text
[Scan Tiket Lain]
```

Scanner harus memungkinkan operator memproses banyak penumpang secara berurutan tanpa harus kembali ke dashboard setiap kali.

---

# 33. UI/UX Design Direction

Tema utama:

**Blue + White + Slate**

Warna:

```text
Primary    #2563EB
Navy       #081C2C
Slate      #0F172A
Background #F8FAFC
White      #FFFFFF
Success    #16A34A
Warning    #F59E0B
Error      #DC2626
```

Prinsip:

* profesional;
* clean;
* travel-oriented;
* mobile-first;
* mudah dipindai secara visual;
* hierarchy jelas;
* tidak terlalu banyak card;
* tidak terlalu banyak rounded element;
* tidak menggunakan gradient berlebihan;
* tidak menggunakan glassmorphism;
* tidak menggunakan blob;
* tidak menggunakan dekorasi yang tidak memiliki fungsi.

---

# 34. Responsive Requirements

UI harus diuji pada:

```text
360px
390px
430px
768px
1024px
1280px
```

Scanner harus diprioritaskan untuk perangkat mobile karena kemungkinan besar kamera digunakan melalui smartphone.

Tombol:

```text
Konfirmasi Check-in
Scan Tiket Lain
Scan Ulang
```

harus mudah ditekan pada layar sentuh.

---

# 35. Security

QR validation wajib dilakukan server-side.

Sistem harus mencegah:

* QR palsu;
* ticket yang tidak ditemukan;
* ticket cancelled;
* ticket expired;
* ticket completed;
* check-in ganda;
* akses customer ke order milik customer lain;
* akses admin ke endpoint customer yang tidak sesuai authorization.

Role middleware tetap dipertahankan.

---

# 36. Business Rules Utama

1. Customer hanya dapat mengakses order miliknya.
2. Admin dapat mengakses data operasional sesuai role.
3. Payment harus valid sebelum e-ticket dianggap dapat digunakan.
4. QR harus divalidasi server-side.
5. Scan tidak otomatis berarti check-in.
6. Check-in dilakukan setelah operator mengonfirmasi hasil scan.
7. `checked_in_at` hanya dicatat satu kali.
8. Check-in tidak dilakukan otomatis berdasarkan waktu.
9. Completion dilakukan berdasarkan estimasi waktu tiba.
10. Tidak ada tombol manual "Tandai Perjalanan Selesai".
11. Kursi dikembalikan ketika order dibatalkan/expired sesuai aturan.
12. Customer dapat kembali ke homepage dari seluruh halaman customer.
13. Scanner tidak menggunakan pencarian tiket manual sebagai flow utama.
14. SweetAlert diganti sistem notification UI yang konsisten.

---

# 37. Out of Scope

Fitur berikut tidak termasuk dalam scope:

* Midtrans;
* Xendit;
* payment gateway eksternal;
* kartu kredit;
* OTP pembayaran;
* live GPS bus;
* tracking posisi bus secara realtime;
* penentuan lokasi penumpang;
* check-in otomatis hanya berdasarkan waktu;
* tombol manual untuk menyelesaikan perjalanan;
* sistem pencarian manual sebagai flow utama scanner.

---

# 38. Technical Constraints

Perubahan UI tidak boleh merusak:

* database;
* migration;
* model;
* controller;
* service;
* request validation;
* middleware;
* authentication;
* authorization;
* existing route;
* business logic booking;
* seat locking;
* payment simulation.

Jika perubahan backend memang diperlukan untuk scanner atau business rule baru, perubahan harus dibatasi pada kebutuhan tersebut dan tidak melakukan refactor besar yang tidak diperlukan.

---

# 39. Testing Requirements

Setiap perubahan wajib melewati:

```bash
php artisan optimize:clear
php artisan view:cache
php artisan test
npm run build
```

Untuk sinkronisasi:

```bash
php artisan travel:sync-statuses
```

Testing minimal:

* authentication;
* authorization;
* trip search;
* booking;
* seat availability;
* payment;
* cancellation;
* expiration;
* QR validation;
* QR invalid;
* QR already checked-in;
* QR cancelled;
* QR unpaid;
* successful check-in;
* duplicate check-in prevention;
* auto completion;
* customer navigation;
* admin navigation;
* responsive UI;
* notification UI.

---

# 40. Acceptance Criteria Scanner

Scanner dianggap selesai apabila:

* halaman scanner langsung meminta akses kamera;
* kamera dapat digunakan pada perangkat yang kompatibel;
* QR berhasil dibaca;
* token dikirim ke server;
* server melakukan validasi;
* tiket valid menampilkan informasi penumpang;
* tiket invalid menampilkan error yang jelas;
* tiket yang sudah check-in tidak dapat check-in ulang;
* tombol konfirmasi check-in hanya muncul ketika tiket valid dan belum check-in;
* `checked_in_at` tersimpan setelah konfirmasi;
* scanner dapat kembali melakukan scan tiket berikutnya;
* tidak terdapat pencarian tiket manual pada flow scanner utama.

---

# 41. Acceptance Criteria Customer

Customer dianggap mendapatkan flow yang benar apabila:

* dapat kembali ke homepage dari semua halaman customer;
* dapat mencari perjalanan;
* dapat memilih kursi;
* dapat membuat order;
* dapat melakukan payment;
* mendapatkan e-ticket;
* dapat melihat QR;
* dapat melihat countdown;
* dapat melihat status check-in;
* dapat melihat status completed;
* dapat memberikan review setelah perjalanan selesai.

---

# 42. Acceptance Criteria UI

UI dianggap selesai apabila:

* konsisten menggunakan blue/white/slate;
* tidak bergantung pada gradient berlebihan;
* tidak menggunakan glassmorphism;
* tidak menggunakan dekorasi AI-slop;
* responsive;
* navigation role-aware;
* alert konsisten;
* scanner nyaman digunakan pada mobile;
* typography jelas;
* CTA mudah ditemukan;
* tidak terdapat dead-end navigation.

---

# 43. Definition of Done

Fitur dianggap selesai apabila:

```text
PRD sesuai
   ↓
Business rule benar
   ↓
Authorization benar
   ↓
Server validation benar
   ↓
UI selesai
   ↓
Responsive selesai
   ↓
Scanner diuji
   ↓
Notification diuji
   ↓
Blade cache berhasil
   ↓
PHPUnit lulus
   ↓
Vite build berhasil
   ↓
Tidak ada regresi
```

---

# 44. Final Product Flow

Flow final PO CAN Travel:

```text
                         GUEST
                           │
                           ▼
                     HOMEPAGE
                           │
                    CARI PERJALANAN
                           │
                           ▼
                    PILIH JADWAL
                           │
                           ▼
                      PILIH KURSI
                           │
                           ▼
                    DATA PENUMPANG
                           │
                           ▼
                       PAYMENT
                           │
                           ▼
                       E-TICKET
                           │
                           │
                    ┌──────┴──────┐
                    │             │
                    ▼             ▼
                CUSTOMER       ADMIN
                    │             │
                    │        SCAN QR CAMERA
                    │             │
                    │             ▼
                    │       VALIDASI SERVER
                    │             │
                    │             ▼
                    │        TIKET VALID
                    │             │
                    │             ▼
                    │      KONFIRMASI CHECK-IN
                    │             │
                    │             ▼
                    │       checked_in_at
                    │             │
                    └──────┬──────┘
                           │
                           ▼
                       PERJALANAN
                           │
                           ▼
                  ESTIMASI TIBA LEWAT
                           │
                           ▼
                       COMPLETED
                           │
                           ▼
                         REVIEW
```

**Prinsip inti versi 4.0:**

> **Waktu menentukan status perjalanan. QR menentukan validasi boarding. Operator mengonfirmasi check-in. Customer tetap menjadi pusat pengalaman pemesanan.**
