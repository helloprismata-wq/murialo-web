<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lowongan extends Model
{
    use HasFactory;

    protected $table = 'lowongan';

    public const TIPE = ['full_time' => 'Full time', 'part_time' => 'Part time', 'kontrak' => 'Kontrak', 'magang' => 'Magang'];

    public const STATUS = ['draft' => 'Draft', 'aktif' => 'Aktif', 'ditutup' => 'Ditutup'];

    protected $fillable = ['judul', 'perusahaan', 'lokasi', 'tipe_pekerjaan', 'deskripsi', 'persyaratan', 'skills', 'gaji_min', 'gaji_max', 'batas_lamaran', 'status'];

    protected function casts(): array
    {
        return ['batas_lamaran' => 'date', 'gaji_min' => 'integer', 'gaji_max' => 'integer'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lamaran()
    {
        return $this->hasMany(Lamaran::class);
    }

    public function paketTes()
    {
        return $this->belongsToMany(PaketTes::class, 'lowongan_paket_tes');
    }
}
