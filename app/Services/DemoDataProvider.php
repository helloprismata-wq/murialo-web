<?php

namespace App\Services;

class DemoDataProvider
{
    /**
     * Data Profil Perusahaan
     */
    public static function getCompanyProfile(): array
    {
        return [
            'name' => 'PT Muria Logika Nusantara',
            'short_name' => 'MURIALO',
            'tagline' => 'Transformasi Rekrutmen Cerdas Berbasis AI',
            'industry' => 'Teknologi Informasi & Rekrutmen Terpadu',
            'location' => 'Jakarta Selatan, DKI Jakarta',
            'website' => 'https://murialo.id',
            'email' => 'talent@murialo.id',
            'phone' => '+62 21 5088 9012',
            'description' => 'Platform enterprise rekrutmen terintegrasi yang menggabungkan ekstraksi CV otomatis, pencocokan keahlian semantik, evaluasi tes esai cerdas, dan analitik talenta prediktif.',
        ];
    }

    /**
     * 20 Standar Keahlian / Skill
     */
    public static function getSkills(): array
    {
        return [
            ['id' => 1, 'name' => 'Python', 'category' => 'Backend & AI'],
            ['id' => 2, 'name' => 'FastAPI', 'category' => 'Backend'],
            ['id' => 3, 'name' => 'PHP', 'category' => 'Backend'],
            ['id' => 4, 'name' => 'Laravel', 'category' => 'Backend'],
            ['id' => 5, 'name' => 'Tailwind CSS', 'category' => 'Frontend'],
            ['id' => 6, 'name' => 'Alpine.js', 'category' => 'Frontend'],
            ['id' => 7, 'name' => 'JavaScript / TypeScript', 'category' => 'Frontend'],
            ['id' => 8, 'name' => 'PostgreSQL', 'category' => 'Database'],
            ['id' => 9, 'name' => 'MySQL', 'category' => 'Database'],
            ['id' => 10, 'name' => 'Docker', 'category' => 'DevOps'],
            ['id' => 11, 'name' => 'Git & GitHub', 'category' => 'DevOps'],
            ['id' => 12, 'name' => 'PyTorch', 'category' => 'AI & ML'],
            ['id' => 13, 'name' => 'Sentence-BERT (S-BERT)', 'category' => 'AI & ML'],
            ['id' => 14, 'name' => 'Natural Language Processing', 'category' => 'AI & ML'],
            ['id' => 15, 'name' => 'UI/UX Design & Figma', 'category' => 'Design'],
            ['id' => 16, 'name' => 'Design Systems', 'category' => 'Design'],
            ['id' => 17, 'name' => 'Communication & Presentation', 'category' => 'Soft Skills'],
            ['id' => 18, 'name' => 'Analytical Thinking', 'category' => 'Soft Skills'],
            ['id' => 19, 'name' => 'Talent Sourcing & HR Management', 'category' => 'HR Operations'],
            ['id' => 20, 'name' => 'Product Roadmapping & Agile', 'category' => 'Product Management'],
        ];
    }

    /**
     * 6 Lowongan Perusahaan
     */
    public static function getLowongan(): array
    {
        return [
            [
                'id' => 1,
                'slug' => 'senior-backend-engineer-python-laravel',
                'judul' => 'Senior Backend Engineer (Python & Laravel)',
                'departemen' => 'Engineering',
                'lokasi' => 'Jakarta Selatan (Hybrid)',
                'tipe_pekerjaan' => 'Penuh Waktu',
                'gaji_min' => 16000000,
                'gaji_max' => 24000000,
                'status' => 'aktif',
                'kuota' => 2,
                'jumlah_pelamar' => 9,
                'deadline' => '2026-10-31',
                'created_at' => '2026-09-15',
                'deskripsi' => 'Bertanggung jawab atas arsitektur backend scalable, integrasi microservice AI Engine FastAPI, manajemen database relasional tingkat tinggi, dan API performa tinggi untuk platform Murialo.',
                'kualifikasi_wajib' => ['Python', 'FastAPI', 'PHP', 'Laravel', 'PostgreSQL', 'Docker'],
                'kualifikasi_opsional' => ['PyTorch', 'Sentence-BERT (S-BERT)', 'Redis'],
                'benefit' => ['Asuransi Kesehatan Swasta', 'Tunjangan WFH', 'Budget Pengembangan Diri', 'Kompensasi Kompetitif'],
            ],
            [
                'id' => 2,
                'slug' => 'product-designer-ui-ux-design-systems',
                'judul' => 'Product Designer (UI/UX & Design Systems)',
                'departemen' => 'Product & Design',
                'lokasi' => 'Jakarta Selatan (On-site)',
                'tipe_pekerjaan' => 'Penuh Waktu',
                'gaji_min' => 12000000,
                'gaji_max' => 18000000,
                'status' => 'aktif',
                'kuota' => 1,
                'jumlah_pelamar' => 5,
                'deadline' => '2026-10-25',
                'created_at' => '2026-09-18',
                'deskripsi' => 'Merancang pengalaman antarmuka pengguna kelas enterprise untuk modul kandidat, dashboard HR, dan visualisasi analitik AI dengan standar estetika tenang dan fungsional.',
                'kualifikasi_wajib' => ['UI/UX Design & Figma', 'Design Systems', 'Communication & Presentation'],
                'kualifikasi_opsional' => ['Tailwind CSS', 'HTML & CSS prototyping', 'User Research'],
                'benefit' => ['MacBook Pro M-Series', 'Fasilitas Gym Kantor', 'Asuransi Rawat Inap & Jalan'],
            ],
            [
                'id' => 3,
                'slug' => 'ai-nlp-research-engineer',
                'judul' => 'AI / NLP Research Engineer',
                'departemen' => 'AI Research Lab',
                'lokasi' => 'Remote (Indonesia)',
                'tipe_pekerjaan' => 'Penuh Waktu',
                'gaji_min' => 18000000,
                'gaji_max' => 28000000,
                'status' => 'aktif',
                'kuota' => 1,
                'jumlah_pelamar' => 4,
                'deadline' => '2026-11-05',
                'created_at' => '2026-09-20',
                'deskripsi' => 'Mengeksplorasi dan mengoptimasi model Semantic Matching (S-BERT), zero-shot classification, automated resume parsing, serta pipeline smart grading esai bahasa Indonesia.',
                'kualifikasi_wajib' => ['Python', 'PyTorch', 'Natural Language Processing', 'Sentence-BERT (S-BERT)'],
                'kualifikasi_opsional' => ['FastAPI', 'Docker', 'Hugging Face Transformers'],
                'benefit' => ['Full Remote Allowance', 'Conference Attendance Budget', 'Bonus Kinerja Tahunan'],
            ],
            [
                'id' => 4,
                'slug' => 'talent-acquisition-hr-specialist',
                'judul' => 'Talent Acquisition & HR Specialist',
                'departemen' => 'Human Resources',
                'lokasi' => 'Jakarta Selatan (On-site)',
                'tipe_pekerjaan' => 'Penuh Waktu',
                'gaji_min' => 9000000,
                'gaji_max' => 14000000,
                'status' => 'aktif',
                'kuota' => 2,
                'jumlah_pelamar' => 4,
                'deadline' => '2026-10-28',
                'created_at' => '2026-09-22',
                'deskripsi' => 'Mengelola pipeline seleksi harian, meninjau hasil AI CV parser & skill matching, menyelenggarakan tes esai online, dan memfasilitasi wawancara bersama user teknis.',
                'kualifikasi_wajib' => ['Talent Sourcing & HR Management', 'Communication & Presentation', 'Analytical Thinking'],
                'kualifikasi_opsional' => ['Pemahaman Rekrutmen Tech', 'Psikologi Industri'],
                'benefit' => ['BPJS Ketenagakerjaan & Kesehatan', 'Makan Siang Kantor', 'Cuti Tahunan Fleksibel'],
            ],
            [
                'id' => 5,
                'slug' => 'technical-product-manager',
                'judul' => 'Technical Product Manager',
                'departemen' => 'Product Management',
                'lokasi' => 'Jakarta Selatan (Hybrid)',
                'tipe_pekerjaan' => 'Penuh Waktu',
                'gaji_min' => 17000000,
                'gaji_max' => 25000000,
                'status' => 'aktif',
                'kuota' => 1,
                'jumlah_pelamar' => 2,
                'deadline' => '2026-11-10',
                'created_at' => '2026-09-25',
                'deskripsi' => 'Memimpin roadmap modul evaluasi AI dan portal rekrutmen, menyelaraskan kebutuhan HR perusahaan dengan tim engineering dan machine learning.',
                'kualifikasi_wajib' => ['Product Roadmapping & Agile', 'Analytical Thinking', 'Communication & Presentation'],
                'kualifikasi_opsional' => ['Pengalaman SaaS B2B', 'Dasar Pemrograman Web'],
                'benefit' => ['Skema Insentif Roadmap', 'Perangkat Kerja Pilihan', 'Asuransi Keluarga'],
            ],
            [
                'id' => 6,
                'slug' => 'devops-cloud-infrastructure-specialist',
                'judul' => 'DevOps & Cloud Infrastructure Specialist',
                'departemen' => 'Infrastructure',
                'lokasi' => 'Jakarta Selatan (Hybrid)',
                'tipe_pekerjaan' => 'Kontrak (12 Bulan)',
                'gaji_min' => 14000000,
                'gaji_max' => 20000000,
                'status' => 'draft',
                'kuota' => 1,
                'jumlah_pelamar' => 1,
                'deadline' => '2026-11-15',
                'created_at' => '2026-09-28',
                'deskripsi' => 'Mengorkestrasi kontainer Docker, CI/CD pipeline, monitoring latency uvicorn/FastAPI, dan stabilitas server Laravel di infrastruktur cloud.',
                'kualifikasi_wajib' => ['Docker', 'Git & GitHub', 'PostgreSQL'],
                'kualifikasi_opsional' => ['Kubernetes', 'Linux Server Administration', 'Prometheus/Grafana'],
                'benefit' => ['Insentif On-call', 'Sertifikasi Cloud Disponsori'],
            ],
        ];
    }

    /**
     * 15 Kandidat Sintetis Lengkap
     */
    public static function getCandidates(): array
    {
        return [
            [
                'id' => 101,
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'telepon' => '+62 812-3456-7890',
                'lokasi' => 'Jakarta Selatan',
                'posisi_dilamar' => 'Senior Backend Engineer (Python & Laravel)',
                'lowongan_id' => 1,
                'pengalaman_tahun' => 5,
                'pendidikan_terakhir' => 'S1 Ilmu Komputer, Universitas Indonesia',
                'status_pipeline' => 'Wawancara HR',
                'skor_matching' => 92,
                'skor_tes' => 92.0,
                'status_tes' => 'Selesai Dinilai',
                'kelengkapan_profil' => 95,
                'cv_filename' => 'CV_Budi_Santoso_Backend.pdf',
                'tanggal_lamar' => '2026-09-22',
                'skills' => ['Python', 'FastAPI', 'PHP', 'Laravel', 'PostgreSQL', 'Docker', 'Git & GitHub'],
                'catatan_hr' => 'Kandidat sangat kuat di sisi arsitektur dan pemahaman query. Tes esai operasional dijawab dengan akurat.',
                'anomali_detected' => false,
            ],
            [
                'id' => 102,
                'nama' => 'Siti Rahma',
                'email' => 'siti.rahma@email.com',
                'telepon' => '+62 813-9876-5432',
                'lokasi' => 'Bandung',
                'posisi_dilamar' => 'Product Designer (UI/UX & Design Systems)',
                'lowongan_id' => 2,
                'pengalaman_tahun' => 4,
                'pendidikan_terakhir' => 'S1 Desain Komunikasi Visual, ITB',
                'status_pipeline' => 'Tes Esai',
                'skor_matching' => 88,
                'skor_tes' => null,
                'status_tes' => 'Menunggu Pengerjaan',
                'kelengkapan_profil' => 85,
                'cv_filename' => 'CV_Siti_Rahma_UIUX.pdf',
                'tanggal_lamar' => '2026-09-24',
                'skills' => ['UI/UX Design & Figma', 'Design Systems', 'Communication & Presentation', 'Tailwind CSS'],
                'catatan_hr' => 'Portofolio sistem desain terstruktur rapi. Sudah ditugaskan Paket B.',
                'anomali_detected' => false,
            ],
            [
                'id' => 103,
                'nama' => 'Dimas Arya Pratama',
                'email' => 'dimas.arya@email.com',
                'telepon' => '+62 856-1122-3344',
                'lokasi' => 'Yogyakarta',
                'posisi_dilamar' => 'AI / NLP Research Engineer',
                'lowongan_id' => 3,
                'pengalaman_tahun' => 3,
                'pendidikan_terakhir' => 'S1 Teknik Elektro & TI, UGM',
                'status_pipeline' => 'Review CV',
                'skor_matching' => 94,
                'skor_tes' => null,
                'status_tes' => 'Belum Ditugaskan',
                'kelengkapan_profil' => 90,
                'cv_filename' => 'CV_Dimas_Arya_NLP.pdf',
                'tanggal_lamar' => '2026-09-26',
                'skills' => ['Python', 'PyTorch', 'Natural Language Processing', 'Sentence-BERT (S-BERT)', 'Docker'],
                'catatan_hr' => 'Memiliki publikasi skripsi terkait fine-tuning transformer bahasa Indonesia.',
                'anomali_detected' => false,
            ],
            [
                'id' => 104,
                'nama' => 'Anisa Kusuma Putri',
                'email' => 'anisa.kusuma@email.com',
                'telepon' => '+62 878-2233-4455',
                'lokasi' => 'Jakarta Barat',
                'posisi_dilamar' => 'Talent Acquisition & HR Specialist',
                'lowongan_id' => 4,
                'pengalaman_tahun' => 3,
                'pendidikan_terakhir' => 'S1 Psikologi, Universitas Airlangga',
                'status_pipeline' => 'Wawancara User',
                'skor_matching' => 85,
                'skor_tes' => 84.0,
                'status_tes' => 'Selesai Dinilai',
                'kelengkapan_profil' => 90,
                'cv_filename' => 'CV_Anisa_Kusuma_HR.pdf',
                'tanggal_lamar' => '2026-09-25',
                'skills' => ['Talent Sourcing & HR Management', 'Communication & Presentation', 'Analytical Thinking'],
                'catatan_hr' => 'Gaya komunikasi lugas dan menguasai teknik behavioral event interview.',
                'anomali_detected' => false,
            ],
            [
                'id' => 105,
                'nama' => 'Fajar Nugroho',
                'email' => 'fajar.nugroho@email.com',
                'telepon' => '+62 821-3344-5566',
                'lokasi' => 'Depok',
                'posisi_dilamar' => 'Technical Product Manager',
                'lowongan_id' => 5,
                'pengalaman_tahun' => 6,
                'pendidikan_terakhir' => 'S1 Sistem Informasi, Binus University',
                'status_pipeline' => 'Offering',
                'skor_matching' => 90,
                'skor_tes' => 88.5,
                'status_tes' => 'Selesai Dinilai',
                'kelengkapan_profil' => 100,
                'cv_filename' => 'CV_Fajar_Nugroho_PM.pdf',
                'tanggal_lamar' => '2026-09-21',
                'skills' => ['Product Roadmapping & Agile', 'Analytical Thinking', 'Communication & Presentation', 'PostgreSQL'],
                'catatan_hr' => 'Latar belakang teknis kuat dan mampu menyusun PRD presisi.',
                'anomali_detected' => false,
            ],
            [
                'id' => 106,
                'nama' => 'Rizky Maulana',
                'email' => 'rizky.maulana@email.com',
                'telepon' => '+62 819-4455-6677',
                'lokasi' => 'Tangerang Selatan',
                'posisi_dilamar' => 'DevOps & Cloud Infrastructure Specialist',
                'lowongan_id' => 6,
                'pengalaman_tahun' => 4,
                'pendidikan_terakhir' => 'S1 Teknik Informatika, ITS',
                'status_pipeline' => 'Review CV',
                'skor_matching' => 82,
                'skor_tes' => null,
                'status_tes' => 'Belum Ditugaskan',
                'kelengkapan_profil' => 80,
                'cv_filename' => 'CV_Rizky_Maulana_DevOps.pdf',
                'tanggal_lamar' => '2026-09-27',
                'skills' => ['Docker', 'Git & GitHub', 'PostgreSQL', 'Python'],
                'catatan_hr' => 'Pengalaman CI/CD GitHub Actions dan containerized microservices.',
                'anomali_detected' => false,
            ],
            [
                'id' => 107,
                'nama' => 'Dewi Anggraini',
                'email' => 'dewi.anggraini@email.com',
                'telepon' => '+62 811-5566-7788',
                'lokasi' => 'Surabaya',
                'posisi_dilamar' => 'Senior Backend Engineer (Python & Laravel)',
                'lowongan_id' => 1,
                'pengalaman_tahun' => 4,
                'pendidikan_terakhir' => 'S1 Teknik Informatika, Universitas Brawijaya',
                'status_pipeline' => 'Tes Esai',
                'skor_matching' => 86,
                'skor_tes' => 78.0,
                'status_tes' => 'Menunggu Review HR',
                'kelengkapan_profil' => 85,
                'cv_filename' => 'CV_Dewi_Anggraini_BE.pdf',
                'tanggal_lamar' => '2026-09-28',
                'skills' => ['PHP', 'Laravel', 'MySQL', 'PostgreSQL', 'Git & GitHub'],
                'catatan_hr' => 'Jawaban tes esai logis, perlu klarifikasi terkait pengalaman Python FastAPI.',
                'anomali_detected' => false,
            ],
            [
                'id' => 108,
                'nama' => 'Hendra Setiawan',
                'email' => 'hendra.setiawan@email.com',
                'telepon' => '+62 852-6677-8899',
                'lokasi' => 'Jakarta Timur',
                'posisi_dilamar' => 'Senior Backend Engineer (Python & Laravel)',
                'lowongan_id' => 1,
                'pengalaman_tahun' => 5,
                'pendidikan_terakhir' => 'S1 Teknik Informatika, Gunadarma',
                'status_pipeline' => 'Wawancara User',
                'skor_matching' => 89,
                'skor_tes' => 85.0,
                'status_tes' => 'Selesai Dinilai',
                'kelengkapan_profil' => 90,
                'cv_filename' => 'CV_Hendra_Setiawan.pdf',
                'tanggal_lamar' => '2026-09-23',
                'skills' => ['Python', 'FastAPI', 'PostgreSQL', 'Docker', 'Git & GitHub'],
                'catatan_hr' => 'Kandidat memahami sistem caching dan background worker.',
                'anomali_detected' => false,
            ],
            [
                'id' => 109,
                'nama' => 'Nadia Safitri',
                'email' => 'nadia.safitri@email.com',
                'telepon' => '+62 877-7788-9900',
                'lokasi' => 'Semarang',
                'posisi_dilamar' => 'Product Designer (UI/UX & Design Systems)',
                'lowongan_id' => 2,
                'pengalaman_tahun' => 2,
                'pendidikan_terakhir' => 'S1 Desain Produk, ITB',
                'status_pipeline' => 'Review CV',
                'skor_matching' => 74,
                'skor_tes' => null,
                'status_tes' => 'Belum Ditugaskan',
                'kelengkapan_profil' => 75,
                'cv_filename' => 'CV_Nadia_Safitri.pdf',
                'tanggal_lamar' => '2026-09-29',
                'skills' => ['UI/UX Design & Figma', 'Communication & Presentation'],
                'catatan_hr' => 'Visual cukup menarik, tetapi perlu bukti portofolio sistem desain yang lebih terstandar.',
                'anomali_detected' => false,
            ],
            [
                'id' => 110,
                'nama' => 'Bambang Triyono',
                'email' => 'bambang.triyono@email.com',
                'telepon' => '+62 822-8899-0011',
                'lokasi' => 'Malang',
                'posisi_dilamar' => 'Senior Backend Engineer (Python & Laravel)',
                'lowongan_id' => 1,
                'pengalaman_tahun' => 7,
                'pendidikan_terakhir' => 'S1 Teknik Komputer, UB',
                'status_pipeline' => 'Review CV',
                'skor_matching' => 78,
                'skor_tes' => null,
                'status_tes' => 'Belum Ditugaskan',
                'kelengkapan_profil' => 80,
                'cv_filename' => 'CV_Bambang_Triyono.pdf',
                'tanggal_lamar' => '2026-09-30',
                'skills' => ['PHP', 'Laravel', 'MySQL', 'Git & GitHub'],
                'catatan_hr' => 'Pengalaman senior namun belum banyak menyentuh stack Python modern.',
                'anomali_detected' => true,
                'anomali_reason' => 'Pola linimasa kerja terdeteksi tumpang tindih 2 perusahaan purna waktu sekaligus (2022-2024).',
            ],
            [
                'id' => 111,
                'nama' => 'Lestari Handayani',
                'email' => 'lestari.handayani@email.com',
                'telepon' => '+62 813-9900-1122',
                'lokasi' => 'Solo',
                'posisi_dilamar' => 'Talent Acquisition & HR Specialist',
                'lowongan_id' => 4,
                'pengalaman_tahun' => 4,
                'pendidikan_terakhir' => 'S1 Manajemen SDM, UNS',
                'status_pipeline' => 'Tes Esai',
                'skor_matching' => 81,
                'skor_tes' => 72.0,
                'status_tes' => 'Menunggu Review HR',
                'kelengkapan_profil' => 85,
                'cv_filename' => 'CV_Lestari_HR.pdf',
                'tanggal_lamar' => '2026-09-29',
                'skills' => ['Talent Sourcing & HR Management', 'Communication & Presentation'],
                'catatan_hr' => 'Pengalaman rekrutmen volume besar di industri manufaktur.',
                'anomali_detected' => false,
            ],
            [
                'id' => 112,
                'nama' => 'Kevin Sanjaya',
                'email' => 'kevin.sanjaya@email.com',
                'telepon' => '+62 857-0011-2233',
                'lokasi' => 'Jakarta Pusat',
                'posisi_dilamar' => 'Technical Product Manager',
                'lowongan_id' => 5,
                'pengalaman_tahun' => 3,
                'pendidikan_terakhir' => 'S1 Ilmu Komputer, UGM',
                'status_pipeline' => 'Review CV',
                'skor_matching' => 79,
                'skor_tes' => null,
                'status_tes' => 'Belum Ditugaskan',
                'kelengkapan_profil' => 80,
                'cv_filename' => 'CV_Kevin_PM.pdf',
                'tanggal_lamar' => '2026-10-01',
                'skills' => ['Product Roadmapping & Agile', 'Communication & Presentation'],
                'catatan_hr' => 'Kandidat transisi dari software engineer ke PM, logika produk rapi.',
                'anomali_detected' => false,
            ],
            [
                'id' => 113,
                'nama' => 'Taufik Hidayat',
                'email' => 'taufik.hidayat@email.com',
                'telepon' => '+62 812-1122-3344',
                'lokasi' => 'Bogor',
                'posisi_dilamar' => 'AI / NLP Research Engineer',
                'lowongan_id' => 3,
                'pengalaman_tahun' => 2,
                'pendidikan_terakhir' => 'S1 Matematika, IPB',
                'status_pipeline' => 'Review CV',
                'skor_matching' => 82,
                'skor_tes' => null,
                'status_tes' => 'Belum Ditugaskan',
                'kelengkapan_profil' => 85,
                'cv_filename' => 'CV_Taufik_Math_AI.pdf',
                'tanggal_lamar' => '2026-10-02',
                'skills' => ['Python', 'Natural Language Processing', 'Analytical Thinking'],
                'catatan_hr' => 'Kuat di fondasi matematika statistik dan kalkulus matriks.',
                'anomali_detected' => false,
            ],
            [
                'id' => 114,
                'nama' => 'Mega Puspita',
                'email' => 'mega.puspita@email.com',
                'telepon' => '+62 878-2244-6688',
                'lokasi' => 'Jakarta Barat',
                'posisi_dilamar' => 'Product Designer (UI/UX & Design Systems)',
                'lowongan_id' => 2,
                'pengalaman_tahun' => 5,
                'pendidikan_terakhir' => 'S1 Desain Komunikasi Visual, Trisakti',
                'status_pipeline' => 'Diterima',
                'skor_matching' => 91,
                'skor_tes' => 90.0,
                'status_tes' => 'Selesai Dinilai',
                'kelengkapan_profil' => 95,
                'cv_filename' => 'CV_Mega_Puspita_Lead.pdf',
                'tanggal_lamar' => '2026-09-19',
                'skills' => ['UI/UX Design & Figma', 'Design Systems', 'Communication & Presentation', 'Analytical Thinking'],
                'catatan_hr' => 'Kandidat menerima penawaran kerja. Onboarding dijadwalkan bulan depan.',
                'anomali_detected' => false,
            ],
            [
                'id' => 115,
                'nama' => 'Arif Wicaksono',
                'email' => 'arif.wicaksono@email.com',
                'telepon' => '+62 821-4466-8800',
                'lokasi' => 'Tangerang',
                'posisi_dilamar' => 'Senior Backend Engineer (Python & Laravel)',
                'lowongan_id' => 1,
                'pengalaman_tahun' => 4,
                'pendidikan_terakhir' => 'S1 Teknik Informatika, Universitas Mercu Buana',
                'status_pipeline' => 'Tes Esai',
                'skor_matching' => 84,
                'skor_tes' => null,
                'status_tes' => 'Dalam Pengerjaan',
                'kelengkapan_profil' => 85,
                'cv_filename' => 'CV_Arif_Wicaksono.pdf',
                'tanggal_lamar' => '2026-09-27',
                'skills' => ['Python', 'PHP', 'Laravel', 'PostgreSQL'],
                'catatan_hr' => 'Kandidat sedang mengerjakan paket tes secara daring.',
                'anomali_detected' => true,
                'anomali_reason' => 'Perpindahan durasi pengerjaan antar soal tercatat sangat singkat (< 15 detik untuk soal deskriptif).',
            ],
        ];
    }

    /**
     * 25 Lamaran untuk Konsistensi Pipeline Seleksi
     */
    public static function getApplications(): array
    {
        // 15 lamaran dari candidates utama + 10 tambahan untuk mencapai 25 data
        $candidates = self::getCandidates();
        $apps = [];

        foreach ($candidates as $c) {
            $apps[] = [
                'id' => 'APP-' . $c['id'],
                'kandidat_id' => $c['id'],
                'nama_kandidat' => $c['nama'],
                'email' => $c['email'],
                'lowongan_id' => $c['lowongan_id'],
                'posisi' => $c['posisi_dilamar'],
                'tanggal_lamar' => $c['tanggal_lamar'],
                'status_tahap' => $c['status_pipeline'],
                'skor_matching' => $c['skor_matching'],
                'skor_tes' => $c['skor_tes'],
                'status_tes' => $c['status_tes'],
            ];
        }

        // Tambahan 10 lamaran untuk variasi statistik
        $extra = [
            ['id' => 'APP-201', 'kandidat_id' => 201, 'nama_kandidat' => 'Indra Permana', 'email' => 'indra.p@email.com', 'lowongan_id' => 1, 'posisi' => 'Senior Backend Engineer (Python & Laravel)', 'tanggal_lamar' => '2026-09-17', 'status_tahap' => 'Ditolak', 'skor_matching' => 58, 'skor_tes' => null, 'status_tes' => 'Tidak Lolos'],
            ['id' => 'APP-202', 'kandidat_id' => 202, 'nama_kandidat' => 'Ratna Sari', 'email' => 'ratna.s@email.com', 'lowongan_id' => 1, 'posisi' => 'Senior Backend Engineer (Python & Laravel)', 'tanggal_lamar' => '2026-09-18', 'status_tahap' => 'Ditolak', 'skor_matching' => 62, 'skor_tes' => null, 'status_tes' => 'Tidak Lolos'],
            ['id' => 'APP-203', 'kandidat_id' => 203, 'nama_kandidat' => 'Wahyu Pratama', 'email' => 'wahyu.p@email.com', 'lowongan_id' => 1, 'posisi' => 'Senior Backend Engineer (Python & Laravel)', 'tanggal_lamar' => '2026-09-20', 'status_tahap' => 'Review CV', 'skor_matching' => 71, 'skor_tes' => null, 'status_tes' => 'Belum Ditugaskan'],
            ['id' => 'APP-204', 'kandidat_id' => 204, 'nama_kandidat' => 'Cynthia Claudia', 'email' => 'cynthia.c@email.com', 'lowongan_id' => 2, 'posisi' => 'Product Designer (UI/UX & Design Systems)', 'tanggal_lamar' => '2026-09-21', 'status_tahap' => 'Review CV', 'skor_matching' => 69, 'skor_tes' => null, 'status_tes' => 'Belum Ditugaskan'],
            ['id' => 'APP-205', 'kandidat_id' => 205, 'nama_kandidat' => 'Gerry Ferdinand', 'email' => 'gerry.f@email.com', 'lowongan_id' => 2, 'posisi' => 'Product Designer (UI/UX & Design Systems)', 'tanggal_lamar' => '2026-09-22', 'status_tahap' => 'Ditolak', 'skor_matching' => 61, 'skor_tes' => null, 'status_tes' => 'Tidak Lolos'],
            ['id' => 'APP-206', 'kandidat_id' => 206, 'nama_kandidat' => 'Bayu Wicaksono', 'email' => 'bayu.w@email.com', 'lowongan_id' => 3, 'posisi' => 'AI / NLP Research Engineer', 'tanggal_lamar' => '2026-09-23', 'status_tahap' => 'Review CV', 'skor_matching' => 77, 'skor_tes' => null, 'status_tes' => 'Belum Ditugaskan'],
            ['id' => 'APP-207', 'kandidat_id' => 207, 'nama_kandidat' => 'Tiara Andini', 'email' => 'tiara.a@email.com', 'lowongan_id' => 3, 'posisi' => 'AI / NLP Research Engineer', 'tanggal_lamar' => '2026-09-24', 'status_tahap' => 'Ditolak', 'skor_matching' => 64, 'skor_tes' => null, 'status_tes' => 'Tidak Lolos'],
            ['id' => 'APP-208', 'kandidat_id' => 208, 'nama_kandidat' => 'Rangga Wijaya', 'email' => 'rangga.w@email.com', 'lowongan_id' => 4, 'posisi' => 'Talent Acquisition & HR Specialist', 'tanggal_lamar' => '2026-09-25', 'status_tahap' => 'Review CV', 'skor_matching' => 70, 'skor_tes' => null, 'status_tes' => 'Belum Ditugaskan'],
            ['id' => 'APP-209', 'kandidat_id' => 209, 'nama_kandidat' => 'Dian Sastro', 'email' => 'dian.s@email.com', 'lowongan_id' => 4, 'posisi' => 'Talent Acquisition & HR Specialist', 'tanggal_lamar' => '2026-09-26', 'status_tahap' => 'Diterima', 'skor_matching' => 88, 'skor_tes' => 86.0, 'status_tes' => 'Selesai Dinilai'],
            ['id' => 'APP-210', 'kandidat_id' => 210, 'nama_kandidat' => 'Eko Prasetyo', 'email' => 'eko.p@email.com', 'lowongan_id' => 1, 'posisi' => 'Senior Backend Engineer (Python & Laravel)', 'tanggal_lamar' => '2026-09-28', 'status_tahap' => 'Diterima', 'skor_matching' => 93, 'skor_tes' => 91.0, 'status_tes' => 'Selesai Dinilai'],
        ];

        return array_merge($apps, $extra);
    }

    /**
     * KPI Terpadu HR Dashboard (Derived dari dataset)
     */
    public static function getHrKpis(): array
    {
        $apps = self::getApplications();
        $totalApps = count($apps);
        $diterima = count(array_filter($apps, fn($a) => $a['status_tahap'] === 'Diterima'));
        $menungguReview = count(array_filter($apps, fn($a) => in_array($a['status_tes'], ['Menunggu Review HR', 'Menunggu Pengerjaan'])));
        $dalamProses = count(array_filter($apps, fn($a) => !in_array($a['status_tahap'], ['Diterima', 'Ditolak'])));

        return [
            'lowongan_aktif' => 5,
            'total_lamaran' => $totalApps, // 25
            'dalam_proses' => $dalamProses, // 18
            'menunggu_review_tes' => $menungguReview, // 4
            'kandidat_diterima' => $diterima, // 3
        ];
    }

    /**
     * Riwayat Lamaran 12 Minggu + Proyeksi Forecasting 4 Minggu
     */
    public static function getTimeSeriesData(): array
    {
        return [
            'labels' => [
                'Mgg 1 (Jul)', 'Mgg 2', 'Mgg 3', 'Mgg 4',
                'Mgg 5 (Ags)', 'Mgg 6', 'Mgg 7', 'Mgg 8',
                'Mgg 9 (Sep)', 'Mgg 10', 'Mgg 11', 'Mgg 12 (Okt)',
                'Mgg 13 (Proyeksi)', 'Mgg 14 (Proyeksi)', 'Mgg 15 (Proyeksi)', 'Mgg 16 (Proyeksi)'
            ],
            // 12 data historis nyata + null untuk proyeksi
            'historical' => [
                12, 16, 14, 21,
                19, 25, 22, 28,
                31, 35, 38, 42,
                null, null, null, null
            ],
            // null untuk 11 minggu pertama, minggu ke-12 sebagai jembatan, 4 minggu prediksi
            'projected' => [
                null, null, null, null,
                null, null, null, null,
                null, null, null, 42,
                46, 51, 55, 60
            ],
            'confidence_lower' => [
                null, null, null, null,
                null, null, null, null,
                null, null, null, 42,
                43, 47, 50, 54
            ],
            'confidence_upper' => [
                null, null, null, null,
                null, null, null, null,
                null, null, null, 42,
                49, 55, 60, 66
            ],
        ];
    }

    /**
     * K-Means Clustering Talenta (3 Klaster)
     */
    public static function getKMeansClusters(): array
    {
        return [
            [
                'cluster_id' => 1,
                'name' => 'Klaster A: Talenta Kritis & High-Tech',
                'color' => '#2563EB',
                'deskripsi' => 'Kandidat dengan skor kecocokan teknis tinggi (skor matching ≥ 88%) dan pengalaman spesifik arsitektur / AI.',
                'jumlah_kandidat' => 6,
                'anggota' => ['Budi Santoso', 'Dimas Arya Pratama', 'Fajar Nugroho', 'Mega Puspita', 'Eko Prasetyo', 'Hendra Setiawan'],
                'rekomendasi_tindakan' => 'Prioritaskan jadwal wawancara teknis cepat sebelum diambil kompetitor pasar.',
            ],
            [
                'cluster_id' => 2,
                'name' => 'Klaster B: Solid Balanced Practitioner',
                'color' => '#10B981',
                'deskripsi' => 'Kandidat dengan keseimbangan pengalaman praktis dan kemampuan kolaborasi lintas tim (skor 75% - 87%).',
                'jumlah_kandidat' => 6,
                'anggota' => ['Siti Rahma', 'Anisa Kusuma Putri', 'Dewi Anggraini', 'Lestari Handayani', 'Kevin Sanjaya', 'Taufik Hidayat'],
                'rekomendasi_tindakan' => 'Lanjutkan ke tahapan evaluasi tes esai dan studi kasus situasi nyata.',
            ],
            [
                'cluster_id' => 3,
                'name' => 'Klaster C: Junior / Specific Upskilling',
                'color' => '#F59E0B',
                'deskripsi' => 'Kandidat potensial dengan gap pada kualifikasi wajib tertentu atau tahun pengalaman yang lebih segar (skor < 75%).',
                'jumlah_kandidat' => 3,
                'anggota' => ['Nadia Safitri', 'Bambang Triyono', 'Arif Wicaksono'],
                'rekomendasi_tindakan' => 'Pertimbangkan untuk program talent pool atau posisi associate/junior.',
            ],
        ];
    }

    /**
     * Natural Language Generation (NLG) Insight Summary
     */
    public static function getNlgSummary(): array
    {
        return [
            'headline' => 'Tren Positif: Lonjakan Pelamar Engineering & Efisiensi Waktu Seleksi Meningkat 24%',
            'generated_at' => '2026-10-08 14:00 WIB',
            'model_source' => 'Murialo NLG Engine v1.2 (Demo)',
            'insights' => [
                'Volume pelamar untuk posisi Senior Backend Engineer mencatat peningkatan tertinggi (+42% dalam 3 minggu terakhir) didorong oleh ketersediaan skema kerja Hybrid.',
                'Waktu rata-rata peninjauan awal berkurang dari 4.2 hari menjadi 1.8 hari berkat pemilahan otomatis Sentence-BERT pada modul Automated Skill Matching.',
                'Proyeksi Time Series memperkirakan akan masuk 18–22 pelamar baru pada 2 minggu mendatang; disarankan menambah slot reviewer pada tahapan tes esai.',
                'Indikator Collaborative Filtering (CF) saat ini berstatus Cold-Start karena riwayat rekrutmen historis belum melampaui ambang batas 50 interaksi penawaran kerja.',
            ],
        ];
    }

    /**
     * Data Rekomendasi Kandidat (CBF vs CF Breakdown)
     */
    public static function getRecommendations(int $lowonganId = 1): array
    {
        return [
            'lowongan' => self::getLowongan()[0],
            'cf_status' => 'cold_start',
            'cf_message' => 'Data interaksi penawaran/penerimaan historis pada lowongan ini masih terbatas (< 50 data). Sistem saat ini mengandalkan bobot dominan Content-Based Filtering (CBF).',
            'candidates' => [
                [
                    'rank' => 1,
                    'kandidat_id' => 101,
                    'nama' => 'Budi Santoso',
                    'skor_total' => 94.2,
                    'skor_cbf' => 96.0,
                    'skor_cf' => null, // Cold start
                    'alasan' => 'Penguasaan 6 dari 6 skill wajib, pengalaman 5 tahun relevan, dan performa tes penalaran operasional unggul.',
                    'fit_level' => 'Sangat Direkomendasikan',
                ],
                [
                    'rank' => 2,
                    'kandidat_id' => 108,
                    'nama' => 'Hendra Setiawan',
                    'skor_total' => 89.4,
                    'skor_cbf' => 91.0,
                    'skor_cf' => null,
                    'alasan' => 'Kecocokan semantik tinggi pada stack Docker dan PostgreSQL, riwayat proyek backend terverifikasi.',
                    'fit_level' => 'Sangat Direkomendasikan',
                ],
                [
                    'rank' => 3,
                    'kandidat_id' => 107,
                    'nama' => 'Dewi Anggraini',
                    'skor_total' => 84.1,
                    'skor_cbf' => 85.5,
                    'skor_cf' => null,
                    'alasan' => 'Fondasi Laravel dan database relasional kuat; potensi tinggi setelah orientasi microservice Python.',
                    'fit_level' => 'Direkomendasikan',
                ],
                [
                    'rank' => 4,
                    'kandidat_id' => 115,
                    'nama' => 'Arif Wicaksono',
                    'skor_total' => 80.5,
                    'skor_cbf' => 82.0,
                    'skor_cf' => null,
                    'alasan' => 'Kesesuaian skill dasar baik, perlu klarifikasi hasil peninjauan anomali pengerjaan tes.',
                    'fit_level' => 'Perlu Peninjauan Tambahan',
                ],
            ]
        ];
    }

    /**
     * Data Deteksi Anomali
     */
    public static function getAnomalyFindings(): array
    {
        return [
            [
                'id' => 'ANO-001',
                'kandidat_id' => 110,
                'nama_kandidat' => 'Bambang Triyono',
                'posisi' => 'Senior Backend Engineer (Python & Laravel)',
                'kategori' => 'Inkonsistensi Linimasa Pengalaman',
                'skor_risiko' => 'Sedang (58/100)',
                'indikator' => 'Tumpang tindih masa kerja purna waktu',
                'detail_pola' => 'Terdeteksi dua entitas riwayat pekerjaan berstatus Full-Time pada periode bulan yang persis sama (Januari 2022 – Maret 2024).',
                'status_tinjauan' => 'Sedang Ditinjau',
                'catatan_hr' => 'Menghubungi kandidat untuk verifikasi status salah satu entitas (kemungkinan freelance/kontrak vendor).',
                'tanggal_deteksi' => '2026-09-30 11:20 WIB',
            ],
            [
                'id' => 'ANO-002',
                'kandidat_id' => 115,
                'nama_kandidat' => 'Arif Wicaksono',
                'posisi' => 'Senior Backend Engineer (Python & Laravel)',
                'kategori' => 'Pola Waktu Pengerjaan Tes Tidak Lazim',
                'skor_risiko' => 'Tinggi (74/100)',
                'indikator' => 'Kecepatan submit abnormal',
                'detail_pola' => 'Pengerjaan 3 butir soal bacaan esai panjang diselesaikan rata-rata dalam 11 detik per soal dengan teks jawaban terisi penuh.',
                'status_tinjauan' => 'Belum Ditinjau',
                'catatan_hr' => null,
                'tanggal_deteksi' => '2026-10-02 09:45 WIB',
            ],
            [
                'id' => 'ANO-003',
                'kandidat_id' => 102,
                'nama_kandidat' => 'Siti Rahma',
                'posisi' => 'Product Designer (UI/UX & Design Systems)',
                'kategori' => 'Variasi Format Metadata Berkas',
                'skor_risiko' => 'Rendah (18/100)',
                'indikator' => 'Penyimpangan parser font PDF',
                'detail_pola' => 'Dokumen CV menggunakan embedded font kustom yang menyebabkan penurunan akurasi OCR sebesar 12%. Tidak terindikasi kecurangan.',
                'status_tinjauan' => 'Selesai',
                'catatan_hr' => 'Teks telah dikonfirmasi manual bersama kandidat melalui portal konfirmasi kandidat.',
                'tanggal_deteksi' => '2026-09-24 16:30 WIB',
            ],
        ];
    }

    /**
     * Data Pengguna Sistem & Administrator
     */
    public static function getSystemUsers(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'HRD Murialo',
                'email' => 'hrd@murialo.test',
                'role' => 'hr',
                'role_label' => 'HR Recruiter Lead',
                'status' => 'Aktif',
                'terakhir_login' => '2026-10-08 19:40 WIB',
            ],
            [
                'id' => 2,
                'name' => 'Rekruter Demo',
                'email' => 'rekruter@murialo.test',
                'role' => 'hr',
                'role_label' => 'Talent Acquisition Staff',
                'status' => 'Aktif',
                'terakhir_login' => '2026-10-07 14:15 WIB',
            ],
            [
                'id' => 3,
                'name' => 'Administrator Murialo',
                'email' => 'admin@murialo.test',
                'role' => 'admin',
                'role_label' => 'Platform Administrator',
                'status' => 'Aktif',
                'terakhir_login' => '2026-10-08 20:10 WIB',
            ],
            [
                'id' => 4,
                'name' => 'Budi Santoso',
                'email' => 'budi.kandidat@murialo.test',
                'role' => 'kandidat',
                'role_label' => 'Kandidat Terdaftar',
                'status' => 'Aktif',
                'terakhir_login' => '2026-10-08 18:30 WIB',
            ],
            [
                'id' => 5,
                'name' => 'Siti Rahma',
                'email' => 'siti.kandidat@murialo.test',
                'role' => 'kandidat',
                'role_label' => 'Kandidat Terdaftar',
                'status' => 'Aktif',
                'terakhir_login' => '2026-10-08 16:20 WIB',
            ],
        ];
    }

    /**
     * Data Audit Log Keamanan & Akses
     */
    public static function getAuditLogs(): array
    {
        return [
            [
                'id' => 'LOG-1092',
                'timestamp' => '2026-10-08 20:15:22',
                'user' => 'Administrator Murialo',
                'action' => 'Pembaruan Kebijakan Sesi Demo',
                'module' => 'Pengaturan Platform',
                'ip_address' => '127.0.0.1',
                'status' => 'Sukses',
            ],
            [
                'id' => 'LOG-1091',
                'timestamp' => '2026-10-08 19:42:05',
                'user' => 'HRD Murialo',
                'action' => 'Publikasi Hasil Tes Budi Santoso (Skor 92.0)',
                'module' => 'Smart Grading',
                'ip_address' => '127.0.0.1',
                'status' => 'Sukses',
            ],
            [
                'id' => 'LOG-1090',
                'timestamp' => '2026-10-08 18:35:10',
                'user' => 'Budi Santoso',
                'action' => 'Pengumpulan Jawaban Paket Tes A (5 Butir Soal)',
                'module' => 'Tes Online Kandidat',
                'ip_address' => '127.0.0.1',
                'status' => 'Sukses',
            ],
            [
                'id' => 'LOG-1089',
                'timestamp' => '2026-10-08 17:10:44',
                'user' => 'HRD Murialo',
                'action' => 'Pemicu Automated Skill Matching Lowongan #1',
                'module' => 'Automated Skill Matching',
                'ip_address' => '127.0.0.1',
                'status' => 'Sukses',
            ],
            [
                'id' => 'LOG-1088',
                'timestamp' => '2026-10-08 16:05:18',
                'user' => 'Sistem AI Engine',
                'action' => 'Penyelesaian Ekstraksi CV Budi Santoso (9 Skill)',
                'module' => 'Resume Parser AI',
                'ip_address' => '127.0.0.1:8001',
                'status' => 'Sukses',
            ],
            [
                'id' => 'LOG-1087',
                'timestamp' => '2026-10-08 15:30:00',
                'user' => 'Rekruter Demo',
                'action' => 'Pembuatan Lowongan Baru: Senior Backend Engineer',
                'module' => 'Manajemen Lowongan',
                'ip_address' => '127.0.0.1',
                'status' => 'Sukses',
            ],
        ];
    }
}
