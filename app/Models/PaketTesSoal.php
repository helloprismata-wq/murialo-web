<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PaketTesSoal extends Pivot
{
    protected $table = 'paket_tes_soal';

    protected $fillable = [
        'paket_tes_id',
        'soal_id',
        'urutan',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];
}
