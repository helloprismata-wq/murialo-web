<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'soal';

    protected $fillable = [
        'user_id',
        'kode_soal',
        'judul',
        'kategori',
        'teks_bacaan',
        'pertanyaan',
        'skor_maksimum',
        'sumber',
        'is_dummy',
        'status',
        'versi',
    ];

    protected $casts = [
        'is_dummy' => 'boolean',
        'skor_maksimum' => 'integer',
        'versi' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jawabanAcuan()
    {
        return $this->hasMany(SoalJawabanAcuan::class);
    }

    public function rubrik()
    {
        return $this->hasMany(SoalRubrik::class)->orderBy('urutan');
    }

    public function snapshots()
    {
        return $this->hasMany(SoalSnapshot::class)->orderByDesc('versi');
    }

    public function paketTes()
    {
        return $this->belongsToMany(PaketTes::class, 'paket_tes_soal')->withPivot('urutan');
    }

    public function totalPoinRubrik(): int
    {
        return (int) $this->rubrik()->sum('poin_maksimum');
    }

    public function isRubrikValid(): bool
    {
        return $this->totalPoinRubrik() === (int) $this->skor_maksimum;
    }

    public function toSnapshotArray(): array
    {
        return [
            'id' => $this->id,
            'kode_soal' => $this->kode_soal,
            'judul' => $this->judul,
            'kategori' => $this->kategori,
            'teks_bacaan' => $this->teks_bacaan,
            'pertanyaan' => $this->pertanyaan,
            'skor_maksimum' => $this->skor_maksimum,
            'sumber' => $this->sumber,
            'is_dummy' => $this->is_dummy,
            'versi' => $this->versi,
            'jawaban_acuan' => $this->jawabanAcuan->map(fn($ja) => [
                'id' => $ja->id,
                'jawaban' => $ja->jawaban,
                'keterangan' => $ja->keterangan,
            ])->toArray(),
            'rubrik' => $this->rubrik->map(fn($r) => [
                'id' => $r->id,
                'kriteria' => $r->kriteria,
                'poin_maksimum' => $r->poin_maksimum,
                'deskripsi' => $r->deskripsi,
                'urutan' => $r->urutan,
            ])->toArray(),
        ];
    }
}
