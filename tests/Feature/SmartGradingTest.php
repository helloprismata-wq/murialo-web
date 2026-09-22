<?php

namespace Tests\Feature;

use App\Models\JawabanKandidat;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\PaketTes;
use App\Models\Penilaian;
use App\Models\PenugasanTes;
use App\Models\PercobaanTes;
use App\Models\Soal;
use App\Models\User;
use App\Services\PaketTesService;
use App\Services\PenilaianService;
use App\Services\PenugasanTesService;
use App\Services\SoalService;
use App\Services\TesKandidatService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SmartGradingTest extends TestCase
{
    use RefreshDatabase;

    private User $hr;
    private User $kandidat;
    private User $kandidatLain;
    private Lowongan $lowongan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hr = User::factory()->create([
            'email' => 'hr@murialo.test',
            'role' => 'hr',
            'password' => 'password',
        ]);

        $this->kandidat = User::factory()->create([
            'email' => 'kandidat@murialo.test',
            'role' => 'kandidat',
            'password' => 'password',
        ]);

        $this->kandidatLain = User::factory()->create([
            'email' => 'kandidat2@murialo.test',
            'role' => 'kandidat',
            'password' => 'password',
        ]);

        $this->lowongan = new Lowongan([
            'judul' => 'Staf Operasional',
            'perusahaan' => 'PT Murialo',
            'lokasi' => 'Kudus',
            'tipe_pekerjaan' => 'full_time',
            'deskripsi' => 'Deskripsi pekerjaan operasional.',
            'persyaratan' => 'Teliti dan disiplin.',
            'skills' => 'Logistik, Administrasi',
            'status' => 'aktif',
        ]);
        $this->lowongan->user()->associate($this->hr);
        $this->lowongan->save();
    }

    private function createValidSoal(string $kode = 'SOAL-001', int $skor = 10): Soal
    {
        $soalService = app(SoalService::class);
        return $soalService->createSoal($this->hr, [
            'kode_soal' => $kode,
            'judul' => 'Soal Uji ' . $kode,
            'kategori' => 'pemahaman_informasi',
            'skor_maksimum' => $skor,
            'teks_bacaan' => 'Konteks bacaan operasional.',
            'pertanyaan' => 'Apa syarat utama?',
            'status' => 'aktif',
            'jawaban_acuan' => [
                ['jawaban' => 'Jawaban acuan rahasia HR.', 'keterangan' => 'Kunci utama'],
            ],
            'rubrik' => [
                ['kriteria' => 'Kriteria 1', 'poin_maksimum' => $skor - 4, 'deskripsi' => 'Poin akurasi'],
                ['kriteria' => 'Kriteria 2', 'poin_maksimum' => 4, 'deskripsi' => 'Poin kelengkapan'],
            ],
        ]);
    }

    private function createValidPaket(): PaketTes
    {
        $soal1 = $this->createValidSoal('SOAL-A', 10);
        $soal2 = $this->createValidSoal('SOAL-B', 10);

        $paketService = app(PaketTesService::class);
        return $paketService->createPaket($this->hr, [
            'nama' => 'Paket Tes Uji',
            'durasi_menit' => 30,
            'status' => 'aktif',
            'soal_ids' => [$soal1->id, $soal2->id],
            'lowongan_ids' => [$this->lowongan->id],
        ]);
    }

    public function test_access_control_hr_vs_kandidat(): void
    {
        // 1. Guest ditolak
        $this->get('/soal')->assertUnauthorized();
        $this->get('/paket')->assertUnauthorized();
        $this->get('/tes-saya')->assertUnauthorized();

        // 2. Kandidat tidak boleh membuka halaman HR
        $this->actingAs($this->kandidat)
            ->get('/soal')
            ->assertForbidden();

        $this->actingAs($this->kandidat)
            ->get('/paket')
            ->assertForbidden();

        $this->actingAs($this->kandidat)
            ->get('/penugasan')
            ->assertForbidden();

        $this->actingAs($this->kandidat)
            ->get('/penilaian')
            ->assertForbidden();

        // 3. HR dapat mengakses halaman HR
        $this->actingAs($this->hr)
            ->get('/soal')
            ->assertOk();

        $this->actingAs($this->hr)
            ->get('/paket')
            ->assertOk();

        // 4. HR tidak dapat mengakses tes kandidat
        $this->actingAs($this->hr)
            ->get('/tes-saya')
            ->assertForbidden();
    }

    public function test_soal_and_rubrik_validation(): void
    {
        $soalService = app(SoalService::class);

        // Jumlah poin rubrik (5 + 3 = 8) tidak sesuai skor maksimum (10)
        $this->expectException(ValidationException::class);
        $soalService->createSoal($this->hr, [
            'kode_soal' => 'INVALID-001',
            'judul' => 'Soal Salah Rubrik',
            'kategori' => 'instruksi',
            'skor_maksimum' => 10,
            'pertanyaan' => 'Pertanyaan uji?',
            'status' => 'draft',
            'jawaban_acuan' => [['jawaban' => 'Kunci']],
            'rubrik' => [
                ['kriteria' => 'Kriteria A', 'poin_maksimum' => 5],
                ['kriteria' => 'Kriteria B', 'poin_maksimum' => 3], // Total 8 != 10
            ],
        ]);
    }

    public function test_soal_versioning_and_snapshot_preservation(): void
    {
        $soal = $this->createValidSoal('SNAP-01', 10);
        $this->assertEquals(1, $soal->versi);
        $this->assertCount(1, $soal->snapshots);

        // Buat paket dan tugaskan ke kandidat
        $paketService = app(PaketTesService::class);
        $paket = $paketService->createPaket($this->hr, [
            'nama' => 'Paket Snapshot',
            'durasi_menit' => 20,
            'status' => 'aktif',
            'soal_ids' => [$soal->id],
        ]);

        $lamaran = Lamaran::create([
            'lowongan_id' => $this->lowongan->id,
            'user_id' => $this->kandidat->id,
            'status' => 'diajukan',
        ]);

        $penugasanService = app(PenugasanTesService::class);
        $penugasan = $penugasanService->assignPaket($lamaran, $paket, now()->subHour(), now()->addDays(2));

        $snapshotPertanyaanAwal = $penugasan->paket_snapshot['soal'][0]['pertanyaan'];

        // HR mengedit soal setelah penugasan dibuat
        $soalService = app(SoalService::class);
        $soalService->updateSoal($soal, [
            'kode_soal' => 'SNAP-01',
            'judul' => 'Judul Baru Setelah Edit',
            'kategori' => 'pemahaman_informasi',
            'skor_maksimum' => 10,
            'pertanyaan' => 'Pertanyaan yang sudah diubah total.',
            'status' => 'aktif',
            'jawaban_acuan' => [['jawaban' => 'Kunci Baru']],
            'rubrik' => [
                ['kriteria' => 'Kriteria Baru', 'poin_maksimum' => 10],
            ],
        ]);

        $soal->refresh();
        $this->assertEquals(2, $soal->versi);
        $this->assertCount(2, $soal->snapshots);

        // Pastikan penugasan tes kandidat sebelumnya tetap menggunakan snapshot versi awal
        $penugasan->refresh();
        $this->assertEquals($snapshotPertanyaanAwal, $penugasan->paket_snapshot['soal'][0]['pertanyaan']);
        $this->assertNotEquals($soal->pertanyaan, $penugasan->paket_snapshot['soal'][0]['pertanyaan']);
    }

    public function test_candidate_cannot_access_another_candidates_test(): void
    {
        $paket = $this->createValidPaket();
        $lamaran = Lamaran::create([
            'lowongan_id' => $this->lowongan->id,
            'user_id' => $this->kandidat->id,
            'status' => 'diajukan',
        ]);

        $penugasanService = app(PenugasanTesService::class);
        $penugasan = $penugasanService->assignPaket($lamaran, $paket, now()->subHour(), now()->addDays(2));

        // Kandidat Lain mencoba mengakses tes milik Kandidat 1
        $this->actingAs($this->kandidatLain)
            ->get("/tes-saya/{$penugasan->id}")
            ->assertForbidden();

        $this->actingAs($this->kandidatLain)
            ->post("/tes-saya/{$penugasan->id}/mulai")
            ->assertForbidden();
    }

    public function test_answer_keys_and_rubrics_are_never_leaked_to_candidate(): void
    {
        $paket = $this->createValidPaket();
        $lamaran = Lamaran::create([
            'lowongan_id' => $this->lowongan->id,
            'user_id' => $this->kandidat->id,
            'status' => 'diajukan',
        ]);

        $penugasanService = app(PenugasanTesService::class);
        $penugasan = $penugasanService->assignPaket($lamaran, $paket, now()->subHour(), now()->addDays(2));

        // Mulai tes
        $tesService = app(TesKandidatService::class);
        $tesService->startTes($penugasan, $this->kandidat);

        // Buka halaman pengerjaan
        $response = $this->actingAs($this->kandidat)
            ->get("/tes-saya/{$penugasan->id}/kerjakan");

        $response->assertOk();

        // Pastikan kata kunci jawaban acuan dan teks rubrik TIDAK ada di response HTML/JSON kandidat
        $response->assertDontSee('Jawaban acuan rahasia HR');
        $response->assertDontSee('Poin akurasi');
        $response->assertDontSee('Poin kelengkapan');
        $response->assertDontSee('soal_rubrik');
    }

    public function test_autosave_and_submission(): void
    {
        $paket = $this->createValidPaket();
        $lamaran = Lamaran::create([
            'lowongan_id' => $this->lowongan->id,
            'user_id' => $this->kandidat->id,
            'status' => 'diajukan',
        ]);

        $penugasanService = app(PenugasanTesService::class);
        $penugasan = $penugasanService->assignPaket($lamaran, $paket, now()->subHour(), now()->addDays(2));

        $tesService = app(TesKandidatService::class);
        $percobaan = $tesService->startTes($penugasan, $this->kandidat);

        $soalPertamaId = $penugasan->paket_snapshot['soal'][0]['id'];

        // 1. Autosave via AJAX
        $response = $this->actingAs($this->kandidat)
            ->postJson("/tes-saya/{$penugasan->id}/autosave", [
                'soal_id' => $soalPertamaId,
                'jawaban' => 'Jawaban tersimpan melalui autosave.',
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('jawaban_kandidat', [
            'percobaan_tes_id' => $percobaan->id,
            'soal_id' => $soalPertamaId,
            'jawaban' => 'Jawaban tersimpan melalui autosave.',
        ]);

        // 2. Submit jawaban akhir
        $submitResponse = $this->actingAs($this->kandidat)
            ->post("/tes-saya/{$penugasan->id}/submit", [
                'jawaban' => [
                    $soalPertamaId => 'Jawaban final saat submit.',
                ],
            ]);

        $submitResponse->assertRedirect("/tes-saya/{$penugasan->id}");

        $penugasan->refresh();
        $this->assertEquals('selesai', $penugasan->status_pengerjaan);
        $this->assertEquals('diserahkan', $penugasan->percobaan->status);
    }

    public function test_answers_are_locked_after_submit_or_expiration(): void
    {
        $paket = $this->createValidPaket();
        $lamaran = Lamaran::create([
            'lowongan_id' => $this->lowongan->id,
            'user_id' => $this->kandidat->id,
            'status' => 'diajukan',
        ]);

        $penugasanService = app(PenugasanTesService::class);
        $penugasan = $penugasanService->assignPaket($lamaran, $paket, now()->subHour(), now()->addDays(2));

        $tesService = app(TesKandidatService::class);
        $percobaan = $tesService->startTes($penugasan, $this->kandidat);
        $soalPertamaId = $penugasan->paket_snapshot['soal'][0]['id'];

        // Submit tes sehingga terkunci
        $tesService->submitTes($percobaan);

        // Upaya autosave setelah submit harus ditolak
        $response = $this->actingAs($this->kandidat)
            ->postJson("/tes-saya/{$penugasan->id}/autosave", [
                'soal_id' => $soalPertamaId,
                'jawaban' => 'Perubahan ilegal setelah submit',
            ]);

        $response->assertStatus(422);
    }

    public function test_automatic_finalization_of_expired_tests(): void
    {
        $paket = $this->createValidPaket();
        $lamaran = Lamaran::create([
            'lowongan_id' => $this->lowongan->id,
            'user_id' => $this->kandidat->id,
            'status' => 'diajukan',
        ]);

        $penugasanService = app(PenugasanTesService::class);
        $penugasan = $penugasanService->assignPaket($lamaran, $paket, now()->subDays(2), now()->addDays(1));

        $tesService = app(TesKandidatService::class);
        $percobaan = $tesService->startTes($penugasan, $this->kandidat);

        // Simulasikan waktu pengerjaan telah lewat (waktu_berakhir di masa lalu)
        $percobaan->update([
            'waktu_berakhir' => now()->subMinutes(5),
        ]);

        // Jalankan perintah console auto-finalisasi
        $this->artisan('tes:finalize-expired')
            ->expectsOutputToContain('Berhasil memfinalisasi 1 pengerjaan tes yang kedaluwarsa.')
            ->assertSuccessful();

        $percobaan->refresh();
        $this->assertEquals('waktu_habis', $percobaan->status);

        $penugasan->refresh();
        $this->assertEquals('selesai', $penugasan->status_pengerjaan);
    }

    public function test_duplicate_assignment_prevention(): void
    {
        $paket = $this->createValidPaket();
        $lamaran = Lamaran::create([
            'lowongan_id' => $this->lowongan->id,
            'user_id' => $this->kandidat->id,
            'status' => 'diajukan',
        ]);

        $penugasanService = app(PenugasanTesService::class);
        $penugasanService->assignPaket($lamaran, $paket, now()->subHour(), now()->addDays(2));

        // Penugasan kedua untuk lamaran dan paket yang sama harus ditolak
        $this->expectException(ValidationException::class);
        $penugasanService->assignPaket($lamaran, $paket, now()->subHour(), now()->addDays(2));
    }

    public function test_manual_scoring_limits_and_calculation(): void
    {
        $paket = $this->createValidPaket();
        $lamaran = Lamaran::create([
            'lowongan_id' => $this->lowongan->id,
            'user_id' => $this->kandidat->id,
            'status' => 'diajukan',
        ]);

        $penugasanService = app(PenugasanTesService::class);
        $penugasan = $penugasanService->assignPaket($lamaran, $paket, now()->subHour(), now()->addDays(2));

        $tesService = app(TesKandidatService::class);
        $percobaan = $tesService->startTes($penugasan, $this->kandidat);
        $tesService->submitTes($percobaan);

        $penugasan->refresh();
        $penilaianService = app(PenilaianService::class);

        $soal1 = $penugasan->paket_snapshot['soal'][0];
        $rubrik1Id = $soal1['rubrik'][0]['id'];
        $maxPoinRubrik1 = $soal1['rubrik'][0]['poin_maksimum']; // 6

        $jawaban1Id = $percobaan->jawaban->firstWhere('soal_id', $soal1['id'])->id;

        // 1. Skor negatif harus ditolak
        try {
            $penilaianService->savePenilaian($penugasan, $this->hr, [
                $jawaban1Id => [$rubrik1Id => ['skor' => -2]],
            ]);
            $this->fail('Harusnya skor negatif melempar ValidationException');
        } catch (ValidationException $e) {
            $this->assertTrue(true);
        }

        // 2. Skor melebihi batas kriteria (misal 99 > 6) harus ditolak
        try {
            $penilaianService->savePenilaian($penugasan, $this->hr, [
                $jawaban1Id => [$rubrik1Id => ['skor' => 99]],
            ]);
            $this->fail('Harusnya skor melebihi batas melempar ValidationException');
        } catch (ValidationException $e) {
            $this->assertTrue(true);
        }

        // 3. Penilaian yang sah: total 15 dari 20 (nilai akhir 75%)
        $scoresPayload = [];
        foreach ($penugasan->paket_snapshot['soal'] as $s) {
            $jId = $percobaan->jawaban->firstWhere('soal_id', $s['id'])->id;
            foreach ($s['rubrik'] as $idx => $r) {
                // Beri nilai separuh atau penuh
                $scoresPayload[$jId][$r['id']] = ['skor' => $idx === 0 ? $r['poin_maksimum'] : 2];
            }
        }

        $penilaian = $penilaianService->savePenilaian($penugasan, $this->hr, $scoresPayload, 'Catatan HR uji.', true);

        $penugasan->refresh();
        $this->assertEquals('selesai_dinilai', $penugasan->status_penilaian);
        $this->assertGreaterThan(0, $penilaian->nilai_akhir);
    }

    public function test_results_are_only_visible_to_candidate_after_publication(): void
    {
        $paket = $this->createValidPaket();
        $lamaran = Lamaran::create([
            'lowongan_id' => $this->lowongan->id,
            'user_id' => $this->kandidat->id,
            'status' => 'diajukan',
        ]);

        $penugasanService = app(PenugasanTesService::class);
        $penugasan = $penugasanService->assignPaket($lamaran, $paket, now()->subHour(), now()->addDays(2));

        $tesService = app(TesKandidatService::class);
        $percobaan = $tesService->startTes($penugasan, $this->kandidat);
        $tesService->submitTes($percobaan);

        $penugasan->refresh();
        $penilaianService = app(PenilaianService::class);

        $scoresPayload = [];
        foreach ($penugasan->paket_snapshot['soal'] as $s) {
            $jId = $percobaan->jawaban->firstWhere('soal_id', $s['id'])->id;
            foreach ($s['rubrik'] as $r) {
                $scoresPayload[$jId][$r['id']] = ['skor' => $r['poin_maksimum']];
            }
        }

        $penilaian = $penilaianService->savePenilaian($penugasan, $this->hr, $scoresPayload, 'Lulus istimewa', true);

        // Sebelum dipublikasikan: kandidat TIDAK melihat nilai akhir 100
        $response = $this->actingAs($this->kandidat)->get("/tes-saya/{$penugasan->id}");
        $response->assertOk();
        $response->assertDontSee('Nilai Anda Telah Dipublikasikan');
        $response->assertSee('sedang dalam proses penilaian oleh HR');

        // HR mempublikasikan hasil
        $penilaianService->setPublishStatus($penugasan, true);

        // Sesudah dipublikasikan: kandidat dapat melihat nilai akhir
        $responseAfter = $this->actingAs($this->kandidat)->get("/tes-saya/{$penugasan->id}");
        $responseAfter->assertOk();
        $responseAfter->assertSee('Nilai Anda Telah Dipublikasikan');
        $responseAfter->assertSee((string) $penilaian->nilai_akhir);
    }

    public function test_score_modification_requires_reason_and_logs_history(): void
    {
        $paket = $this->createValidPaket();
        $lamaran = Lamaran::create([
            'lowongan_id' => $this->lowongan->id,
            'user_id' => $this->kandidat->id,
            'status' => 'diajukan',
        ]);

        $penugasanService = app(PenugasanTesService::class);
        $penugasan = $penugasanService->assignPaket($lamaran, $paket, now()->subHour(), now()->addDays(2));

        $tesService = app(TesKandidatService::class);
        $percobaan = $tesService->startTes($penugasan, $this->kandidat);
        $tesService->submitTes($percobaan);

        $penugasan->refresh();
        $penilaianService = app(PenilaianService::class);

        $scoresPayload = [];
        foreach ($penugasan->paket_snapshot['soal'] as $s) {
            $jId = $percobaan->jawaban->firstWhere('soal_id', $s['id'])->id;
            foreach ($s['rubrik'] as $r) {
                $scoresPayload[$jId][$r['id']] = ['skor' => $r['poin_maksimum']];
            }
        }

        // Selesaikan penilaian pertama
        $penilaianService->savePenilaian($penugasan, $this->hr, $scoresPayload, 'Nilai awal', true);

        // 1. Mengubah penilaian yang sudah selesai TANPA alasan perubahan harus ditolak
        try {
            $penilaianService->savePenilaian($penugasan, $this->hr, $scoresPayload, 'Edit tanpa alasan', true, '');
            $this->fail('Harusnya melempar ValidationException jika alasan perubahan kosong');
        } catch (ValidationException $e) {
            $this->assertTrue(true);
        }

        // 2. Mengubah dengan alasan perubahan yang valid
        $scoresPayloadEdited = $scoresPayload;
        $firstSoal = $penugasan->paket_snapshot['soal'][0];
        $firstJawabanId = $percobaan->jawaban->firstWhere('soal_id', $firstSoal['id'])->id;
        $firstRubrikId = $firstSoal['rubrik'][0]['id'];
        $scoresPayloadEdited[$firstJawabanId][$firstRubrikId]['skor'] = 2; // turunkan skor

        $penilaianService->savePenilaian(
            $penugasan,
            $this->hr,
            $scoresPayloadEdited,
            'Penyesuaian setelah re-evaluasi',
            true,
            'Klarifikasi jawaban kandidat kurang lengkap pada poin awal.'
        );

        $penugasan->refresh();
        $this->assertCount(1, $penugasan->penilaian->riwayat);
        $this->assertEquals(
            'Klarifikasi jawaban kandidat kurang lengkap pada poin awal.',
            $penugasan->penilaian->riwayat->first()->alasan_perubahan
        );
    }
}
