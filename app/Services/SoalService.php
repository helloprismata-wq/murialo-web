<?php

namespace App\Services;

use App\Models\Soal;
use App\Models\SoalJawabanAcuan;
use App\Models\SoalRubrik;
use App\Models\SoalSnapshot;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SoalService
{
    /**
     * Create a new soal with sample answers, rubrics, and initial snapshot.
     */
    public function createSoal(User $user, array $data): Soal
    {
        return DB::transaction(function () use ($user, $data) {
            $this->validateRubrikSum($data['skor_maksimum'], $data['rubrik'] ?? []);

            $soal = Soal::create([
                'user_id' => $user->id,
                'kode_soal' => $data['kode_soal'],
                'judul' => $data['judul'],
                'kategori' => $data['kategori'],
                'teks_bacaan' => $data['teks_bacaan'] ?? null,
                'pertanyaan' => $data['pertanyaan'],
                'skor_maksimum' => $data['skor_maksimum'],
                'sumber' => $data['sumber'] ?? null,
                'is_dummy' => !empty($data['is_dummy']),
                'status' => $data['status'] ?? 'draft',
                'versi' => 1,
            ]);

            $this->syncJawabanAcuan($soal, $data['jawaban_acuan'] ?? []);
            $this->syncRubrik($soal, $data['rubrik'] ?? []);

            $this->createSnapshot($soal);

            return $soal;
        });
    }

    /**
     * Update an existing soal. Increments version and creates a new snapshot.
     */
    public function updateSoal(Soal $soal, array $data): Soal
    {
        return DB::transaction(function () use ($soal, $data) {
            $this->validateRubrikSum($data['skor_maksimum'], $data['rubrik'] ?? []);

            $newVersion = $soal->versi + 1;

            $soal->update([
                'kode_soal' => $data['kode_soal'],
                'judul' => $data['judul'],
                'kategori' => $data['kategori'],
                'teks_bacaan' => $data['teks_bacaan'] ?? null,
                'pertanyaan' => $data['pertanyaan'],
                'skor_maksimum' => $data['skor_maksimum'],
                'sumber' => $data['sumber'] ?? null,
                'is_dummy' => !empty($data['is_dummy']),
                'status' => $data['status'] ?? $soal->status,
                'versi' => $newVersion,
            ]);

            $this->syncJawabanAcuan($soal, $data['jawaban_acuan'] ?? []);
            $this->syncRubrik($soal, $data['rubrik'] ?? []);

            $this->createSnapshot($soal);

            return $soal;
        });
    }

    /**
     * Delete or archive soal. Prevent hard deletion if ever assigned in a test.
     */
    public function deleteSoal(Soal $soal): void
    {
        // Check if the soal belongs to a paket that has penugasan
        $hasAssignments = DB::table('penugasan_tes')
            ->join('paket_tes_soal', 'penugasan_tes.paket_tes_id', '=', 'paket_tes_soal.paket_tes_id')
            ->where('paket_tes_soal.soal_id', $soal->id)
            ->exists();

        if ($hasAssignments) {
            // Soft delete/archive only to protect test history
            $soal->update(['status' => 'arsip']);
            $soal->delete();
        } else {
            $soal->delete();
        }
    }

    public function archiveSoal(Soal $soal): void
    {
        $soal->update(['status' => 'arsip']);
    }

    public function createSnapshot(Soal $soal): SoalSnapshot
    {
        $soal->load(['jawabanAcuan', 'rubrik']);
        return SoalSnapshot::create([
            'soal_id' => $soal->id,
            'versi' => $soal->versi,
            'data' => $soal->toSnapshotArray(),
        ]);
    }

    private function validateRubrikSum(int $skorMaksimum, array $rubrikItems): void
    {
        $sum = 0;
        foreach ($rubrikItems as $item) {
            $sum += (int) ($item['poin_maksimum'] ?? 0);
        }

        if ($sum !== $skorMaksimum) {
            throw ValidationException::withMessages([
                'rubrik' => "Total poin kriteria rubrik ({$sum}) harus sama dengan skor maksimum soal ({$skorMaksimum}).",
            ]);
        }
    }

    private function syncJawabanAcuan(Soal $soal, array $items): void
    {
        $soal->jawabanAcuan()->delete();
        foreach ($items as $item) {
            if (!empty($item['jawaban'])) {
                SoalJawabanAcuan::create([
                    'soal_id' => $soal->id,
                    'jawaban' => trim($item['jawaban']),
                    'keterangan' => trim($item['keterangan'] ?? ''),
                ]);
            }
        }
    }

    private function syncRubrik(Soal $soal, array $items): void
    {
        $soal->rubrik()->delete();
        $urutan = 1;
        foreach ($items as $item) {
            if (!empty($item['kriteria'])) {
                SoalRubrik::create([
                    'soal_id' => $soal->id,
                    'kriteria' => trim($item['kriteria']),
                    'poin_maksimum' => (int) $item['poin_maksimum'],
                    'deskripsi' => trim($item['deskripsi'] ?? ''),
                    'urutan' => $urutan++,
                ]);
            }
        }
    }
}
