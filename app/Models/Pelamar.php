<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pelamar extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'nomor_hp',
        'posisi_dilamar',
        'file_cv',
    ];

    /**
     * Hasil ekstraksi informasi AI Engine untuk pelamar ini.
     */
    public function hasilParsing(): HasOne
    {
        return $this->hasOne(HasilParsing::class, 'pelamar_id');
    }
}
