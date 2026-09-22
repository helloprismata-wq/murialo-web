<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianRiwayat extends Model
{
    use HasFactory;

    protected $table = 'penilaian_riwayat';

    public $timestamps = false;

    protected $fillable = [
        'penilaian_id',
        'user_id',
        'alasan_perubahan',
        'skor_sebelumnya',
        'skor_baru',
        'snapshot_perubahan',
        'created_at',
    ];

    protected $casts = [
        'skor_sebelumnya' => 'decimal:2',
        'skor_baru' => 'decimal:2',
        'snapshot_perubahan' => 'array',
        'created_at' => 'datetime',
    ];

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
