<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'penugasan_tes_id',
        'penilai_id',
        'total_skor_diperoleh',
        'total_skor_maksimum',
        'nilai_akhir',
        'catatan_umum',
        'waktu_penilaian',
    ];

    protected $casts = [
        'total_skor_diperoleh' => 'decimal:2',
        'total_skor_maksimum' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'waktu_penilaian' => 'datetime',
    ];

    public function penugasanTes()
    {
        return $this->belongsTo(PenugasanTes::class);
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }

    public function detail()
    {
        return $this->hasMany(PenilaianDetail::class);
    }

    public function riwayat()
    {
        return $this->hasMany(PenilaianRiwayat::class)->orderByDesc('created_at');
    }

    public function recalculateScores(): void
    {
        $totalObtained = (float) $this->detail()->sum('skor');
        $totalMax = (float) $this->detail()->sum('skor_maksimum');

        $this->total_skor_diperoleh = $totalObtained;
        $this->total_skor_maksimum = $totalMax;
        $this->nilai_akhir = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
        $this->save();
    }
}
