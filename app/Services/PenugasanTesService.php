<?php

namespace App\Services;

use App\Models\Lamaran;
use App\Models\PaketTes;
use App\Models\PenugasanTes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenugasanTesService
{
    /**
     * Assign a test package to a candidate application.
     * Freezes a complete snapshot of questions, rubrics, and settings.
     * Prevents duplicate assignments atomically.
     */
    public function assignPaket(Lamaran $lamaran, PaketTes $paket, Carbon $waktuTersedia, Carbon $batasWaktu): PenugasanTes
    {
        if ($waktuTersedia->gte($batasWaktu)) {
            throw ValidationException::withMessages([
                'batas_waktu' => 'Batas waktu tes harus lebih besar dari waktu mulai tersedia.',
            ]);
        }

        if ($paket->status !== 'aktif') {
            throw ValidationException::withMessages([
                'paket_tes_id' => 'Hanya paket tes berstatus aktif yang dapat ditugaskan kepada kandidat.',
            ]);
        }

        return DB::transaction(function () use ($lamaran, $paket, $waktuTersedia, $batasWaktu) {
            // Lock and check for existing assignment to prevent race conditions & duplicate assignment
            $existing = PenugasanTes::where('lamaran_id', $lamaran->id)
                ->where('paket_tes_id', $paket->id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                throw ValidationException::withMessages([
                    'paket_tes_id' => 'Paket tes ini sudah pernah ditugaskan pada lamaran tersebut.',
                ]);
            }

            // Create complete frozen snapshot of paket and its questions
            $snapshot = $paket->toFullSnapshot();

            $penugasan = PenugasanTes::create([
                'lamaran_id' => $lamaran->id,
                'user_id' => $lamaran->user_id, // Candidate ID
                'paket_tes_id' => $paket->id,
                'paket_snapshot' => $snapshot,
                'waktu_tersedia' => $waktuTersedia,
                'batas_waktu' => $batasWaktu,
                'status_pengerjaan' => 'belum_dimulai',
                'status_penilaian' => 'belum_dinilai',
                'is_published' => false,
            ]);

            // Update lamaran status if appropriate
            $lamaran->update(['status' => 'proses_tes']);

            return $penugasan;
        });
    }
}
