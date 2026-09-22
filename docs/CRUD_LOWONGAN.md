# CRUD Lowongan Murialo

Modul ini dibuat di branch `fitur/crud-lowongan`. Dokumentasi setup yang tersedia di workspace adalah `DOKUMENTASI_SETUP_MURIALO.md`.

## Menjalankan

1. Jalankan `composer install` di folder `murialo-web`.
2. Bila `.env` belum ada, salin `.env.example` menjadi `.env`, lalu jalankan `php artisan key:generate`.
3. Atur koneksi MySQL `murialo_web` di `.env` sesuai dokumentasi setup tim.
4. Jalankan `php artisan migrate` (menambahkan tabel `lowongan`; tabel `jobs` tetap untuk queue Laravel).
5. Jika belum punya akun di tabel `users`, jalankan `php artisan lowongan:user` untuk membuat akun lokal dengan password pilihan sendiri.
6. Jalankan `php artisan serve`, lalu buka **http://127.0.0.1:8000/lowongan**. Masukkan email dan password akun tersebut pada dialog browser.

CSS tersedia langsung di `public/css/lowongan.css`, sehingga modul ini tidak membutuhkan build npm.

## Fitur

- Tambah, daftar, detail, edit, dan hapus dengan konfirmasi dua langkah (buka bagian Hapus, lalu konfirmasi).
- Pencarian judul/perusahaan/lokasi, filter status, dan pagination 10 data.
- Status: `draft`, `aktif`, `ditutup`. Tipe: `full_time`, `part_time`, `kontrak`, `magang`.
- Gaji opsional dalam rupiah per bulan, bilangan bulat nonnegatif; maksimum harus >= minimum jika keduanya diisi.
- Batas lamaran opsional. Tanggal lewat tetap dapat disimpan agar lowongan historis dapat diedit/ditutup. Status tidak otomatis berubah saat tenggat lewat.
- CSRF, validasi server, output Blade di-escape, dan akses hanya untuk pemilik.

## Integrasi dengan fitur auth dan AI

Auth tim belum ada pada snapshot proyek ini. Sementara semua resource `/lowongan` dilindungi `auth.basic`, memakai provider `users` Laravel. Gunakan HTTPS jika diakses selain localhost. Browser menyimpan kredensial Basic Auth; tutup sesi browser/private window untuk berganti akun.

Setelah fitur auth selesai, ganti middleware di `routes/web.php` menjadi middleware login dan role HRD milik tim. Saat ini belum ada kolom role: semua akun terautentikasi dapat mengelola lowongan sendiri. Periksa ownership tetap dipertahankan. Tidak ada daftar lowongan publik dalam modul manajemen ini.

Data tersimpan di tabel **`lowongan`** dengan FK `user_id` ke `users`. Model Laravel: `App\Models\Lowongan`. Kolom `skills` berupa teks dipisahkan koma untuk input modul skill matching; `deskripsi` dan `persyaratan` adalah teks biasa. AI engine dapat membaca tabel ini di database bersama. Penghapusan user menghapus lowongan miliknya melalui FK cascade.

## Pengujian

Jalankan `php artisan test`. Feature tests memakai SQLite in-memory dari `phpunit.xml` dan tidak mengubah database aplikasi. Pengujian mencakup CRUD, ownership, Basic Auth, validasi, filter, pagination, data opsional, 404, dan escaping HTML.

Alur manual: tambah draft → buka detail → edit status aktif → cari/filter → buka Hapus lowongan → konfirmasi. Coba gaji maksimum lebih kecil dari minimum untuk memastikan validasi tampil dan input tetap terisi.
