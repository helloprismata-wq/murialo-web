<?php

namespace App\Services;

use App\Models\JawabanKandidat;
use App\Models\PenugasanTes;
use App\Models\PercobaanTes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TesKandidatService
{
    /**
     * Start a test attempt for a candidate.
     * Ensures only 1 attempt per assignment and fixes the end time using server clock.
     */
    public function startTes(PenugasanTes $penugasan, User $kandidat, ?string $ipAddress = null, ?string $userAgent = null): PercobaanTes
    {
        if ($penugasan->user_id !== $kandidat->id) {
            abort(403, 'Anda tidak berhak mengakses tes ini.');
        }

        $now = now();

        if ($now->lt($penugasan->waktu_tersedia)) {
            throw ValidationException::withMessages([
                'tes' => 'Tes belum dibuka. Silakan tunggu jadwal mulai.',
            ]);
        }

        if ($now->gt($penugasan->batas_waktu)) {
            $penugasan->update(['status_pengerjaan' => 'kedaluwarsa']);
            throw ValidationException::withMessages([
                'tes' => 'Batas waktu penugasan tes telah berakhir.',
            ]);
        }

        return DB::transaction(function () use ($penugasan, $now, $ipAddress, $userAgent) {
            // Check if attempt already exists
            $percobaan = PercobaanTes::where('penugasan_tes_id', $penugasan->id)->lockForUpdate()->first();

            if ($percobaan) {
                // If attempt already finished
                if (in_array($percobaan->status, ['diserahkan', 'waktu_habis'])) {
                    throw ValidationException::withMessages([
                        'tes' => 'Tes ini sudah diselesaikan dan tidak dapat diakses kembali.',
                    ]);
                }

                // Check if time expired
                if ($percobaan->waktu_berakhir && $now->gte($percobaan->waktu_berakhir)) {
                    $this->finalizeAttempt($percobaan, 'waktu_habis');
                    throw ValidationException::withMessages([
                        'tes' => 'Waktu pengerjaan tes telah habis.',
                    ]);
                }

                return $percobaan;
            }

            // Calculate waktu_berakhir: min(now() + durasi_menit, penugasan->batas_waktu)
            $durasiMenit = $penugasan->paket_snapshot['durasi_menit'] ?? 60;
            $waktuDurasi = (clone $now)->addMinutes($durasiMenit);
            $waktuBerakhir = $waktuDurasi->lt($penugasan->batas_waktu) ? $waktuDurasi : $penugasan->batas_waktu;

            $percobaan = PercobaanTes::create([
                'penugasan_tes_id' => $penugasan->id,
                'waktu_mulai' => $now,
                'waktu_berakhir' => $waktuBerakhir,
                'status' => 'sedang_mengerjakan',
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);

            // Seed empty answer slots for all questions in snapshot
            $soalList = $penugasan->paket_snapshot['soal'] ?? [];
            foreach ($soalList as $s) {
                JawabanKandidat::create([
                    'percobaan_tes_id' => $percobaan->id,
                    'soal_id' => $s['id'],
                    'urutan' => $s['urutan'],
                    'jawaban' => null,
                ]);
            }

            $penugasan->update(['status_pengerjaan' => 'sedang_mengerjakan']);

            return $percobaan;
        });
    }

    /**
     * Autosave candidate's single answer.
     */
    public function autosaveAnswer(PercobaanTes $percobaan, int $soalId, ?string $jawaban): JawabanKandidat
    {
        return DB::transaction(function () use ($percobaan, $soalId, $jawaban) {
            // Lock attempt and verify active status
            $freshPercobaan = PercobaanTes::where('id', $percobaan->id)->lockForUpdate()->first();

            if ($freshPercobaan->status !== 'sedang_mengerjakan') {
                throw new \Exception('Tes sudah selesai atau waktu habis. Perubahan ditolak.');
            }

            if ($freshPercobaan->waktu_berakhir && now()->gte($freshPercobaan->waktu_berakhir)) {
                $this->finalizeAttempt($freshPercobaan, 'waktu_habis');
                throw new \Exception('Waktu pengerjaan tes telah habis.');
            }

            $jawabanRecord = JawabanKandidat::where('percobaan_tes_id', $freshPercobaan->id)
                ->where('soal_id', $soalId)
                ->first();

            if (!$jawabanRecord) {
                throw new \Exception('Soal tidak ditemukan pada tes ini.');
            }

            $jawabanRecord->update([
                'jawaban' => $jawaban,
                'terakhir_disimpan_pada' => now(),
            ]);

            return $jawabanRecord;
        });
    }

    /**
     * Submit candidate test answers manually.
     */
    public function submitTes(PercobaanTes $percobaan, array $jawabanData = []): void
    {
        DB::transaction(function () use ($percobaan, $jawabanData) {
            $freshPercobaan = PercobaanTes::where('id', $percobaan->id)->lockForUpdate()->first();

            if ($freshPercobaan->status !== 'sedang_mengerjakan') {
                throw ValidationException::withMessages([
                    'tes' => 'Tes ini sudah diselesaikan sebelumnya.',
                ]);
            }

            // Save any final responses passed with submit
            foreach ($jawabanData as $soalId => $jawaban) {
                JawabanKandidat::where('percobaan_tes_id', $freshPercobaan->id)
                    ->where('soal_id', $soalId)
                    ->update([
                        'jawaban' => $jawaban,
                        'terakhir_disimpan_pada' => now(),
                    ]);
            }

            $this->finalizeAttempt($freshPercobaan, 'diserahkan');
        });
    }

    /**
     * Finalize an attempt (either manual submit or time expired).
     */
    public function finalizeAttempt(PercobaanTes $percobaan, string $status = 'diserahkan'): void
    {
        $percobaan->update([
            'status' => $status,
            'waktu_selesai' => now(),
        ]);

        $penugasan = $percobaan->penugasanTes;
        if ($penugasan) {
            $penugasan->update([
                'status_pengerjaan' => 'selesai',
            ]);

            // Otomatis picu Smart Grading melalui AI Engine
            \App\Jobs\ProcessSmartGrading::dispatch($penugasan->id);
        }
    }

    /**
     * Auto-finalize all expired test attempts. Used by scheduler / artisan command.
     */
    public function finalizeAllExpired(): int
    {
        $now = now();
        $count = 0;

        $expiredAttempts = PercobaanTes::where('status', 'sedang_mengerjakan')
            ->whereNotNull('waktu_berakhir')
            ->where('waktu_berakhir', '<=', $now)
            ->get();

        foreach ($expiredAttempts as $percobaan) {
            $this->finalizeAttempt($percobaan, 'waktu_habis');
            $count++;
        }

        // Also mark assignments that passed deadline without ever starting as 'kedaluwarsa'
        PenugasanTes::where('status_pengerjaan', 'belum_dimulai')
            ->where('batas_waktu', '<=', $now)
            ->update(['status_pengerjaan' => 'kedaluwarsa']);

        return $count;
    }
}
