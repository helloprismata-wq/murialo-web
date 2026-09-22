<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PercobaanTes extends Model
{
    use HasFactory;

    protected $table = 'percobaan_tes';

    protected $fillable = [
        'penugasan_tes_id',
        'waktu_mulai',
        'waktu_berakhir',
        'waktu_selesai',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_berakhir' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function penugasanTes()
    {
        return $this->belongsTo(PenugasanTes::class);
    }

    public function jawaban()
    {
        return $this->hasMany(JawabanKandidat::class)->orderBy('urutan');
    }

    public function isTimeUp(): bool
    {
        if (!$this->waktu_berakhir) {
            return false;
        }
        return now()->gte($this->waktu_berakhir);
    }

    public function sisaDetik(): int
    {
        if (!$this->waktu_berakhir) {
            return 0;
        }
        $remaining = now()->diffInSeconds($this->waktu_berakhir, false);
        return max(0, $remaining);
    }
}
