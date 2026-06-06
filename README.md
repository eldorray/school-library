# School Library — Sistem Manajemen Perpustakaan YPDH Al Madani

Aplikasi Laravel untuk manajemen perpustakaan sekolah — mengelola koleksi buku, anggota, peminjaman, dan pengembalian.

## Fitur

- **Manajemen buku** — katalog, genre, rak, kode ISBN
- **Manajemen anggota** — data siswa & guru sebagai peminjam
- **Peminjaman & pengembalian** — catat transaksi, denda keterlambatan
- **Laporan** — buku terpopuler, anggota aktif, histori peminjaman
- **Export** — laporan ke PDF / Excel

## Tech Stack

- **Backend:** Laravel 12
- **Frontend:** Blade + Livewire 4
- **Database:** MySQL
- **UI:** Tailwind CSS

## Setup

```bash
composer install
cp .env.example .env
# isi konfigurasi database
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
