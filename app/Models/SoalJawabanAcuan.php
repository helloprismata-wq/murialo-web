<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalJawabanAcuan extends Model
{
    use HasFactory;

    protected $table = 'soal_jawaban_acuan';

    protected $fillable = [
        'soal_id',
        'jawaban',
        'keterangan',
    ];

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}
