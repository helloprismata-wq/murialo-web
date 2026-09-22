<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanKandidat extends Model
{
    use HasFactory;

    protected $table = 'jawaban_kandidat';

    protected $fillable = [
        'percobaan_tes_id',
        'soal_id',
        'urutan',
        'jawaban',
        'terakhir_disimpan_pada',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'terakhir_disimpan_pada' => 'datetime',
    ];

    public function percobaanTes()
    {
        return $this->belongsTo(PercobaanTes::class);
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }

    public function penilaianDetail()
    {
        return $this->hasMany(PenilaianDetail::class);
    }
}
