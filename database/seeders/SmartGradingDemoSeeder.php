<?php

namespace Database\Seeders;

use App\Models\JawabanKandidat;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\PaketTes;
use App\Models\Penilaian;
use App\Models\PenilaianDetail;
use App\Models\PenugasanTes;
use App\Models\PercobaanTes;
use App\Models\Soal;
use App\Models\SoalJawabanAcuan;
use App\Models\SoalRubrik;
use App\Models\SoalSnapshot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmartGradingDemoSeeder extends Seeder
{
    public const DISCLAIMER = 'Data dummy untuk demonstrasi; belum divalidasi sebagai instrumen seleksi.';

    public function run(): void
    {
        // 1. Dapatkan atau buat akun HR pembuat
        $hrUser = User::where('email', 'hrd@murialo.test')->first();
        if (!$hrUser) {
            $hrUser = User::firstOrCreate(
                ['email' => 'hrd@murialo.test'],
                ['name' => 'HRD Murialo', 'password' => 'password', 'role' => 'hr']
            );
        } else {
            $hrUser->update(['role' => 'hr']);
        }

        // Dapatkan lowongan untuk relasi
        $lowongan1 = Lowongan::first();
        $lowongan2 = Lowongan::skip(1)->first() ?? $lowongan1;

        // 2. Buat 10 Soal Jawaban Singkat
        $soalDefinitions = [
            // --- Kategori 1: Pemahaman Informasi Tertulis (4 Soal) ---
            [
                'kode_soal' => 'DEMO-INF-01',
                'judul' => 'Jadwal dan Syarat Pergantian Shift Gudang',
                'kategori' => 'pemahaman_informasi',
                'skor_maksimum' => 10,
                'teks_bacaan' => "Pergantian shift kerja di gudang logistik berlangsung tepat pada pukul 07.00, 15.00, dan 23.00 WIB. Setiap staf wajib hadir 15 menit sebelum jam pergantian untuk mengikuti pengarahan keselamatan (safety briefing) dan menandatangani presensi digital. Staf yang terlambat hadir tanpa pemberitahuan minimal 1 jam sebelumnya akan dialihkan ke tugas pencatatan administratif non-operasional untuk hari tersebut.",
                'pertanyaan' => "Kapan batas waktu paling lambat staf harus memberitahukan keterlambatan agar tidak dialihkan ke tugas administratif?",
                'jawaban_acuan' => [
                    ['jawaban' => 'Minimal 1 jam sebelum jam pergantian shift (atau sebelum jadwal briefing).', 'keterangan' => 'Poin penting: batas 1 jam sebelum pergantian.'],
                    ['jawaban' => 'Pemberitahuan harus dilakukan sekurang-kurangnya 1 jam sebelum shift dimulai.', 'keterangan' => 'Variasi kalimat alternatif.'],
                ],
                'rubrik' => [
                    ['kriteria' => 'Ketepatan Durasi Batas Waktu', 'poin_maksimum' => 6, 'deskripsi' => 'Menyebutkan minimal 1 jam sebelum pergantian shift.'],
                    ['kriteria' => 'Kelengkapan Konteks Konsekuensi', 'poin_maksimum' => 4, 'deskripsi' => 'Menyebutkan hubungan pemberitahuan dengan pengalihan tugas.'],
                ],
            ],
            [
                'kode_soal' => 'DEMO-INF-02',
                'judul' => 'Kebijakan Pengembalian Dana Pembelian Perlengkapan',
                'kategori' => 'pemahaman_informasi',
                'skor_maksimum' => 10,
                'teks_bacaan' => "Karyawan lapangan berhak mengajukan reimbursement untuk pembelian perlengkapan darurat maksimal Rp 300.000 per transaksi dengan melampirkan struk resmi fisik dan foto barang. Pengajuan wajib diserahkan ke bagian keuangan selambat-lambatnya 3 hari kerja setelah transaksi dilakukan. Pengajuan tanpa struk fisik asli otomatis ditolak tanpa pengecualian.",
                'pertanyaan' => "Dokumen apa saja yang wajib dilampirkan agar pengajuan reimbursement perlengkapan tidak ditolak?",
                'jawaban_acuan' => [
                    ['jawaban' => 'Struk resmi fisik asli dan foto barang yang dibeli.', 'keterangan' => 'Harus menyebutkan struk resmi fisik dan foto barang.'],
                    ['jawaban' => 'Bukti struk fisik asli beserta dokumentasi foto perlengkapan.', 'keterangan' => 'Alternatif jawaban lengkap.'],
                ],
                'rubrik' => [
                    ['kriteria' => 'Penyebutan Struk Fisik Asli', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan struk fisik asli/resmi.'],
                    ['kriteria' => 'Penyebutan Foto Barang', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan dokumentasi foto barang/perlengkapan.'],
                ],
            ],
            [
                'kode_soal' => 'DEMO-INF-03',
                'judul' => 'Spesifikasi Suhu Ruang Penyimpanan Sampel',
                'kategori' => 'pemahaman_informasi',
                'skor_maksimum' => 10,
                'teks_bacaan' => "Ruang penyimpanan bahan baku farmasi A-3 harus dijaga pada rentang suhu 2°C hingga 8°C dengan kelembapan udara di bawah 60%. Jika suhu ruangan naik melebihi 10°C selama lebih dari 30 menit terus-menerus, sistem alarm pendingin cadangan akan aktif dan petugas QA wajib melakukan karantina pada seluruh bets yang sedang tersimpan.",
                'pertanyaan' => "Kondisi spesifik apa yang memicu petugas QA melakukan karantina terhadap bets bahan baku?",
                'jawaban_acuan' => [
                    ['jawaban' => 'Ketika suhu ruangan melebihi 10°C selama lebih dari 30 menit terus-menerus.', 'keterangan' => 'Harus memuat parameter suhu >10°C dan durasi >30 menit.'],
                ],
                'rubrik' => [
                    ['kriteria' => 'Ketepatan Ambang Suhu (>10°C)', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan suhu melebihi 10°C.'],
                    ['kriteria' => 'Ketepatan Parameter Durasi (>30 Menit)', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan durasi lebih dari 30 menit terus-menerus.'],
                ],
            ],
            [
                'kode_soal' => 'DEMO-INF-04',
                'judul' => 'Ketentuan Hak Akses Ruang Server Pusat',
                'kategori' => 'pemahaman_informasi',
                'skor_maksimum' => 10,
                'teks_bacaan' => "Ruang server lantai 4 hanya boleh dimasuki oleh personil berwenang dengan kartu akses tingkat A. Tamu teknis eksternal diperbolehkan masuk hanya apabila didampingi oleh minimal satu staf IT internal tetap dan telah mendaftarkan kartu identitas di pos keamanan lantai dasar.",
                'pertanyaan' => "Dua syarat apa yang harus dipenuhi tamu teknis eksternal untuk dapat masuk ke ruang server?",
                'jawaban_acuan' => [
                    ['jawaban' => 'Didampingi staf IT internal tetap dan mendaftarkan kartu identitas di pos keamanan.', 'keterangan' => 'Dua syarat lengkap.'],
                ],
                'rubrik' => [
                    ['kriteria' => 'Syarat Pendampingan IT Internal', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan didampingi oleh staf IT internal tetap.'],
                    ['kriteria' => 'Syarat Pendaftaran Identitas di Keamanan', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan registrasi identitas di pos keamanan.'],
                ],
            ],

            // --- Kategori 2: Pemahaman Instruksi (3 Soal) ---
            [
                'kode_soal' => 'DEMO-INS-01',
                'judul' => 'Instruksi Penanganan Kebocoran Bahan Kimia Ringan',
                'kategori' => 'instruksi',
                'skor_maksimum' => 10,
                'teks_bacaan' => "Instruksi Kerja Penanganan Tumpahan Kimia: Langkah 1: Pasang rambu peringatan isolasi area dalam radius 3 meter. Langkah 2: Kenakan sarung tangan nitril dan masker pelindung uap. Langkah 3: Taburkan serbuk penetral di sekeliling tumpahan sebelum menabur ke bagian tengah. Langkah 4: Kumpulkan limbah ke kantong berkode kuning dan beri label tanggal penanganan.",
                'pertanyaan' => "Menurut instruksi kerja di atas, bagaimana urutan penaburan serbuk penetral pada tumpahan yang benar?",
                'jawaban_acuan' => [
                    ['jawaban' => 'Taburkan serbuk di sekeliling/tepi tumpahan terlebih dahulu, baru kemudian ke bagian tengah.', 'keterangan' => 'Inti instruksi: sekeliling luar dulu, baru tengah.'],
                ],
                'rubrik' => [
                    ['kriteria' => 'Urutan Penaburan Bagian Luar', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan sekeliling tepi luar terlebih dahulu.'],
                    ['kriteria' => 'Urutan Penaburan Bagian Tengah', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan bagian tengah sesudahnya.'],
                ],
            ],
            [
                'kode_soal' => 'DEMO-INS-02',
                'judul' => 'Instruksi Pengunggahan Laporan Penjualan Mingguan',
                'kategori' => 'instruksi',
                'skor_maksimum' => 10,
                'teks_bacaan' => "Format penamaan file laporan: [KODE_CABANG]_[BULAN]_[MINGGU_KE] dalam format file PDF (contoh: JKT01_SEP_W3.pdf). Laporan diunggah ke folder Google Drive bersama sebelum hari Jumat pukul 17.00 WIB. File yang diunggah dalam format spreadsheet (.xlsx) tidak akan diproses oleh tim audit.",
                'pertanyaan' => "Sebutkan dua aturan format file yang harus dipatuhi saat mengunggah laporan mingguan!",
                'jawaban_acuan' => [
                    ['jawaban' => 'Format penamaan file [KODE_CABANG]_[BULAN]_[MINGGU_KE] dan menggunakan ekstensi PDF.', 'keterangan' => 'Format nama dan tipe file PDF.'],
                ],
                'rubrik' => [
                    ['kriteria' => 'Format Penamaan File', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan format penamaan [KODE_CABANG]_[BULAN]_[MINGGU_KE].'],
                    ['kriteria' => 'Ketentuan Format Ekstensi PDF', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan format PDF (bukan spreadsheet/xlsx).'],
                ],
            ],
            [
                'kode_soal' => 'DEMO-INS-03',
                'judul' => 'Instruksi Verifikasi Identitas Pelanggan Melalui Telepon',
                'kategori' => 'instruksi',
                'skor_maksimum' => 10,
                'teks_bacaan' => "Sebelum memberikan informasi nomor rekening atau transaksi, agen layanan wajib memverifikasi pelanggan dengan menanyakan 3 data: nama lengkap gadis ibu kandung, tanggal lahir, dan 4 digit terakhir nomor kartu debit. Jika salah satu data tidak cocok, agen dilarang mengulang pertanyaan lebih dari dua kali dan wajib mengarahkan pelanggan ke kantor cabang terdekat.",
                'pertanyaan' => "Tindakan apa yang wajib diambil agen jika pelanggan gagal mencocokkan data setelah dua kali percobaan?",
                'jawaban_acuan' => [
                    ['jawaban' => 'Menghentikan pengulangan pertanyaan dan mengarahkan pelanggan untuk datang ke kantor cabang terdekat.', 'keterangan' => 'Inti: stop pertanyaan & arahkan ke kantor cabang.'],
                ],
                'rubrik' => [
                    ['kriteria' => 'Penghentian Verifikasi Telepon', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan tidak mengulang pertanyaan lagi.'],
                    ['kriteria' => 'Pengalihan ke Kantor Cabang', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan mengarahkan ke kantor cabang terdekat.'],
                ],
            ],

            // --- Kategori 3: Penalaran Berdasarkan Informasi (3 Soal) ---
            [
                'kode_soal' => 'DEMO-REA-01',
                'judul' => 'Analisis Kelayakan Pengiriman Ekspedisi Darat',
                'kategori' => 'penalaran',
                'skor_maksimum' => 10,
                'teks_bacaan' => "Armada truk tipe B memiliki kapasitas angkut maksimal 4 ton dan volume kubikasi 18 meter kubik. Paket jenis Elektronik memiliki berat jenis 200 kg per meter kubik, sedangkan paket jenis Tekstil memiliki berat jenis 150 kg per meter kubik. Pengiriman tidak boleh melebihi batas berat maupun batas volume armada.",
                'pertanyaan' => "Jika truk tipe B diisi penuh 18 meter kubik dengan paket Tekstil seluruhnya, apakah truk tersebut melebihi batas berat kapasitas? Jelaskan alasannya secara singkat!",
                'jawaban_acuan' => [
                    ['jawaban' => 'Tidak melebihi batas berat. Total beratnya adalah 18 m³ x 150 kg/m³ = 2.700 kg (2,7 ton), yang masih di bawah kapasitas maksimal 4 ton.', 'keterangan' => 'Kesimpulan tidak melebihi + perhitungan 2.700 kg < 4.000 kg.'],
                ],
                'rubrik' => [
                    ['kriteria' => 'Ketepatan Kesimpulan (Tidak Melebihi)', 'poin_maksimum' => 4, 'deskripsi' => 'Menyatakan tidak melebihi kapasitas berat.'],
                    ['kriteria' => 'Kalkulasi Logis (2.700 kg < 4.000 kg)', 'poin_maksimum' => 6, 'deskripsi' => 'Menunjukkan perhitungan 18 x 150 kg = 2.700 kg dibanding batas 4.000 kg.'],
                ],
            ],
            [
                'kode_soal' => 'DEMO-REA-02',
                'judul' => 'Penalaran Prioritas Penyelesaian Keluhan Konsumen',
                'kategori' => 'penalaran',
                'skor_maksimum' => 10,
                'teks_bacaan' => "Kriteria prioritas penanganan komplain: Prioritas 1 (Kritis): Kerugian finansial langsung di atas Rp 1.000.000 atau kendala keamanan akun. Prioritas 2 (Tinggi): Gangguan akses fitur transaksi utama tanpa kerugian finansial. Prioritas 3 (Sedang): Pertanyaan status pesanan atau kendala tampilan antarmuka.",
                'pertanyaan' => "Kasus: Seorang pengguna melaporkan bahwa saldo dompet digitalnya berkurang Rp 1.200.000 tanpa melakukan transaksi apapun. Berdasarkan panduan di atas, tingkat prioritas apa yang harus ditetapkan dan mengapa?",
                'jawaban_acuan' => [
                    ['jawaban' => 'Prioritas 1 (Kritis), karena melibatkan kerugian finansial langsung di atas Rp 1.000.000 dan potensi pelanggaran keamanan akun.', 'keterangan' => 'Menyebutkan Prioritas 1 dan alasan nominal > 1 jt.'],
                ],
                'rubrik' => [
                    ['kriteria' => 'Penentuan Tingkat Prioritas 1', 'poin_maksimum' => 5, 'deskripsi' => 'Menentukan Prioritas 1 (Kritis) secara tepat.'],
                    ['kriteria' => 'Alasan Rasional Berdasarkan Teks', 'poin_maksimum' => 5, 'deskripsi' => 'Mengaitkan dengan nominal Rp 1.200.000 (> Rp 1.000.000) dan keamanan akun.'],
                ],
            ],
            [
                'kode_soal' => 'DEMO-REA-03',
                'judul' => 'Evaluasi Jadwal Rapat Antar Zona Waktu',
                'kategori' => 'penalaran',
                'skor_maksimum' => 10,
                'teks_bacaan' => "Kantor Jakarta berada pada zona waktu WIB (UTC+7), sedangkan kantor Tokyo berada pada zona waktu JST (UTC+9, selisih 2 jam lebih cepat dari WIB). Jam kerja efektif kantor Jakarta adalah 09.00 - 17.00 WIB, sedangkan jam kerja kantor Tokyo adalah 09.00 - 18.00 JST. Rapat bersama hanya boleh diadakan pada rentang jam kerja yang saling bersinggungan di kedua kantor.",
                'pertanyaan' => "Berapa jam paling awal (dalam WIB) rapat koordinasi bersama dapat dimulai agar kedua kantor sudah berada dalam jam kerja efektif?",
                'jawaban_acuan' => [
                    ['jawaban' => 'Pukul 09.00 WIB (karena pada jam tersebut di Tokyo sudah pukul 11.00 JST, keduanya sudah masuk jam kerja).', 'keterangan' => 'Menyebutkan jam 09.00 WIB dan perbandingannya.'],
                ],
                'rubrik' => [
                    ['kriteria' => 'Penentuan Jam Mulai (09.00 WIB)', 'poin_maksimum' => 5, 'deskripsi' => 'Menyebutkan pukul 09.00 WIB.'],
                    ['kriteria' => 'Penalaran Selisih Waktu Tokyo (11.00 JST)', 'poin_maksimum' => 5, 'deskripsi' => 'Menjelaskan bahwa kantor Jakarta baru mulai pukul 09.00 WIB sedangkan Tokyo sudah buka sejak 07.00 WIB (09.00 JST).'],
                ],
            ],
        ];

        $createdSoalIds = [];

        foreach ($soalDefinitions as $def) {
            $soal = Soal::updateOrCreate(
                ['kode_soal' => $def['kode_soal']],
                [
                    'user_id' => $hrUser->id,
                    'judul' => $def['judul'],
                    'kategori' => $def['kategori'],
                    'skor_maksimum' => $def['skor_maksimum'],
                    'teks_bacaan' => $def['teks_bacaan'],
                    'pertanyaan' => $def['pertanyaan'],
                    'sumber' => self::DISCLAIMER,
                    'is_dummy' => true,
                    'status' => 'aktif',
                    'versi' => 1,
                ]
            );

            // Sync jawaban acuan
            $soal->jawabanAcuan()->delete();
            foreach ($def['jawaban_acuan'] as $ja) {
                SoalJawabanAcuan::create([
                    'soal_id' => $soal->id,
                    'jawaban' => $ja['jawaban'],
                    'keterangan' => $ja['keterangan'] ?? null,
                ]);
            }

            // Sync rubrik
            $soal->rubrik()->delete();
            $urutan = 1;
            foreach ($def['rubrik'] as $r) {
                SoalRubrik::create([
                    'soal_id' => $soal->id,
                    'kriteria' => $r['kriteria'],
                    'poin_maksimum' => $r['poin_maksimum'],
                    'deskripsi' => $r['deskripsi'],
                    'urutan' => $urutan++,
                ]);
            }

            // Simpan snapshot
            SoalSnapshot::updateOrCreate(
                ['soal_id' => $soal->id, 'versi' => 1],
                ['data' => $soal->toSnapshotArray()]
            );

            $createdSoalIds[] = $soal->id;
        }

        // 3. Buat 2 Paket Tes
        // Paket 1: Pemahaman Informasi & Prosedur (5 Soal)
        $soalPaket1 = array_slice($createdSoalIds, 0, 5);
        $paket1 = PaketTes::updateOrCreate(
            ['nama' => 'Paket A: Pemahaman Informasi & Instruksi Operasional'],
            [
                'user_id' => $hrUser->id,
                'deskripsi' => 'Evaluasi kemampuan memahami teks prosedur kerja, regulasi operasional, dan instruksi penanganan.',
                'petunjuk' => "1. Bacalah setiap teks bacaan secara seksama.\n2. Tuliskan jawaban singkat, padat, dan langsung ke inti pertanyaan.\n3. Gunakan bahasa Indonesia yang baku dan jelas.\n4. Waktu pengerjaan akan otomatis dihitung sejak Anda menekan tombol Mulai.",
                'durasi_menit' => 25,
                'status' => 'aktif',
            ]
        );
        $sync1 = [];
        $seq = 1;
        foreach ($soalPaket1 as $sid) {
            $sync1[$sid] = ['urutan' => $seq++];
        }
        $paket1->soal()->sync($sync1);
        $paket1->recalculateTotalSkor();
        if ($lowongan1) {
            $paket1->lowongan()->syncWithoutDetaching([$lowongan1->id]);
        }

        // Paket 2: Logika & Penalaran Kerja (5 Soal)
        $soalPaket2 = array_slice($createdSoalIds, 5, 5);
        $paket2 = PaketTes::updateOrCreate(
            ['nama' => 'Paket B: Penalaran & Logika Analitis Kerja'],
            [
                'user_id' => $hrUser->id,
                'deskripsi' => 'Evaluasi daya nalar logis, perhitungan parameter kerja, dan pengambilan keputusan berdasarkan informasi tertulis.',
                'petunjuk' => "1. Analisis fakta dan angka yang disajikan dalam teks informasi.\n2. Berikan jawaban logis beserta alasan singkat atau perhitungannya.\n3. Jangan mengasumsikan data yang tidak tercantum di dalam teks.",
                'durasi_menit' => 30,
                'status' => 'aktif',
            ]
        );
        $sync2 = [];
        $seq = 1;
        foreach ($soalPaket2 as $sid) {
            $sync2[$sid] = ['urutan' => $seq++];
        }
        $paket2->soal()->sync($sync2);
        $paket2->recalculateTotalSkor();
        if ($lowongan2) {
            $paket2->lowongan()->syncWithoutDetaching([$lowongan2->id]);
        }

        // 4. Buat Akun Kandidat Demo dan Lamaran
        $kandidat1 = User::firstOrCreate(
            ['email' => 'budi.kandidat@murialo.test'],
            ['name' => 'Budi Santoso (Kandidat Demo)', 'password' => 'password', 'role' => 'kandidat']
        );
        $kandidat2 = User::firstOrCreate(
            ['email' => 'siti.kandidat@murialo.test'],
            ['name' => 'Siti Rahma (Kandidat Demo)', 'password' => 'password', 'role' => 'kandidat']
        );

        if ($lowongan1) {
            // Lamaran Budi
            $lamaranBudi = Lamaran::firstOrCreate(
                ['lowongan_id' => $lowongan1->id, 'user_id' => $kandidat1->id],
                ['status' => 'proses_tes', 'catatan' => 'Berkas lengkap dan sesuai kualifikasi awal.']
            );

            // Penugasan Paket 1 untuk Budi (Sudah selesai pengerjaan dan sudah dinilai oleh HR)
            $penugasanBudi = PenugasanTes::firstOrCreate(
                ['lamaran_id' => $lamaranBudi->id, 'paket_tes_id' => $paket1->id],
                [
                    'user_id' => $kandidat1->id,
                    'paket_snapshot' => $paket1->toFullSnapshot(),
                    'waktu_tersedia' => now()->subDays(2),
                    'batas_waktu' => now()->addDays(5),
                    'status_pengerjaan' => 'selesai',
                    'status_penilaian' => 'selesai_dinilai',
                    'is_published' => true,
                    'published_at' => now()->subHours(4),
                ]
            );

            // Percobaan Budi
            $percobaanBudi = PercobaanTes::firstOrCreate(
                ['penugasan_tes_id' => $penugasanBudi->id],
                [
                    'waktu_mulai' => now()->subDays(1)->setTime(10, 0),
                    'waktu_berakhir' => now()->subDays(1)->setTime(10, 25),
                    'waktu_selesai' => now()->subDays(1)->setTime(10, 22),
                    'status' => 'diserahkan',
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                ]
            );

            // Jawaban Kandidat Budi
            $jawabanSampleBudi = [
                'DEMO-INF-01' => 'Staf wajib memberitahukan paling lambat 1 jam sebelum jam pergantian shift agar tidak dipindahkan ke tugas administratif.',
                'DEMO-INF-02' => 'Harus melampirkan struk fisik asli dan foto perlengkapan yang dibeli dalam kurun waktu 3 hari kerja.',
                'DEMO-INF-03' => 'Ketika suhu ruangan A-3 naik di atas 10 derajat celcius selama lebih dari 30 menit terus menerus.',
                'DEMO-INF-04' => 'Tamu teknis harus didampingi staf IT internal dan menitipkan kartu tanda pengenal di pos keamanan.',
                'DEMO-INS-01' => 'Serbuk penetral ditaburkan di sekeliling luar tumpahan terlebih dahulu sebelum menabur ke bagian tengah.',
            ];

            foreach ($paket1->soal as $s) {
                $jawab = JawabanKandidat::firstOrCreate(
                    ['percobaan_tes_id' => $percobaanBudi->id, 'soal_id' => $s->id],
                    [
                        'urutan' => $s->pivot->urutan,
                        'jawaban' => $jawabanSampleBudi[$s->kode_soal] ?? 'Jawaban singkat kandidat.',
                        'terakhir_disimpan_pada' => now()->subDays(1)->setTime(10, 20),
                    ]
                );
            }

            // Penilaian Manual HR untuk Budi
            $penilaianBudi = Penilaian::firstOrCreate(
                ['penugasan_tes_id' => $penugasanBudi->id],
                [
                    'penilai_id' => $hrUser->id,
                    'total_skor_diperoleh' => 46,
                    'total_skor_maksimum' => 50,
                    'nilai_akhir' => 92.00,
                    'catatan_umum' => 'Pemahaman teks dan instruksi sangat baik dan runut. Memenuhi kriteria standar operasional.',
                    'waktu_penilaian' => now()->subHours(5),
                ]
            );

            // Detail Penilaian Budi
            foreach ($percobaanBudi->jawaban as $jw) {
                $soalItem = Soal::find($jw->soal_id);
                if ($soalItem) {
                    foreach ($soalItem->rubrik as $r) {
                        PenilaianDetail::firstOrCreate(
                            [
                                'penilaian_id' => $penilaianBudi->id,
                                'jawaban_kandidat_id' => $jw->id,
                                'soal_rubrik_id' => $r->id,
                            ],
                            [
                                'kriteria' => $r->kriteria,
                                'skor_maksimum' => $r->poin_maksimum,
                                'skor' => $r->poin_maksimum, // Nilai sempurna untuk demo
                                'catatan' => 'Sesuai dengan panduan acuan.',
                            ]
                        );
                    }
                }
            }

            // Lamaran Siti (Belum dimulai, siap dikerjakan oleh kandidat demo)
            $lamaranSiti = Lamaran::firstOrCreate(
                ['lowongan_id' => $lowongan1->id, 'user_id' => $kandidat2->id],
                ['status' => 'proses_tes', 'catatan' => 'Siap mengikuti tes online.']
            );

            PenugasanTes::firstOrCreate(
                ['lamaran_id' => $lamaranSiti->id, 'paket_tes_id' => $paket2->id],
                [
                    'user_id' => $kandidat2->id,
                    'paket_snapshot' => $paket2->toFullSnapshot(),
                    'waktu_tersedia' => now()->subHour(),
                    'batas_waktu' => now()->addDays(3),
                    'status_pengerjaan' => 'belum_dimulai',
                    'status_penilaian' => 'belum_dinilai',
                    'is_published' => false,
                ]
            );
        }
    }
}
