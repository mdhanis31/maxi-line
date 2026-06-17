# Maxi-Line CRM

Maxi-Line adalah aplikasi CRM untuk ISP (Internet Service Provider) yang dirancang untuk mengelola pelanggan, tagihan, tiket layanan, dan integrasi perangkat jaringan.

## Ringkasan
Aplikasi ini menyediakan manajemen user berbasis role (role-based access control) dan fitur otomatisasi billing serta integrasi ke Mikrotik melalui API.

## Role dan Hak Akses
- Administrator: mengelola sistem dan memiliki hak akses penuh.
- Finance: manajemen billing dan laporan keuangan.
- Teknisi: melihat informasi pelanggan, menangani laporan, melihat jadwal instalasi/dismantle, dan monitoring trafik.
- CS (Customer Service): respon pertama terhadap laporan pelanggan dan melakukan assignment teknisi untuk penanganan gangguan.
- Pelanggan: melihat riwayat tagihan, membuka tiket masalah, mengonfirmasi pembayaran.
- Reseller: melihat informasi pelanggan di bawah naungan dan membantu membuka tiket untuk mereka.

## Fitur Utama
- Multi-role user dengan RBAC (role-based access control).
- Auto-billing: pembuatan tagihan berkala otomatis.
- Integrasi Mikrotik via API untuk manajemen user dan monitoring trafik.
- Sistem tiket untuk pelaporan gangguan dan follow-up.
- Dashboard administrasi dan laporan.

## Instalasi Singkat
Prerequisites: PHP, MySQL/MariaDB, Composer, web server (Apache/Nginx).

1. Clone repository:
   git clone https://github.com/mdhanis31/maxi-line.git
2. Masuk ke folder proyek dan salin environment:
   cp .env.example .env
3. Install dependensi (jika menggunakan Composer):
   composer install
4. Buat database dan atur konfigurasi database di .env
5. Jalankan migrasi dan seeder sesuai struktur proyek (contoh):
   php artisan migrate --seed
6. Setel cron job untuk auto-billing sesuai dokumentasi aplikasi.

Catatan: langkah 3–5 bisa berbeda tergantung stack proyek (Laravel, plain PHP, dll). Sesuaikan dengan instruksi di dokumentasi proyek bila ada.

## Integrasi Mikrotik
Aplikasi sudah mendukung integrasi ke Mikrotik menggunakan API. Pastikan kredensial dan akses API Mikrotik dikonfigurasi di file .env atau konfigurasi aplikasi, serta koneksi aman (VPN/firewall) antara server aplikasi dan Mikrotik.

## Tips Deployment
- Jangan commit file log besar (tambahkan ke .gitignore).
- Jika perlu menyimpan file besar, gunakan Git LFS.
- Amankan file konfigurasi (.env) dan kredensial.

## Kontribusi
Silakan buka issue atau PR di repo ini. Sertakan deskripsi perubahan dan langkah pengujian.

## Lisensi
(Tentukan lisensi proyek — tambahkan file LICENSE jika diperlukan)

---
Dibuat untuk: Maxi-Line CRM — crm for isp maxi-line
