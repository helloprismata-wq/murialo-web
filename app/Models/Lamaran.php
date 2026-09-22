<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lamaran extends Model
{
    use HasFactory;

    protected $table = 'lamaran';

    protected $fillable = [
        'lowongan_id',
        'user_id',
        'status',
        'catatan',
    ];

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class);
    }

    public function kandidat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penugasanTes()
    {
        return $this->hasMany(PenugasanTes::class);
    }
}
