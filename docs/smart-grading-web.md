# Dokumentasi Fitur Web Smart Grading (Jawaban Singkat)

Dokumen ini menjelaskan implementasi fitur web untuk tes jawaban singkat (*short-answer test*) pada aplikasi rekrutmen **Murialo**, yang dirancang untuk mendukung integrasi *Smart Grading* AI pada fase berikutnya.

> **Batasan Fase Ini:**
> - Penilaian dilakukan secara **manual oleh HR** berdasarkan rubrik penilaian.
> - Jawaban kandidat disimpan secara terstruktur dan terisolasi agar siap dikonsumsi AI Engine pada tahap selanjutnya.
> - Belum ada koneksi HTTP, FastAPI, model SBERT, atau skor AI palsu.

---

## 1. Fitur yang Telah Diimplementasikan

### A. Bank Soal untuk HR (`/soal`)
1. **CRUD Lengkap**: Daftar soal, tambah, detail, edit, dan pengarsipan.
2. **Atribut Soal**:
   - Kode unik (misal: `DEMO-INF-01`).
   - Judul & Kategori Kemampuan (`pemahaman_informasi`, `instruksi`, `penalaran`).
   - Teks bacaan / konteks (opsional jika soal membutuhkan teks rujukan).
   - Pertanyaan jawaban singkat.
   - Skor maksimum.
   - Contoh jawaban acuan (kunci jawaban HR).
   - Rubrik penilaian (kriteria, poin maksimal, dan deskripsi panduan).
   - Penanda data dummy demo (`is_dummy`).
   - Status (`draft`, `aktif`, `arsip`).
   - Versi soal (`versi`).
3. **Validasi Rubrik**: Jumlah poin seluruh kriteria rubrik harus sama persis dengan skor maksimum soal.
4. **Proteksi & Isolasi Kunci Jawaban**: Jawaban acuan dan rubrik penilaian hanya dapat dilihat oleh pengguna dengan peran HR dan **tidak pernah dikirimkan** ke antarmuka kandidat (baik melalui Blade, JSON, HTML attributes, maupun hidden input).
5. **Snapshot & Integritas Riwayat**: Setiap pembaruan soal menaikkan versi dan menyimpan snapshot lengkap ke tabel `soal_snapshots`. Soal yang telah digunakan dalam tes tidak dapat dihapus permanen agar tidak merusak riwayat pengerjaan.

### B. Paket Tes (`/paket`)
1. **CRUD Paket Tes**: Pengelompokan soal menjadi satu instrumen seleksi dengan durasi pengerjaan (menit), deskripsi, dan petunjuk.
2. **Urutan Pengerjaan**: Mengatur nomor urut soal di dalam paket.
3. **Total Skor Otomatis**: Menghitung akumulasi skor maksimum dari butir soal yang dipilih.
4. **Relasi Lowongan**: Satu paket tes dapat dihubungkan ke beberapa lowongan kerja yang relevan.
5. **Validasi Aktivasi**: Paket hanya dapat diaktifkan jika memiliki butir soal yang berstatus aktif dan seluruh rubriknya valid.
6. **Freeze Snapshot**: Saat ditugaskan, paket tes membekukan snapshot seluruh butir soal, urutan, rubrik, dan durasi ke dalam JSON snapshot penugasan.

### C. Penugasan Tes (`/penugasan`)
1. **Penugasan Berbasis Lamaran**: HR menugaskan paket tes aktif kepada pelamar pekerjaan.
2. **Periode Tes**: Mengatur waktu mulai tersedia (`waktu_tersedia`) dan batas akhir pengumpulan (`batas_waktu`).
3. **Pencegahan Penugasan Ganda**: Dilindungi oleh *unique database constraint* pada kombinasi `[lamaran_id, paket_tes_id]` dan transaksi database dengan *pessimistic lock*.
4. **Satu Kesempatan Pengerjaan**: Satu penugasan hanya dapat memiliki 1 percobaan pengerjaan.

### D. Portal Kandidat (`/tes-saya`)
1. **Daftar Tes Saya**: Menampilkan tes yang ditugaskan kepada kandidat bersangkutan.
2. **Lembar Petunjuk**: Informasi durasi, tata cara, dan tombol konfirmasi sebelum memulai tes.
3. **Antarmuka Pengerjaan Tes (`/tes-saya/{id}/kerjakan`)**:
   - Bacaan konteks dan pertanyaan per nomor.
   - Textarea pengisian jawaban singkat.
   - Navigasi nomor soal dengan penanda status (Sudah Dijawab / Belum Dijawab).
   - **Autosave Berkala**: Menyimpan jawaban secara asinkron (AJAX) setiap ada pengetikan (debounced) dengan indikator status live.
   - **Sinkronisasi Waktu Server**: Waktu akhir dihitung dari `min(waktu_mulai + durasi, batas_waktu_penugasan)`. Refresh browser tidak mereset timer.
   - **Finalisasi Otomatis**: Jika waktu habis, form langsung difinalisasi dan perubahan berikutnya ditolak.
   - **Kunci Jawaban**: Setelah disubmit, seluruh jawaban kandidat terkunci.

### E. Penilaian Manual oleh HR (`/penilaian`)
1. **Pemisahan Status**:
   - Status Pengerjaan: `belum_dimulai`, `sedang_mengerjakan`, `selesai`, `kedaluwarsa`.
   - Status Penilaian: `belum_dinilai`, `sedang_dinilai`, `selesai_dinilai`.
2. **Lembar Pemeriksaan**:
   - Menampilkan jawaban kandidat per soal bersandingan dengan jawaban acuan dan rubrik HR.
   - Input skor per kriteria rubrik dengan validasi batas skor (0 ≤ skor ≤ skor kriteria).
   - Kalkulasi otomatis total skor diperoleh dan nilai akhir skala 100:  
     $$\text{Nilai Akhir} = \left(\frac{\text{Total Skor Diperoleh}}{\text{Total Skor Maksimum}}\right) \times 100$$
   - Catatan evaluasi umum dan catatan per kriteria.
3. **Publikasi Hasil Terpisah**:
   - Penilaian yang selesai disimpan berstatus internal HR.
   - Kandidat baru dapat melihat nilai akhir setelah HR menekan tombol **Publikasikan Hasil ke Kandidat**.
4. **Audit Trail Revisi Nilai**:
   - Jika penilaian yang sudah selesai diubah, sistem mewajibkan pengisian **Alasan Perubahan Nilai**.
   - Perubahan skor lama, skor baru, alasan, identitas penilai, dan timestamp dicatat ke tabel `penilaian_riwayat`.

---

## 2. Struktur Data Utama (Database Schema)

| Tabel | Deskripsi |
|---|---|
| `users` | Menambahkan kolom `role` (`hr` atau `kandidat`) |
| `lamaran` | Berkas lamaran kandidat untuk lowongan (`lowongan_id`, `user_id`, `status`) |
| `soal` | Bank soal jawaban singkat (`kode_soal`, `judul`, `kategori`, `pertanyaan`, `skor_maksimum`, `versi`) |
| `soal_jawaban_acuan` | Contoh jawaban acuan referensi HR |
| `soal_rubrik` | Kriteria penilaian dan batas poin rubrik per soal |
| `soal_snapshots` | Arsip versi soal untuk menjaga integritas riwayat |
| `paket_tes` | Paket kumpulan soal dan durasi pengerjaan |
| `paket_tes_soal` | Pivot relasi paket dan soal beserta urutannya (`urutan`) |
| `lowongan_paket_tes` | Pivot relasi lowongan dengan paket tes |
| `penugasan_tes` | Penugasan paket ke lamaran dengan snapshot paket terbekukan |
| `percobaan_tes` | Percobaan pengerjaan kandidat, jam mulai, jam berakhir, jam selesai |
| `jawaban_kandidat` | Jawaban singkat kandidat per nomor soal |
| `penilaian` | Rekapitulasi penilaian manual HR dan nilai akhir |
| `penilaian_detail` | Skor per kriteria rubrik yang diberikan penilai |
| `penilaian_riwayat` | Catatan audit trail revisi skor yang mewajibkan alasan perubahan |

---

## 3. Cara Menjalankan Migration dan Seeder

### Menjalankan Migrasi Tambahan
Migrasi dirancang aman tanpa menghapus data yang sudah ada:
```bash
php artisan migrate
```

### Menjalankan Seeder Demo Data
Seeder `SmartGradingDemoSeeder` bersifat **idempotent** (dapat dijalankan berulang kali tanpa membuat duplikasi data):
```bash
php artisan db:seed --class=SmartGradingDemoSeeder
```

Seeder demo akan membuat:
- 10 butir soal jawaban singkat bahasa Indonesia (4 pemahaman informasi, 3 pemahaman instruksi, 3 penalaran).
- 2 paket tes aktif (Paket A: 25 menit, Paket B: 30 menit).
- Akun kandidat demo:
  - `budi.kandidat@murialo.test` (password: `password`) — sudah selesai tes dan dinilai.
  - `siti.kandidat@murialo.test` (password: `password`) — tes belum dimulai, siap dikerjakan.

---

## 4. Cara Menjalankan Scheduler

Untuk memfinalisasi otomatis tes kandidat yang telah melewati batas waktu pengerjaan:

Jalankan perintah manual:
```bash
php artisan tes:finalize-expired
```

Atau jalankan daemon worker scheduler Laravel:
```bash
php artisan schedule:work
```

---

## 5. Alur Penggunaan (User Journey)

### Alur HR:
1. **Membuat Soal**: Buka menu **Bank Soal** (`/soal`) → Klik **+ Buat Soal Baru** → Masukkan teks bacaan, pertanyaan, contoh jawaban acuan, dan tentukan rubrik penilaian (pastikan total poin rubrik sama dengan skor maksimum).
2. **Merakit Paket Tes**: Buka menu **Paket Tes** (`/paket`) → Klik **+ Buat Paket Tes** → Tentukan nama, durasi (menit), petunjuk pengerjaan, pilih butir soal, dan hubungkan ke lowongan yang sesuai → Simpan sebagai **Aktif**.
3. **Menugaskan ke Pelamar**: Buka menu **Penugasan** (`/penugasan`) → Klik **+ Berikan Tes ke Kandidat** → Pilih lamaran pelamar dan paket tes yang ditugaskan → Tentukan jadwal ketersediaan dan deadline.
4. **Memeriksa Jawaban**: Setelah kandidat menyelesaikan tes, buka menu **Hasil Tes** (`/penilaian`) → Klik **Nilai Sekarang** pada kandidat yang telah selesai.
5. **Memberi Skor Rubrik**: Bandingkan jawaban kandidat dengan jawaban acuan HR → Beri skor pada setiap kriteria rubrik → Simpan sebagai Draf atau klik **Selesaikan Penilaian**.
6. **Publikasi Nilai**: Pada halaman rincian hasil tes, klik tombol **Publikasikan Hasil ke Kandidat**.

### Alur Kandidat:
1. Login menggunakan akun kandidat (misal: `siti.kandidat@murialo.test` / `password`).
2. Masuk ke menu **Tes Saya** (`/tes-saya`).
3. Pilih tes yang tersedia → Baca petunjuk pengerjaan dan aturan durasi.
4. Klik **Mulai Kerjakan Tes Sekarang** (konfirmasi dialog).
5. Pada lembar tes:
   - Jawab pertanyaan pada textarea yang disediakan.
   - Jawaban tersimpan otomatis secara berkala (lihat indikator *Tersimpan otomatis*).
   - Perhatikan sisa waktu pada timer di pojok kanan atas.
   - Navigasi antar soal menggunakan tombol navigasi nomor di sidebar atau tombol Sebelumnya/Berikutnya.
6. Klik **Kirim Jawaban Tes** dan konfirmasi pengiriman.
7. Status tes akan berubah menjadi *Selesai* (menunggu penilaian HR). Nilai akhir akan muncul setelah dipublikasikan oleh HR.

---

## 6. Cara Menjalankan Pengujian (Automated Tests)

Jalankan seluruh pengujian fitur Smart Grading:
```bash
php artisan test --filter=SmartGradingTest
```

Jalankan seluruh test suite proyek:
```bash
php artisan test
```

### Lingkup Pengujian yang Dicakup:
- `access control hr vs kandidat`: Memastikan kandidat tidak bisa mengakses halaman HR dan sebaliknya.
- `soal and rubrik validation`: Memastikan jumlah poin rubrik harus sama dengan skor maksimum.
- `soal versioning and snapshot preservation`: Memastikan pengeditan soal tidak mengubah snapshot tes yang telah ditugaskan.
- `candidate cannot access another candidates test`: Isolasi hak akses antar kandidat.
- `answer keys and rubrics are never leaked to candidate`: Memastikan kunci jawaban dan rubrik tidak bocor ke browser kandidat.
- `autosave and submission`: Memastikan fitur autosave AJAX dan pengiriman jawaban bekerja sempurna.
- `answers are locked after submit or expiration`: Memastikan jawaban terkunci setelah submit atau waktu habis.
- `automatic finalization of expired tests`: Memastikan perintah finalisasi otomatis berjalan tepat.
- `duplicate assignment prevention`: Memastikan tidak terjadi penugasan ganda.
- `manual scoring limits and calculation`: Memastikan skor tidak melebihi batas rubrik, tidak negatif, dan formula nilai akhir dihitung tepat.
- `results are only visible to candidate after publication`: Memastikan nilai hanya terlihat oleh kandidat setelah dipublikasikan.
- `score modification requires reason and logs history`: Memastikan perubahan penilaian tercatat di riwayat audit trail dengan alasan revisi.
