<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianDetail extends Model
{
    use HasFactory;

    protected $table = 'penilaian_detail';

    protected $fillable = [
        'penilaian_id',
        'jawaban_kandidat_id',
        'soal_rubrik_id',
        'kriteria',
        'skor_maksimum',
        'skor',
        'catatan',
    ];

    protected $casts = [
        'skor_maksimum' => 'decimal:2',
        'skor' => 'decimal:2',
    ];

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class);
    }

    public function jawabanKandidat()
    {
        return $this->belongsTo(JawabanKandidat::class);
    }

    public function rubrik()
    {
        return $this->belongsTo(SoalRubrik::class, 'soal_rubrik_id');
    }
}
