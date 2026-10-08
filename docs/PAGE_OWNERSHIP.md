# MURIALO - Pembagian Kepemilikan Halaman & Modul (Page Ownership)

Dokumen ini mendokumentasikan pemetaan tanggung jawab implementasi fitur, antarmuka pengguna, controller, dan integrasi backend untuk setiap anggota tim pengembang MURIALO.

---

## 1. Matriks Kepemilikan Tim

| Anggota Tim | Domain Fungsional | Cakupan Modul Utama |
|---|---|---|
| **Khamdanul Jiyad** | Dokumen & Parser CV | Unggah berkas CV, Parser Ekstraksi AI, Verifikasi Entitas & Skills Kandidat, Peninjauan CV Gagal |
| **Muhammad Adi Pratama** | Manajemen Rekrutmen & Asesmen | Pengelolaan Lowongan Kerja, Automated Skill Matching (S-BERT), Ujian Esai Fokus, Bank Soal & Rubrik, Smart Grading |
| **Satyatma Raka Wiratama** | Operasional HR & Rekomendasi | Dashboard Metrik HR, Pelamar & Detail 5-Tab, Kanban Pipeline Seleksi, Algoritma Rekomendasi Talenta (CBF/CF) |
| **Ahmad Sulthon Fajar Nailul Muna** | Keamanan & Integritas | Autentikasi Multi-Peran, Manajemen Pengguna & Hak Akses (RBAC), Deteksi Anomali & Jejak Audit Log |
| **Habib Hadi Widjanarko** | Publik Portal & Analitik Data | Landing Page Korporat, Katalog Karir Publik, Recruitment Analytics (Time Series Forecasting, K-Means Clustering, NLG) |

---

## 2. Rincian Berkas & Rute Per Pengembang

### A. Khamdanul Jiyad
**Fokus**: Pemrosesan berkas resume, integrasi parser FastAPI, dan antarmuka koreksi data ekstraksi.
- **Tampilan Blade**:
  - `resources/views/kandidat/cv.blade.php`: Halaman unggah dan daftar riwayat dokumen CV kandidat.
  - `resources/views/kandidat/cv-ekstraksi.blade.php`: Antarmuka preview hasil pembacaan AI dan konfirmasi keahlian.
  - `resources/views/hr/cv-parser.blade.php`: Monitoring berkas masuk, status parsing, dan penanganan review error.
- **Controller Backend**:
  - `App\Http\Controllers\Kandidat\CvController`
  - `App\Http\Controllers\Hrd\CvController`
- **Rute Web**:
  - `/kandidat/cv`, `/kandidat/cv/ekstraksi`
  - `/hr/cv-parser`
- **Tanggung Jawab Integrasi**:
  - Menghubungkan proses upload berkas ke microservice parser AI (`http://127.0.0.1:8001/api/v1/parse-resume`).
  - Memastikan candidate profile terisi secara otomatis setelah konfirmasi hasil ekstraksi.

---

### B. Muhammad Adi Pratama
**Fokus**: Core workflow rekrutmen, perhitungan kesesuaian semantik, dan evaluasi soal esai bertingkat.
- **Tampilan Blade**:
  - `resources/views/hr/lowongan/index.blade.php`: Daftar lowongan perusahaan.
  - `resources/views/hr/lowongan/create.blade.php` & `edit.blade.php`: Formulir input kriteria skill dan bobot penilaian.
  - `resources/views/hr/skill-matching.blade.php`: Komparasi kandidat berbasis S-BERT dan penelusuran bukti kontekstual.
  - `resources/views/kandidat/tes/index.blade.php` & `hasil.blade.php`: Daftar tugas tes dan umpan balik kandidat.
  - `resources/views/kandidat/tes/kerjakan.blade.php`: Antarmuka ujian fokus bebas distraksi (`layouts.focus`).
  - `resources/views/hr/smart-grading/index.blade.php`: Bank soal esai umum, kunci jawaban rahasia, paket tes, dan penugasan.
  - `resources/views/hr/smart-grading/penilaian.blade.php`: Form evaluasi jawaban, perbandingan prediksi AI vs manual reviewer.
- **Controller Backend**:
  - `App\Http\Controllers\LowonganController`
  - `App\Http\Controllers\SkillMatchingController`
  - `App\Http\Controllers\SoalController`, `PaketSoalController`, `PenugasanController`, `PenilaianController`
- **Rute Web**:
  - `/hr/lowongan/*`
  - `/hr/skill-matching`
  - `/kandidat/tes/*`
  - `/hr/smart-grading/*`
- **Tanggung Jawab Integrasi**:
  - Mengirim payload deskripsi lowongan dan resume ke endpoint S-BERT (`/api/v1/match-skills`).
  - Mengamankan kunci jawaban dan rubrik agar tidak pernah bocor ke view kandidat.
  - Menghubungkan engine NLP Smart Grading untuk memberikan rekomendasi skor awal pada jawaban esai pelamar.

---

### C. Satyatma Raka Wiratama
**Fokus**: Antarmuka kerja harian recruiter, orkestrasi pipeline rekrutmen, dan rekomendasi kandidat.
- **Tampilan Blade**:
  - `resources/views/hr/dashboard.blade.php`: Dashboard metrik KPI, grafik pelamar baru, dan aktivitas seleksi.
  - `resources/views/hr/pelamar/index.blade.php`: Tabel pelamar dengan pencarian, filter tahap, dan sorting.
  - `resources/views/hr/pelamar/show.blade.php`: Detail pelamar bertab (Ringkasan, CV, Skill Match, Tes, Riwayat).
  - `resources/views/hr/pipeline.blade.php`: Kanban board seleksi multi-tahap.
  - `resources/views/hr/rekomendasi.blade.php`: Rekomendasi kandidat hybrid CBF/CF dan penanganan kondisi cold-start.
- **Controller Backend**:
  - `App\Http\Controllers\DashboardController`
  - `App\Http\Controllers\LamaranController`
  - `App\Http\Controllers\RekomendasiController`
- **Rute Web**:
  - `/hr/dashboard`
  - `/hr/pelamar/*`
  - `/hr/pipeline`
  - `/hr/rekomendasi`
- **Tanggung Jawab Integrasi**:
  - Memastikan angka statistik KPI selalu selaras dengan data pelamar aktual.
  - Menyediakan endpoint update tahapan lamaran (Kanban status transition).
  - Mengintegrasikan engine rekomendasi talenta dari model hybrid.

---

### D. Ahmad Sulthon Fajar Nailul Muna
**Fokus**: Manajemen identitas pengguna, hak akses perizinan, integritas data, dan deteksi anomali.
- **Tampilan Blade**:
  - `resources/views/auth/login.blade.php`, `register.blade.php`, `forgot-password.blade.php`, `reset-password.blade.php`: Halaman autentikasi publik.
  - `resources/views/hr/anomali.blade.php`: Deteksi pola janggal kandidat dengan prinsip etika netral dan catatan tindak lanjut HR.
  - `resources/views/admin/users.blade.php`: Manajemen akun pengguna seluruh platform.
  - `resources/views/admin/roles.blade.php`: Matriks perizinan modul RBAC.
  - `resources/views/admin/audit-log.blade.php`: Log jejak audit aktivitas sistem.
- **Controller Backend**:
  - `App\Http\Controllers\AuthController`
  - `App\Http\Controllers\AnomaliController`
  - `App\Http\Controllers\AdminController`
- **Rute Web**:
  - `/login`, `/register`, `/forgot-password`, `/reset-password`
  - `/hr/anomali`
  - `/admin/users`, `/admin/roles`, `/admin/audit-log`
- **Tanggung Jawab Integrasi**:
  - Mengamankan route dengan middleware otorisasi berbasis peran (`role:kandidat`, `role:hr`, `role:admin`).
  - Menghubungkan algoritma deteksi pola anomali dari backend / AI engine.
  - Mengisi catatan tindak lanjut peninjauan HR ke tabel database audit.

---

### E. Habib Hadi Widjanarko
**Fokus**: Pengalaman publik pencari kerja, identitas visual brand MURIALO, dan analitik bisnis eksekutif.
- **Tampilan Blade**:
  - `resources/views/landing.blade.php`: Halaman utama publik MURIALO (Hero, Fitur, Lowongan Pilihan, Alur Seleksi, FAQ, Footer).
  - `resources/views/karir/index.blade.php`: Katalog lowongan kerja publik dengan filter departemen dan lokasi.
  - `resources/views/karir/show.blade.php`: Halaman detail lowongan dan modal lamaran kerja.
  - `resources/views/hr/analitik.blade.php`: Visualisasi Time Series Forecasting, K-Means Clustering scatter chart, dan ringkasan teks NLG.
  - `resources/views/admin/settings.blade.php`: Pengaturan profil organisasi dan parameter rekrutmen.
- **Controller Backend**:
  - `App\Http\Controllers\LandingController`
  - `App\Http\Controllers\KarirController`
  - `App\Http\Controllers\AnalitikController`
- **Rute Web**:
  - `/`, `/karir`, `/karir/{id}`
  - `/hr/analitik`
  - `/admin/settings`
- **Tanggung Jawab Integrasi**:
  - Mengintegrasikan feed data lowongan aktif ke halaman landing dan katalog publik.
  - Menghubungkan data historis lamaran ke model peramalan Time Series dan klastering K-Means.
  - Menghubungkan service NLG untuk menghasilkan ringkasan naratif otomatis mingguan/bulanan.
