# MURIALO - Peta Situs & Alur Navigasi (Sitemap)

Dokumen ini memetakan seluruh hierarki halaman, URL web, nama route Laravel, layout yang digunakan, dan deskripsi fungsinya.

---

## 1. Modul Publik & Portal Karir

| Halaman | URL | Route Name | Layout | Deskripsi |
|---|---|---|---|---|
| **Landing Page** | `/` | `landing` | `layouts.public` | Pengenalan platform MURIALO, hero banner, fitur unggulan, lowongan pilihan, alur seleksi, FAQ, dan footer. |
| **Katalog Karir** | `/karir` | `karir.index` | `layouts.public` | Direktori pencarian lowongan kerja publik dengan filter departemen, tipe pekerjaan, dan lokasi. |
| **Detail Lowongan** | `/karir/{id}` | `karir.show` | `layouts.public` | Deskripsi posisi, kualifikasi wajib/opsional, benefit, serta modal interaktif untuk melamar kerja. |

---

## 2. Modul Autentikasi

| Halaman | URL | Route Name | Layout | Deskripsi |
|---|---|---|---|---|
| **Login** | `/login` | `login` | `layouts.auth` | Masuk ke sistem dengan email & sandi + Quick Role Switcher (Kandidat, HR, Admin) untuk kemudahan demo. |
| **Registrasi** | `/register` | `register` | `layouts.auth` | Pendaftaran akun baru kandidat pencari kerja. |
| **Lupa Password** | `/forgot-password` | `password.request` | `layouts.auth` | Formulir pengiriman tautan pemulihan sandi via email. |
| **Reset Password** | `/reset-password/{token}` | `password.reset` | `layouts.auth` | Pengaturan ulang kata sandi baru. |

---

## 3. Modul Area Kandidat

| Halaman | URL | Route Name | Layout | Deskripsi |
|---|---|---|---|---|
| **Dashboard Kandidat** | `/kandidat/dashboard` | `kandidat.dashboard` | `layouts.kandidat` | Ringkasan kelengkapan profil, CV aktif, lamaran aktif, tes esai yang perlu dikerjakan, dan langkah berikutnya. |
| **Profil & Pengaturan** | `/kandidat/profil` | `kandidat.profil` | `layouts.kandidat` | Formulir data diri, kontak, media sosial, dan riwayat ringkas kandidat. |
| **CV Saya** | `/kandidat/cv` | `kandidat.cv` | `layouts.kandidat` | Manajemen berkas CV, upload dokumen PDF/Word, dan status pemrosesan parser. |
| **Preview Ekstraksi CV** | `/kandidat/cv/ekstraksi` | `kandidat.cv.ekstraksi` | `layouts.kandidat` | Hasil pembacaan AI: verifikasi data pendidikan, pengalaman, dan konfirmasi keahlian (skills). |
| **Jelajah Lowongan** | `/kandidat/lowongan` | `kandidat.lowongan` | `layouts.kandidat` | Pencarian lowongan internal kandidat dengan filter dan tombol melamar langsung. |
| **Lamaran Saya** | `/kandidat/lamaran` | `kandidat.lamaran.index` | `layouts.kandidat` | Daftar seluruh riwayat lowongan yang pernah dilamar beserta status seleksi saat ini. |
| **Detail Lamaran** | `/kandidat/lamaran/{id}` | `kandidat.lamaran.show` | `layouts.kandidat` | Pelacakan linimasa (timeline) tahapan seleksi dari Berkas Masuk hingga Offering. |
| **Tugas Tes Esai** | `/kandidat/tes` | `kandidat.tes.index` | `layouts.kandidat` | Daftar paket tes yang ditugaskan kepada kandidat beserta tenggat waktu pengerjaan. |
| **Pengerjaan Tes (Zen)** | `/kandidat/tes/{id}/kerjakan` | `kandidat.tes.kerjakan` | `layouts.focus` | Halaman ujian fokus bebas distraksi, navigasi nomor soal, editor jawaban, autosave, dan timer hitung mundur. |
| **Hasil & Konfirmasi Tes** | `/kandidat/tes/{id}/hasil` | `kandidat.tes.hasil` | `layouts.kandidat` | Konfirmasi pengumpulan tes, status review tim HR, dan umpan balik yang diizinkan untuk kandidat. |

---

## 4. Modul Area Recruiter / HR

### A. Ringkasan & Operasional Rekrutmen
| Halaman | URL | Route Name | Layout | Deskripsi |
|---|---|---|---|---|
| **Dashboard HR** | `/hr/dashboard` | `hr.dashboard` | `layouts.hr` | Metrik KPI rekrutmen, grafik tren lamaran, pelamar terbaru, aktivitas seleksi, dan daftar to-do HR. |
| **Kelola Lowongan** | `/hr/lowongan` | `hr.lowongan.index` | `layouts.hr` | Tabel daftar lowongan kerja perusahaan dengan status (Publik/Draft) dan jumlah pelamar. |
| **Tambah Lowongan** | `/hr/lowongan/create` | `hr.lowongan.create` | `layouts.hr` | Form pembuatan lowongan baru beserta kriteria skill wajib/opsional dan bobot evaluasi. |
| **Edit Lowongan** | `/hr/lowongan/{id}/edit` | `hr.lowongan.edit` | `layouts.hr` | Form pembaharuan data posisi dan kriteria spesifikasi. |
| **Daftar Pelamar** | `/hr/pelamar` | `hr.pelamar.index` | `layouts.hr` | Tabel komprehensif pelamar kerja dengan pencarian, multi-filter tahap, sorting skor, dan pagination. |
| **Detail Pelamar (Tab)** | `/hr/pelamar/{id}` | `hr.pelamar.show` | `layouts.hr` | Detail 5 tab: (1) Ringkasan Profil, (2) CV Dokumen, (3) Skill Matching, (4) Hasil Tes Esai, (5) Riwayat Status. |
| **Kanban Pipeline** | `/hr/pipeline` | `hr.pipeline` | `layouts.hr` | Papan interaktif tahapan seleksi (Seleksi Berkas, Tes Esai, Wawancara, Penawaran, Diterima) dengan simulasi drag & move. |

### B. Evaluasi & Modul AI Khusus
| Halaman | URL | Route Name | Layout | Deskripsi |
|---|---|---|---|---|
| **Resume Parser** | `/hr/cv-parser` | `hr.cv_parser` | `layouts.hr` | Monitoring berkas CV masuk, status parsing AI (Selesai/Gagal), preview entitas terdeteksi, dan verifikasi manual. |
| **Skill Matching** | `/hr/skill-matching` | `hr.skill_matching` | `layouts.hr` | Analisis kesesuaian berbasis S-BERT, perbandingan skill wajib/opsional, bukti kontekstual, dan komparasi kandidat. |
| **Smart Grading Test** | `/hr/smart-grading` | `hr.smart_grading.index` | `layouts.hr` | Bank soal esai umum, pembuatan kunci jawaban/rubrik rahasia, paket tes, dan penugasan ke kandidat. |
| **Penilaian Reviewer** | `/hr/smart-grading/{id}/penilaian` | `hr.smart_grading.penilaian` | `layouts.hr` | Evaluasi jawaban esai: perbandingan rekomendasi AI vs penilaian manual reviewer, rubrik, dan finalisasi nilai. |
| **Rekomendasi Talenta** | `/hr/rekomendasi` | `hr.rekomendasi` | `layouts.hr` | Algoritma pemeringkatan hybrid Content-Based Filtering (CBF) & Collaborative Filtering (CF) + fallback kondisi cold-start. |
| **Deteksi Anomali** | `/hr/anomali` | `hr.anomali` | `layouts.hr` | Monitoring sinyal inkonsistensi data kandidat dengan bahasa netral, skor risiko, dan form catatan klarifikasi HR. |
| **Analitik Rekrutmen** | `/hr/analitik` | `hr.analitik` | `layouts.hr` | Time Series Forecasting (12 minggu historis + 4 minggu peramalan), K-Means Clustering talenta, dan ringkasan eksekutif NLG. |

---

## 5. Modul Area Administrator

| Halaman | URL | Route Name | Layout | Deskripsi |
|---|---|---|---|---|
| **Kelola Pengguna** | `/admin/users` | `admin.users` | `layouts.admin` | Manajemen akun kandidat, HR, dan admin, pengubahan peran, dan aktivasi/nonaktif akun. |
| **Hak Akses & Role** | `/admin/roles` | `admin.roles` | `layouts.admin` | Matriks perizinan RBAC (Role-Based Access Control) untuk menjamin pemisahan wewenang. |
| **Pengaturan Platform** | `/admin/settings` | `admin.settings` | `layouts.admin` | Profil organisasi PT Muria Logika Nusantara, endpoint microservice AI (Port 8001), dan batas berkas. |
| **Audit Log** | `/admin/audit-log` | `admin.audit_log` | `layouts.admin` | Pencatatan riwayat aktivitas pengguna, perubahan status pelamar, evaluasi nilai, dan jejak IP address. |

---

## 6. Alur Kerja Utama Sistem (User Journey)

### Alur 1: Kandidat Melamar Pekerjaan
1. Kandidat membuka **Landing Page (`/`)** atau **Katalog Karir (`/karir`)**.
2. Memilih posisi yang diminati dan membuka **Detail Lowongan (`/karir/{id}`)**.
3. Menekan tombol **Lamar Sekarang** -> Dialog modal konfirmasi lamaran terbuka.
4. Kandidat masuk ke **Dashboard Kandidat (`/kandidat/dashboard`)** untuk melengkapi profil dan memeriksa **CV Saya (`/kandidat/cv`)**.
5. Melihat hasil ekstraksi AI pada **Preview Ekstraksi CV (`/kandidat/cv/ekstraksi`)** dan memverifikasi daftar skill.
6. Memantau progres seleksi pada menu **Lamaran Saya (`/kandidat/lamaran`)**.
7. Mengerjakan tes evaluasi pada **Pengerjaan Tes (`/kandidat/tes/{id}/kerjakan`)** dengan antarmuka fokus bebas distraksi.

### Alur 2: Recruiter / HR Mengelola Rekrutmen & Seleksi AI
1. HR login ke **Dashboard HR (`/hr/dashboard`)** dan melihat ringkasan KPI serta to-do list.
2. Membuka **Pipeline Kanban (`/hr/pipeline`)** atau **Daftar Pelamar (`/hr/pelamar`)**.
3. Membuka **Detail Pelamar (`/hr/pelamar/{id}`)** untuk melihat CV asli, hasil analisis **Skill Matching (S-BERT)**, dan histori jawaban tes.
4. Meninjau evaluasi tes esai di **Penilaian Smart Grading (`/hr/smart-grading/{id}/penilaian`)** guna memvalidasi skor AI dan memberikan nilai final.
5. Memeriksa sinyal verifikasi di **Deteksi Anomali (`/hr/anomali`)** sebelum menjadwalkan wawancara akhir.
6. Memantau efisiensi seleksi dan proyeksi beban kerja tim di **Analitik Rekrutmen (`/hr/analitik`)**.
