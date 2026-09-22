<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanTes extends Model
{
    use HasFactory;

    protected $table = 'penugasan_tes';

    protected $fillable = [
        'lamaran_id',
        'user_id',
        'paket_tes_id',
        'paket_snapshot',
        'waktu_tersedia',
        'batas_waktu',
        'status_pengerjaan',
        'status_penilaian',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'paket_snapshot' => 'array',
        'waktu_tersedia' => 'datetime',
        'batas_waktu' => 'datetime',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function lamaran()
    {
        return $this->belongsTo(Lamaran::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kandidat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paketTes()
    {
        return $this->belongsTo(PaketTes::class);
    }

    public function percobaan()
    {
        return $this->hasOne(PercobaanTes::class);
    }

    public function penilaian()
    {
        return $this->hasOne(Penilaian::class);
    }

    public function isAvailable(): bool
    {
        $now = now();
        return $now->gte($this->waktu_tersedia) && $now->lte($this->batas_waktu);
    }

    public function isExpired(): bool
    {
        return now()->gt($this->batas_waktu);
    }

    /**
     * Return question data safely stripped of rubrics and sample answers for candidate UI.
     */
    public function getCandidateQuestionsAttribute(): array
    {
        $snapshot = $this->paket_snapshot ?? [];
        $soalList = $snapshot['soal'] ?? [];

        return array_map(function ($s) {
            return [
                'id' => $s['id'],
                'urutan' => $s['urutan'],
                'kode_soal' => $s['kode_soal'],
                'judul' => $s['judul'],
                'kategori' => $s['kategori'],
                'teks_bacaan' => $s['teks_bacaan'],
                'pertanyaan' => $s['pertanyaan'],
                'skor_maksimum' => $s['skor_maksimum'],
            ];
        }, $soalList);
    }
}
