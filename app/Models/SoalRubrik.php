<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalRubrik extends Model
{
    use HasFactory;

    protected $table = 'soal_rubrik';

    protected $fillable = [
        'soal_id',
        'kriteria',
        'poin_maksimum',
        'deskripsi',
        'urutan',
    ];

    protected $casts = [
        'poin_maksimum' => 'integer',
        'urutan' => 'integer',
    ];

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}
