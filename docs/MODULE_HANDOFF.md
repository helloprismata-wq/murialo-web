# MURIALO - Dokumen Serah Terima Modul (Module Handoff)

Dokumen ini memetakan status implementasi UI Foundation, titik integrasi backend, dan kriteria penerimaan (*Acceptance Criteria*) untuk setiap modul aplikasi.

---

## 1. Status Implementasi Global

| Komponen | Status UI Foundation | Catatan Kesiapan |
|---|---|---|
| **Sistem Desain & Token** | **Selesai (Production Ready)** | Tailwind CSS v4, font Plus Jakarta Sans, palet warna baku (`#2563EB`), responsif mobile-desktop. |
| **Blade Reusable Components** | **Selesai (Production Ready)** | 14 komponen siap pakai (`button`, `input`, `badge`, `modal`, `tabs`, dll) siap digunakan oleh modul apa pun. |
| **Data Provider Sentral** | **Selesai (Demo Dataset)** | `DemoDataProvider` melayani 15 kandidat, 6 lowongan, 25 lamaran seleksi, time series, dan klaster K-Means. |
| **FastAPI Microservice** | **Siap Dihubungkan** | Server FastAPI telah berjalan di port 8001. Mode demo saat ini beroperasi mandiri tanpa memblokir UI jika service offline. |

---

## 2. Rincian Serah Terima Per Modul

### Modul A: Unggah CV & Resume Parser
- **Penanggung Jawab**: Khamdanul Jiyad
- **Halaman Terkait**:
  - Kandidat: `/kandidat/cv`, `/kandidat/cv/ekstraksi`
  - HR: `/hr/cv-parser`
- **Titik Integrasi Backend**:
  - Controller: `App\Http\Controllers\Kandidat\CvController` & `Hrd\CvController`
  - Rute asli backend: `route('cv.upload')`, `route('hrd.cv.index')`
  - Endpoint AI: `POST http://127.0.0.1:8001/api/v1/parse-resume`
- **Acceptance Criteria**:
  1. File CV (PDF / DOCX) berhasil diunggah ke `storage/app/public/cv/` dengan validasi ukuran maks 5 MB.
  2. Background job atau HTTP client mengirimkan dokumen ke microservice FastAPI untuk ekstraksi teks entitas.
  3. Hasil ekstraksi (pendidikan, pengalaman, sertifikasi, skills) disimpan ke tabel database dan ditampilkan di halaman verifikasi kandidat.
  4. Kandidat dapat mengonfirmasi atau mengoreksi skill yang salah terdeteksi sebelum disimpan final.
  5. Jika parsing gagal, status ditandai `Gagal` dengan opsi upload ulang atau review manual oleh staf HR.

---

### Modul B: Lowongan Kerja, Automated Skill Matching, Tes Esai, & Smart Grading
- **Penanggung Jawab**: Muhammad Adi Pratama
- **Halaman Terkait**:
  - Publik/Kandidat: `/karir`, `/karir/{id}`, `/kandidat/tes`, `/kandidat/tes/{id}/kerjakan`
  - HR: `/hr/lowongan/*`, `/hr/skill-matching`, `/hr/smart-grading/*`
- **Titik Integrasi Backend**:
  - Controller: `LowonganController`, `SoalController`, `PaketSoalController`, `PenugasanController`, `PenilaianController`, `SkillMatchingController`
  - Rute asli backend: `lowongan.*`, `soal.*`, `paket.*`, `penugasan.*`, `penilaian.*`
  - Endpoint AI: `POST /api/v1/match-skills` (S-BERT), `POST /api/v1/grade-essay` (NLP Smart Grading)
- **Acceptance Criteria**:
  1. HR dapat mengelola lowongan (CRUD), menentukan kualifikasi skill wajib/opsional, dan mengubah status publikasi.
  2. Modul skill matching menghitung skor kemiripan semantik (S-BERT) antara deskripsi lowongan dan CV pelamar. Label ditampilkan sebagai **Kesesuaian**, bukan probabilitas diterima.
  3. Kandidat dapat mengerjakan soal esai pada antarmuka fokus (`layouts.focus`) dengan timer berjalan dan simulasi autosave jawaban.
  4. Kandidat **tidak boleh** menerima kunci jawaban, bobot rubrik tersembunyi, atau catatan internal reviewer dalam HTML/JSON response.
  5. Halaman evaluasi HR menampilkan skor prediksi AI dan kolom input nilai manual reviewer secara terpisah, disertai catatan evaluator sebelum finalisasi nilai.

---

### Modul C: Dashboard HR & Rekomendasi Kandidat
- **Penanggung Jawab**: Satyatma Raka Wiratama
- **Halaman Terkait**:
  - HR: `/hr/dashboard`, `/hr/rekomendasi`, `/hr/pelamar`, `/hr/pipeline`
- **Titik Integrasi Backend**:
  - Controller: `DashboardController`, `RekomendasiController`, `LamaranController`
  - Rute asli backend: `hr.dashboard`, `hr.rekomendasi`, `hr.pelamar.*`
  - Endpoint AI: `POST /api/v1/recommend-candidates` (Hybrid Content-Based & Collaborative Filtering)
- **Acceptance Criteria**:
  1. Dashboard HR menampilkan metrik KPI yang sinkron dan konsisten dari database: Lowongan Aktif, Total Lamaran, Tes Menunggu Review, dan Diterima.
  2. Pipeline seleksi memungkinkan pemindahan status kandidat (Seleksi Berkas -> Tes Esai -> Wawancara -> Offering -> Diterima/Ditolak).
  3. Modul rekomendasi kandidat menampilkan ranking talenta terbaik untuk lowongan yang dipilih beserta rincian skor CBF dan skor interaksi (CF).
  4. Sediakan fallback UI yang elegan jika data interaksi belum mencukupi (kondisi *cold-start* collaborative filtering), dengan mengandalkan murni skor CBF.

---

### Modul D: Autentikasi & Deteksi Anomali
- **Penanggung Jawab**: Ahmad Sulthon Fajar Nailul Muna
- **Halaman Terkait**:
  - Publik: `/login`, `/register`, `/forgot-password`, `/reset-password`
  - HR: `/hr/anomali`
  - Admin: `/admin/users`, `/admin/roles`
- **Titik Integrasi Backend**:
  - Controller: `AuthController`, `AnomaliController`, `AdminController`
  - Rute asli backend: `login`, `register`, `password.*`
  - Endpoint AI: `POST /api/v1/detect-anomalies`
- **Acceptance Criteria**:
  1. Autentikasi multi-peran (kandidat, recruiter HR, admin) dengan password hashing `bcrypt` dan session management yang aman.
  2. Deteksi anomali menyajikan indikator inkonsistensi data (seperti overlap tanggal kerja yang tidak wajar atau anomali kecepatan pengisian tes) dengan bahasa netral dan objektif (tanpa tuduhan langsung berbuat curang).
  3. HR dapat mencatat hasil verifikasi faktual dan mengubah status peninjauan: `Belum Ditinjau`, `Sedang Ditinjau`, atau `Selesai`.
  4. Matriks otorisasi membatasi akses peran: kandidat tidak boleh mengakses halaman evaluasi HR atau konfigurasi administrator.

---

### Modul E: Landing Page & Analitik Rekrutmen
- **Penanggung Jawab**: Habib Hadi Widjanarko
- **Halaman Terkait**:
  - Publik: `/`, `/karir`
  - HR: `/hr/analitik`
- **Titik Integrasi Backend**:
  - Controller: `LandingController`, `AnalitikController`
  - Rute asli backend: `landing`, `karir.*`, `hr.analitik`
  - Endpoint AI: `POST /api/v1/forecast-applications` (Time Series), `POST /api/v1/cluster-candidates` (K-Means), `POST /api/v1/generate-summary` (NLG)
- **Acceptance Criteria**:
  1. Landing page memuat identitas korporat MURIALO yang elegan, dual user-journey CTA (kandidat melamar dan perusahaan merekrut), lowongan unggulan, alur seleksi, serta FAQ responsif.
  2. Grafik Time Series pada modul analitik menampilkan visual pembeda yang tegas antara data historis (garis solid) dan proyeksi peramalan masa depan (garis putus-putus).
  3. Visualisasi scatter chart mengelompokkan kandidat ke dalam 3 klaster K-Means berdasarkan skor kompetensi dan pengalaman.
  4. Komponen Natural Language Generation (NLG) merangkum intisari laporan analitik dalam paragraf narasi eksekutif yang mudah dipahami manajemen.
