<?php

namespace App\Services;

use App\Models\PaketTes;
use App\Models\PaketTesSoal;
use App\Models\Soal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaketTesService
{
    /**
     * Create a new Paket Tes.
     */
    public function createPaket(User $user, array $data): PaketTes
    {
        return DB::transaction(function () use ($user, $data) {
            $paket = PaketTes::create([
                'user_id' => $user->id,
                'nama' => $data['nama'],
                'deskripsi' => $data['deskripsi'] ?? null,
                'petunjuk' => $data['petunjuk'] ?? null,
                'durasi_menit' => (int) $data['durasi_menit'],
                'status' => 'draft',
            ]);

            if (!empty($data['soal_ids'])) {
                $this->syncSoalWithOrder($paket, $data['soal_ids']);
            }

            if (!empty($data['lowongan_ids'])) {
                $paket->lowongan()->sync($data['lowongan_ids']);
            }

            $paket->recalculateTotalSkor();

            if (($data['status'] ?? 'draft') === 'aktif') {
                $this->activatePaket($paket);
            }

            return $paket;
        });
    }

    /**
     * Update an existing Paket Tes.
     */
    public function updatePaket(PaketTes $paket, array $data): PaketTes
    {
        return DB::transaction(function () use ($paket, $data) {
            $paket->update([
                'nama' => $data['nama'],
                'deskripsi' => $data['deskripsi'] ?? null,
                'petunjuk' => $data['petunjuk'] ?? null,
                'durasi_menit' => (int) $data['durasi_menit'],
            ]);

            if (isset($data['soal_ids'])) {
                $this->syncSoalWithOrder($paket, $data['soal_ids']);
            }

            if (isset($data['lowongan_ids'])) {
                $paket->lowongan()->sync($data['lowongan_ids']);
            }

            $paket->recalculateTotalSkor();

            $targetStatus = $data['status'] ?? $paket->status;
            if ($targetStatus === 'aktif') {
                $this->activatePaket($paket);
            } else {
                $paket->update(['status' => $targetStatus]);
            }

            return $paket;
        });
    }

    /**
     * Activate a test package, validating that it contains complete and valid questions.
     */
    public function activatePaket(PaketTes $paket): void
    {
        if ($paket->soal()->count() === 0) {
            throw ValidationException::withMessages([
                'status' => 'Paket tes tidak dapat diaktifkan karena belum memiliki soal.',
            ]);
        }

        foreach ($paket->soal as $s) {
            if ($s->status !== 'aktif') {
                throw ValidationException::withMessages([
                    'status' => "Paket tes tidak dapat diaktifkan karena soal '{$s->kode_soal}' berstatus {$s->status} (harus aktif).",
                ]);
            }

            if (!$s->isRubrikValid()) {
                throw ValidationException::withMessages([
                    'status' => "Paket tes tidak dapat diaktifkan karena rubrik soal '{$s->kode_soal}' belum sesuai dengan skor maksimumnya.",
                ]);
            }
        }

        $paket->update(['status' => 'aktif']);
    }

    /**
     * Sync attached soal with sequential ordering.
     */
    public function syncSoalWithOrder(PaketTes $paket, array $soalIds): void
    {
        $syncData = [];
        $order = 1;
        foreach ($soalIds as $id) {
            if (!empty($id)) {
                $syncData[$id] = ['urutan' => $order++];
            }
        }
        $paket->soal()->sync($syncData);
    }
}
