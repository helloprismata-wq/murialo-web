# MURIALO - Kontrak Data Antarmuka (Data Contracts)

Dokumen ini mendefinisikan struktur objek data (DTO / Models) yang diharapkan oleh antarmuka frontend MURIALO. Seluruh field diselaraskan dengan arsitektur backend Laravel dan skema database yang telah dirancang.

---

## 1. Entitas Lowongan Kerja (`Lowongan`)

Digunakan pada: Landing page, Katalog Karir, Manajemen HR, dan Skill Matching.

```json
{
  "id": 1,
  "judul": "Senior Fullstack Developer (Laravel & Vue/React)",
  "departemen": "Engineering",
  "lokasi": "Jakarta Selatan (Hybrid)",
  "tipe": "Full-time",
  "gaji": "Rp 18.000.000 - Rp 25.000.000",
  "batas_lamaran": "30 April 2026",
  "status": "Publik", // 'Publik' | 'Draft' | 'Ditutup'
  "pelamar_count": 28,
  "ringkasan": "Memimpin arsitektur aplikasi web scalable...",
  "deskripsi": "Kami mencari Senior Fullstack Developer yang berpengalaman...",
  "tanggung_jawab": [
    "Merancang arsitektur backend robust menggunakan Laravel",
    "Mengembangkan frontend reaktif dan responsif",
    "Melakukan code review dan mentoring engineer junior"
  ],
  "kualifikasi_wajib": [
    "Minimal 4 tahun pengalaman profesional Laravel & PHP modern",
    "Mahir REST API design dan PostgreSQL / MySQL optimization",
    "Pemahaman mendalam mengenai Git dan CI/CD"
  ],
  "kualifikasi_opsional": [
    "Pengalaman integrasi FastAPI / Machine Learning services",
    "Pemahaman Docker containerization"
  ],
  "skills": ["PHP", "Laravel", "JavaScript", "PostgreSQL", "Docker", "REST API", "Git"]
}
```

---

## 2. Entitas Kandidat (`Kandidat`)

Digunakan pada: Profil Kandidat, Detail Pelamar HR, CV Parser, dan Skill Matching.

```json
{
  "id": 101,
  "nama": "Farhan Ramadhan",
  "email": "farhan.ramadhan@example.com",
  "telepon": "+62 812-3456-7890",
  "lokasi": "Tangerang Selatan, Banten",
  "posisi_terakhir": "Senior Backend Developer di PT Solusi Tekno",
  "pendidikan_terakhir": "S1 Teknik Informatika - Institut Teknologi Bandung (2018 - 2022)",
  "pengalaman_tahun": 4.5,
  "cv_file": "CV_Farhan_Ramadhan_2026.pdf",
  "cv_url": "/storage/cv/farhan.pdf",
  "status_parsing": "Selesai", // 'Selesai' | 'Sedang Diproses' | 'Gagal'
  "parsed_data": {
    "ringkasan_ekstraksi": "Engineer dengan keahlian mendalam pada ekosistem Laravel...",
    "pendidikan": [
      {
        "institusi": "Institut Teknologi Bandung",
        "gelar": "Sarjana Komputer (S.Kom)",
        "jurusan": "Teknik Informatika",
        "tahun": "2018 - 2022",
        "ipk": "3.82"
      }
    ],
    "pengalaman": [
      {
        "posisi": "Senior Backend Developer",
        "perusahaan": "PT Solusi Tekno Digital",
        "periode": "2022 - Sekarang",
        "deskripsi": "Mengelola microservices backend melayani 500k active users."
      }
    ],
    "sertifikasi": [
      "AWS Certified Solutions Architect (2024)",
      "Laravel Certified Developer (2023)"
    ]
  },
  "skills_terverifikasi": [
    { "nama": "Laravel", "kategori": "Framework", "sumber": "Pengalaman Kerja" },
    { "nama": "PHP", "kategori": "Bahasa", "sumber": "Sertifikasi" },
    { "nama": "PostgreSQL", "kategori": "Database", "sumber": "Pengalaman Kerja" }
  ]
}
```

---

## 3. Entitas Lamaran & Seleksi (`Lamaran`)

Digunakan pada: Lamaran Saya, Detail Lamaran Kandidat, Pipeline Kanban, dan Daftar Pelamar HR.

```json
{
  "id": 201,
  "kandidat_id": 101,
  "kandidat_nama": "Farhan Ramadhan",
  "lowongan_id": 1,
  "posisi": "Senior Fullstack Developer (Laravel & Vue/React)",
  "departemen": "Engineering",
  "tanggal_melamar": "24 Mar 2026",
  "tahap_saat_ini": "Tes Esai", // 'Seleksi Berkas' | 'Tes Esai' | 'Wawancara User' | 'Penawaran' | 'Diterima' | 'Ditolak'
  "skor_kesesuaian": 92, // Persentase kesesuaian skill S-BERT (0 - 100)
  "skor_tes": 88, // Nilai tes esai gabungan (0 - 100), null jika belum dinilai
  "status_label": "Dalam Proses",
  "timeline": [
    { "tahap": "Lamaran Terkirim", "waktu": "24 Mar 2026 10:15", "status": "selesai", "keterangan": "Berkas masuk ke sistem" },
    { "tahap": "Verifikasi Dokumen & CV", "waktu": "25 Mar 2026 14:00", "status": "selesai", "keterangan": "Parsing CV otomatis dan verifikasi kualifikasi lolos" },
    { "tahap": "Ujian Kemampuan Teknis (Esai)", "waktu": "27 Mar 2026 09:00", "status": "aktif", "keterangan": "Menunggu pengerjaan soal esai arsitektur" },
    { "tahap": "Wawancara Tim Teknis", "waktu": "Menunggu", "status": "menunggu", "keterangan": "Jadwal akan diinfokan setelah hasil tes selesai" },
    { "tahap": "Keputusan Akhir / Offering", "waktu": "Menunggu", "status": "menunggu", "keterangan": "Tahap akhir keputusan rekrutmen" }
  ]
}
```

---

## 4. Modul Smart Grading & Bank Soal Esai

Digunakan pada: Pengerjaan Tes Kandidat (`layouts.focus`), Bank Soal HR, dan Evaluasi Reviewer.

### A. Payload Soal Ujian (Sisi Kandidat)
> **PENTING**: Kunci jawaban, rubrik penilaian, dan catatan reviewer **TIDAK BOLEH** dikirim ke browser kandidat!

```json
{
  "paket_id": 1,
  "paket_nama": "Evaluasi Kemampuan Teknis Backend & Arsitektur",
  "durasi_menit": 60,
  "soal_list": [
    {
      "id": 1,
      "nomor": 1,
      "pertanyaan": "Jelaskan strategi Anda dalam mengoptimalkan query database yang lambat pada aplikasi Laravel dengan jutaan baris data!",
      "bobot_nilai": 30,
      "jawaban_tersimpan": "Strategi pertama adalah menggunakan EXPLAIN ANALYZE untuk menganalisis query execution plan..."
    }
  ]
}
```

### B. Payload Soal & Penilaian (Sisi HR Internal)
```json
{
  "jawaban_id": 501,
  "kandidat_nama": "Farhan Ramadhan",
  "pertanyaan": "Jelaskan strategi Anda dalam mengoptimalkan query database yang lambat pada aplikasi Laravel...",
  "jawaban_kandidat": "Strategi pertama adalah menggunakan EXPLAIN ANALYZE...",
  "kunci_jawaban_internal": "Jawaban yang baik harus mencakup: 1. EXPLAIN ANALYZE, 2. Indexing yang tepat (B-Tree/Composite), 3. Eager Loading (mencegah N+1 query), 4. Database caching (Redis), 5. Chunking / Lazy Collections untuk bulk processing.",
  "rubrik_kriteria": [
    { "poin": "Analisis Execution Plan & Profiling", "bobot": 25 },
    { "poin": "Strategi Indexing Database", "bobot": 25 },
    { "poin": "Pencegahan N+1 Query & Eloquent Eager Loading", "bobot": 25 },
    { "poin": "Implementasi Caching & Bulk Chunking", "bobot": 25 }
  ],
  "skor_ai_otomatis": 88, // Prediksi model NLP Smart Grading
  "alasan_skor_ai": "Kandidat menyebutkan analisis EXPLAIN, eager loading, dan strategi indexing composite secara komprehensif.",
  "skor_manual_reviewer": 90, // Nilai manual inputan HR
  "catatan_reviewer": "Penjelasan sangat aplikatif dan sesuai standar senior backend engineer.",
  "status_penilaian": "Selesai" // 'Menunggu Review' | 'Selesai'
}
```

---

## 5. Modul AI Skill Matching (S-BERT)

Digunakan pada: Automated Skill Matching HR (`hr.skill_matching`) dan Tab Pelamar.

```json
{
  "lowongan_id": 1,
  "kandidat_id": 101,
  "skor_kesesuaian": 92, // Persentase kecocokan semantik
  "label_kesesuaian": "Sangat Sesuai",
  "skill_wajib": [
    { "skill": "Laravel", "status": "Ditemukan", "bukti": "4+ tahun pengalaman kerja di PT Solusi Tekno & proyek e-commerce" },
    { "skill": "PHP Modern", "status": "Ditemukan", "bukti": "Kandidat memiliki sertifikasi resmi Laravel & PHP 8.x" },
    { "skill": "PostgreSQL", "status": "Ditemukan", "bukti": "Disebutkan dalam perancangan database high-volume transaksi" }
  ],
  "skill_opsional": [
    { "skill": "Docker", "status": "Ditemukan", "bukti": "Tertera dalam pengelolaan container deployment" },
    { "skill": "FastAPI", "status": "Belum Ditemukan", "bukti": "Tidak tercantum dalam riwayat proyek" }
  ]
}
```

---

## 6. Modul Deteksi Anomali & Integritas Data

Digunakan pada: Deteksi Anomali HR (`hr.anomali`).

```json
{
  "id": 1,
  "kandidat_id": 103,
  "nama": "Ilham Pratama",
  "posisi": "Machine Learning Engineer",
  "indikator": "Inkonsistensi Linimasa Pengalaman & Pendidikan",
  "detail_pola": "Terdapat periode overlap bekerja full-time 40 jam/minggu di dua perusahaan berbeda saat menempuh kuliah reguler.",
  "skor": 78,
  "tingkat_risiko": "Sedang", // 'Tinggi' | 'Sedang' | 'Rendah'
  "status": "belum_ditinjau", // 'belum_ditinjau' | 'sedang_ditinjau' | 'selesai'
  "catatan_hr": null
}
```

---

## 7. Modul Analitik Rekrutmen (Time Series & K-Means)

Digunakan pada: Analitik Rekrutmen HR (`hr.analitik`).

```json
{
  "time_series": [
    { "minggu": "W1", "aktual": 14, "forecast": null },
    { "minggu": "W12", "aktual": 26, "forecast": null },
    { "minggu": "W13 (P)", "aktual": null, "forecast": 29 },
    { "minggu": "W16 (P)", "aktual": null, "forecast": 36 }
  ],
  "clusters": [
    {
      "id": 1,
      "nama": "Klaster 1: High Potential & Ready-to-Hire",
      "jumlah": 5,
      "karakteristik": "Kesesuaian skill S-BERT >85% dan nilai esai >85%. Direkomendasikan langsung wawancara akhir."
    },
    {
      "id": 2,
      "nama": "Klaster 2: Solid Contender",
      "jumlah": 6,
      "karakteristik": "Kesesuaian skill baik (70-84%), memiliki pondasi teknis kuat namun butuh klarifikasi portofolio."
    },
    {
      "id": 3,
      "nama": "Klaster 3: Baseline & Growing",
      "jumlah": 4,
      "karakteristik": "Kesesuaian skill di bawah 70%, profil entry level atau berpindah haluan kejuruan."
    }
  ],
  "nlg_summary": "Volume lamaran mengalami tren kenaikan positif rata-rata 8.5% dalam 4 minggu terakhir..."
}
```
