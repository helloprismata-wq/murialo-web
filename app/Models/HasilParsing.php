<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilParsing extends Model
{
    protected $table = 'hasil_parsings';
    public $timestamps = false; // kolom diparse_pada dikelola manual/MySQL
    protected $guarded = [];

    // Accessor untuk daftar skill sebagai array
    public function getSkillListAttribute(): array
    {
        return $this->skill_terdeteksi 
            ? array_map('trim', explode(',', $this->skill_terdeteksi)) 
            : [];
    }

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id');
    }
}
