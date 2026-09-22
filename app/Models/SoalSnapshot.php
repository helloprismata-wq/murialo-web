<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalSnapshot extends Model
{
    use HasFactory;

    protected $table = 'soal_snapshots';

    protected $fillable = [
        'soal_id',
        'versi',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'versi' => 'integer',
    ];

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}
