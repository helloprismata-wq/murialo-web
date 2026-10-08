<?php

namespace App\Http\Controllers;

use App\Services\DemoDataProvider;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DemoUiController extends Controller
{
    // =========================================================================
    // 1. PUBLIK & LANDING PAGE
    // =========================================================================

    public function landing(): View
    {
        $company = DemoDataProvider::getCompanyProfile();
        $lowongan = array_slice(DemoDataProvider::getLowongan(), 0, 4);
        $kpis = DemoDataProvider::getHrKpis();

        return view('landing', compact('company', 'lowongan', 'kpis'));
    }

    public function karirIndex(Request $request): View
    {
        $lowongan = DemoDataProvider::getLowongan();
        $departemenList = array_unique(array_column($lowongan, 'departemen'));
        
        $search = $request->query('q');
        $dept = $request->query('dept');

        if ($search) {
            $lowongan = array_filter($lowongan, function ($job) use ($search) {
                return stripos($job['judul'], $search) !== false ||
                       stripos($job['deskripsi'], $search) !== false;
            });
        }

        if ($dept) {
            $lowongan = array_filter($lowongan, function ($job) use ($dept) {
                return $job['departemen'] === $dept;
            });
        }

        return view('karir.index', compact('lowongan', 'departemenList', 'search', 'dept'));
    }

    public function karirShow(int $id): View
    {
        $all = DemoDataProvider::getLowongan();
        $job = collect($all)->firstWhere('id', $id) ?? $all[0];
        $company = DemoDataProvider::getCompanyProfile();

        return view('karir.show', compact('job', 'company'));
    }

    // =========================================================================
    // 2. AUTENTIKASI (SIMULASI DEMO)
    // =========================================================================

    public function login(Request $request): View
    {
        $presetRole = $request->query('role', 'hr');
        return view('auth.login', compact('presetRole'));
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function forgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function resetPassword(): View
    {
        return view('auth.reset-password');
    }

    // =========================================================================
    // 3. AREA KANDIDAT
    // =========================================================================

    public function kandidatDashboard(): View
    {
        $candidates = DemoDataProvider::getCandidates();
        $candidate = $candidates[0]; // Budi Santoso
        $applications = array_slice(DemoDataProvider::getApplications(), 0, 3);
        $lowongan = array_slice(DemoDataProvider::getLowongan(), 0, 3);

        return view('kandidat.dashboard', compact('candidate', 'applications', 'lowongan'));
    }

    public function kandidatProfil(): View
    {
        $candidate = DemoDataProvider::getCandidates()[0];
        $allSkills = DemoDataProvider::getSkills();

        return view('kandidat.profil', compact('candidate', 'allSkills'));
    }

    public function kandidatCv(): View
    {
        $candidate = DemoDataProvider::getCandidates()[0];
        return view('kandidat.cv', compact('candidate'));
    }

    public function kandidatCvEkstraksi(): View
    {
        $candidate = DemoDataProvider::getCandidates()[0];
        $skills = $candidate['skills'];

        return view('kandidat.cv-ekstraksi', compact('candidate', 'skills'));
    }

    public function kandidatLowongan(Request $request): View
    {
        $lowongan = DemoDataProvider::getLowongan();
        return view('kandidat.lowongan', compact('lowongan'));
    }

    public function kandidatLamaran(): View
    {
        $candidate = DemoDataProvider::getCandidates()[0];
        $applications = array_filter(DemoDataProvider::getApplications(), function ($a) use ($candidate) {
            return $a['kandidat_id'] == $candidate['id'] || $a['nama_kandidat'] == $candidate['nama'];
        });

        if (empty($applications)) {
            $applications = [DemoDataProvider::getApplications()[0]];
        }

        return view('kandidat.lamaran.index', compact('applications', 'candidate'));
    }

    public function kandidatLamaranDetail(string $id): View
    {
        $candidate = DemoDataProvider::getCandidates()[0];
        $job = DemoDataProvider::getLowongan()[0];

        return view('kandidat.lamaran.show', compact('id', 'candidate', 'job'));
    }

    public function kandidatTesIndex(): View
    {
        $candidate = DemoDataProvider::getCandidates()[0];
        return view('kandidat.tes.index', compact('candidate'));
    }

    public function kandidatTesKerjakan(string $id): View
    {
        $candidate = DemoDataProvider::getCandidates()[0];
        $job = DemoDataProvider::getLowongan()[0];

        // Soal untuk tes esai (Hanya pertanyaan & konteks teks, TANPA kunci jawaban & TANPA rubrik)
        $soalList = [
            [
                'nomor' => 1,
                'judul' => 'Jadwal dan Syarat Pergantian Shift Gudang',
                'teks' => 'Pergantian shift kerja di gudang logistik berlangsung tepat pada pukul 07.00, 15.00, dan 23.00 WIB. Setiap staf wajib hadir 15 menit sebelum jam pergantian untuk mengikuti pengarahan keselamatan (safety briefing) dan menandatangani presensi digital. Staf yang terlambat hadir tanpa pemberitahuan minimal 1 jam sebelumnya akan dialihkan ke tugas pencatatan administratif non-operasional untuk hari tersebut.',
                'pertanyaan' => 'Kapan batas waktu paling lambat staf harus memberitahukan keterlambatan agar tidak dialihkan ke tugas administratif?',
                'jawaban_tersimpan' => 'Staf wajib memberitahukan paling lambat 1 jam sebelum jam pergantian shift agar tidak dipindahkan ke tugas administratif.',
            ],
            [
                'nomor' => 2,
                'judul' => 'Kebijakan Pengembalian Dana Pembelian Perlengkapan',
                'teks' => 'Karyawan lapangan berhak mengajukan reimbursement untuk pembelian perlengkapan darurat maksimal Rp 300.000 per transaksi dengan melampirkan struk resmi fisik dan foto barang. Pengajuan wajib diserahkan ke bagian keuangan selambat-lambatnya 3 hari kerja setelah transaksi dilakukan. Pengajuan tanpa struk fisik asli otomatis ditolak tanpa pengecualian.',
                'pertanyaan' => 'Dokumen apa saja yang wajib dilampirkan agar pengajuan reimbursement perlengkapan tidak ditolak?',
                'jawaban_tersimpan' => 'Harus melampirkan struk fisik asli dan foto perlengkapan yang dibeli dalam kurun waktu 3 hari kerja.',
            ],
            [
                'nomor' => 3,
                'judul' => 'Spesifikasi Suhu Ruang Penyimpanan Sampel',
                'teks' => 'Ruang penyimpanan bahan baku farmasi A-3 harus dijaga pada rentang suhu 2°C hingga 8°C dengan kelembapan udara di bawah 60%. Jika suhu ruangan naik melebihi 10°C selama lebih dari 30 menit terus-menerus, sistem alarm pendingin cadangan akan aktif dan petugas QA wajib melakukan karantina pada seluruh bets yang sedang tersimpan.',
                'pertanyaan' => 'Kondisi spesifik apa yang memicu petugas QA melakukan karantina terhadap bets bahan baku?',
                'jawaban_tersimpan' => 'Ketika suhu ruangan A-3 naik di atas 10 derajat celcius selama lebih dari 30 menit terus menerus.',
            ],
            [
                'nomor' => 4,
                'judul' => 'Ketentuan Hak Akses Ruang Server Pusat',
                'teks' => 'Ruang server lantai 4 hanya boleh dimasuki oleh personil berwenang dengan kartu akses tingkat A. Tamu teknis eksternal diperbolehkan masuk hanya apabila didampingi oleh minimal satu staf IT internal tetap dan telah mendaftarkan kartu identitas di pos keamanan lantai dasar.',
                'pertanyaan' => 'Dua syarat apa yang harus dipenuhi tamu teknis eksternal untuk dapat masuk ke ruang server?',
                'jawaban_tersimpan' => 'Tamu teknis harus didampingi staf IT internal dan menitipkan kartu tanda pengenal di pos keamanan.',
            ],
            [
                'nomor' => 5,
                'judul' => 'Instruksi Penanganan Kebocoran Bahan Kimia Ringan',
                'teks' => 'Instruksi Kerja Penanganan Tumpahan Kimia: Langkah 1: Pasang rambu peringatan isolasi area dalam radius 3 meter. Langkah 2: Kenakan sarung tangan nitril dan masker pelindung uap. Langkah 3: Taburkan serbuk penetral di sekeliling tumpahan sebelum menabur ke bagian tengah. Langkah 4: Kumpulkan limbah ke kantong berkode kuning dan beri label tanggal penanganan.',
                'pertanyaan' => 'Menurut instruksi kerja di atas, bagaimana urutan penaburan serbuk penetral pada tumpahan yang benar?',
                'jawaban_tersimpan' => 'Serbuk penetral ditaburkan di sekeliling luar tumpahan terlebih dahulu sebelum menabur ke bagian tengah.',
            ],
        ];

        return view('kandidat.tes.kerjakan', compact('id', 'candidate', 'job', 'soalList'));
    }

    public function kandidatTesHasil(string $id): View
    {
        $candidate = DemoDataProvider::getCandidates()[0];
        $job = DemoDataProvider::getLowongan()[0];

        return view('kandidat.tes.hasil', compact('id', 'candidate', 'job'));
    }

    // =========================================================================
    // 4. AREA HR & RECRUITER
    // =========================================================================

    public function hrDashboard(): View
    {
        $kpis = DemoDataProvider::getHrKpis();
        $candidates = array_slice(DemoDataProvider::getCandidates(), 0, 5);
        $lowongan = array_slice(DemoDataProvider::getLowongan(), 0, 4);
        $timeSeries = DemoDataProvider::getTimeSeriesData();
        $nlg = DemoDataProvider::getNlgSummary();

        return view('hr.dashboard', compact('kpis', 'candidates', 'lowongan', 'timeSeries', 'nlg'));
    }

    public function hrLowonganIndex(): View
    {
        $lowongan = DemoDataProvider::getLowongan();
        return view('hr.lowongan.index', compact('lowongan'));
    }

    public function hrLowonganCreate(): View
    {
        $allSkills = DemoDataProvider::getSkills();
        return view('hr.lowongan.create', compact('allSkills'));
    }

    public function hrLowonganEdit(int $id): View
    {
        $all = DemoDataProvider::getLowongan();
        $job = collect($all)->firstWhere('id', $id) ?? $all[0];
        $allSkills = DemoDataProvider::getSkills();

        return view('hr.lowongan.edit', compact('job', 'allSkills'));
    }

    public function hrPelamarIndex(Request $request): View
    {
        $candidates = DemoDataProvider::getCandidates();
        $lowonganList = DemoDataProvider::getLowongan();

        $search = $request->query('q');
        $status = $request->query('status');
        $jobId = $request->query('job_id');

        if ($search) {
            $candidates = array_filter($candidates, function ($c) use ($search) {
                return stripos($c['nama'], $search) !== false ||
                       stripos($c['email'], $search) !== false ||
                       stripos($c['posisi_dilamar'], $search) !== false;
            });
        }

        if ($status) {
            $candidates = array_filter($candidates, fn($c) => $c['status_pipeline'] === $status);
        }

        if ($jobId) {
            $candidates = array_filter($candidates, fn($c) => $c['lowongan_id'] == $jobId);
        }

        return view('hr.pelamar.index', compact('candidates', 'lowonganList', 'search', 'status', 'jobId'));
    }

    public function hrPelamarDetail(int $id): View
    {
        $all = DemoDataProvider::getCandidates();
        $candidate = collect($all)->firstWhere('id', $id) ?? $all[0];
        $job = collect(DemoDataProvider::getLowongan())->firstWhere('id', $candidate['lowongan_id']) ?? DemoDataProvider::getLowongan()[0];

        return view('hr.pelamar.show', compact('candidate', 'job'));
    }

    public function hrPipeline(): View
    {
        $candidates = DemoDataProvider::getCandidates();
        $lowonganList = DemoDataProvider::getLowongan();

        $columns = [
            'Review CV' => array_filter($candidates, fn($c) => $c['status_pipeline'] === 'Review CV'),
            'Tes Esai' => array_filter($candidates, fn($c) => $c['status_pipeline'] === 'Tes Esai'),
            'Wawancara HR' => array_filter($candidates, fn($c) => $c['status_pipeline'] === 'Wawancara HR'),
            'Wawancara User' => array_filter($candidates, fn($c) => $c['status_pipeline'] === 'Wawancara User'),
            'Offering & Diterima' => array_filter($candidates, fn($c) => in_array($c['status_pipeline'], ['Offering', 'Diterima'])),
        ];

        return view('hr.pipeline', compact('columns', 'lowonganList'));
    }

    public function hrCvParser(): View
    {
        $candidates = DemoDataProvider::getCandidates();
        return view('hr.cv-parser', compact('candidates'));
    }

    public function hrSkillMatching(Request $request): View
    {
        $lowonganList = DemoDataProvider::getLowongan();
        $selectedJobId = (int)$request->query('lowongan_id', 1);
        $selectedJob = collect($lowonganList)->firstWhere('id', $selectedJobId) ?? $lowonganList[0];

        $candidates = array_filter(DemoDataProvider::getCandidates(), function ($c) use ($selectedJobId) {
            return $c['lowongan_id'] === $selectedJobId;
        });

        // Urutkan berdasarkan skor matching
        usort($candidates, fn($a, $b) => $b['skor_matching'] <=> $a['skor_matching']);

        return view('hr.skill-matching', compact('lowonganList', 'selectedJob', 'candidates'));
    }

    public function hrSmartGradingIndex(): View
    {
        $candidates = DemoDataProvider::getCandidates();
        $lowonganList = DemoDataProvider::getLowongan();
        $kpis = DemoDataProvider::getHrKpis();

        return view('hr.smart-grading.index', compact('candidates', 'lowonganList', 'kpis'));
    }

    public function hrSmartGradingPenilaian(int $id): View
    {
        $all = DemoDataProvider::getCandidates();
        $candidate = collect($all)->firstWhere('id', $id) ?? $all[0];
        $job = collect(DemoDataProvider::getLowongan())->firstWhere('id', $candidate['lowongan_id']) ?? DemoDataProvider::getLowongan()[0];

        // Soal & Jawaban Kandidat vs Jawaban Acuan & Rubrik Internal HR
        $items = [
            [
                'soal_id' => 1,
                'judul' => 'Jadwal dan Syarat Pergantian Shift Gudang',
                'teks' => 'Pergantian shift kerja di gudang logistik berlangsung tepat pada pukul 07.00, 15.00, dan 23.00 WIB. Setiap staf wajib hadir 15 menit sebelum jam pergantian untuk mengikuti pengarahan keselamatan (safety briefing) dan menandatangani presensi digital. Staf yang terlambat hadir tanpa pemberitahuan minimal 1 jam sebelumnya akan dialihkan ke tugas pencatatan administratif non-operasional untuk hari tersebut.',
                'pertanyaan' => 'Kapan batas waktu paling lambat staf harus memberitahukan keterlambatan agar tidak dialihkan ke tugas administratif?',
                'jawaban_kandidat' => 'Staf wajib memberitahukan paling lambat 1 jam sebelum jam pergantian shift agar tidak dipindahkan ke tugas administratif.',
                'jawaban_acuan' => 'Minimal 1 jam sebelum jam pergantian shift (atau sebelum jadwal briefing).',
                'skor_ai_prediksi' => 9.5,
                'skor_maks' => 10,
                'rubrik' => [
                    ['kriteria' => 'Ketepatan Durasi Batas Waktu', 'poin_maks' => 6, 'skor_diberikan' => 6, 'deskripsi' => 'Menyebutkan minimal 1 jam sebelum pergantian shift.'],
                    ['kriteria' => 'Kelengkapan Konteks Konsekuensi', 'poin_maks' => 4, 'skor_diberikan' => 4, 'deskripsi' => 'Menyebutkan hubungan pemberitahuan dengan pengalihan tugas.'],
                ],
            ],
            [
                'soal_id' => 2,
                'judul' => 'Kebijakan Pengembalian Dana Pembelian Perlengkapan',
                'teks' => 'Karyawan lapangan berhak mengajukan reimbursement untuk pembelian perlengkapan darurat maksimal Rp 300.000 per transaksi dengan melampirkan struk resmi fisik dan foto barang. Pengajuan wajib diserahkan ke bagian keuangan selambat-lambatnya 3 hari kerja setelah transaksi dilakukan. Pengajuan tanpa struk fisik asli otomatis ditolak tanpa pengecualian.',
                'pertanyaan' => 'Dokumen apa saja yang wajib dilampirkan agar pengajuan reimbursement perlengkapan tidak ditolak?',
                'jawaban_kandidat' => 'Harus melampirkan struk fisik asli dan foto perlengkapan yang dibeli dalam kurun waktu 3 hari kerja.',
                'jawaban_acuan' => 'Struk resmi fisik asli dan foto barang yang dibeli.',
                'skor_ai_prediksi' => 9.0,
                'skor_maks' => 10,
                'rubrik' => [
                    ['kriteria' => 'Penyebutan Struk Fisik Asli', 'poin_maks' => 5, 'skor_diberikan' => 5, 'deskripsi' => 'Menyebutkan struk fisik asli/resmi.'],
                    ['kriteria' => 'Penyebutan Foto Barang', 'poin_maks' => 5, 'skor_diberikan' => 4, 'deskripsi' => 'Menyebutkan dokumentasi foto barang/perlengkapan.'],
                ],
            ],
        ];

        return view('hr.smart-grading.penilaian', compact('candidate', 'job', 'items'));
    }

    public function hrRekomendasi(Request $request): View
    {
        $lowonganList = DemoDataProvider::getLowongan();
        $selectedJobId = (int)$request->query('lowongan_id', 1);
        $recs = DemoDataProvider::getRecommendations($selectedJobId);

        return view('hr.rekomendasi', compact('lowonganList', 'recs', 'selectedJobId'));
    }

    public function hrAnomali(): View
    {
        $findings = DemoDataProvider::getAnomalyFindings();
        return view('hr.anomali', compact('findings'));
    }

    public function hrAnalitik(): View
    {
        $timeSeries = DemoDataProvider::getTimeSeriesData();
        $clusters = DemoDataProvider::getKMeansClusters();
        $nlg = DemoDataProvider::getNlgSummary();
        $kpis = DemoDataProvider::getHrKpis();

        return view('hr.analitik', compact('timeSeries', 'clusters', 'nlg', 'kpis'));
    }

    // =========================================================================
    // 5. AREA ADMINISTRATOR
    // =========================================================================

    public function adminUsers(): View
    {
        $users = DemoDataProvider::getSystemUsers();
        return view('admin.users', compact('users'));
    }

    public function adminRoles(): View
    {
        return view('admin.roles');
    }

    public function adminSettings(): View
    {
        $company = DemoDataProvider::getCompanyProfile();
        return view('admin.settings', compact('company'));
    }

    public function adminAuditLog(): View
    {
        $logs = DemoDataProvider::getAuditLogs();
        return view('admin.audit-log', compact('logs'));
    }
}
