<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaketTes extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paket_tes';

    protected $fillable = [
        'user_id',
        'nama',
        'deskripsi',
        'petunjuk',
        'durasi_menit',
        'total_skor_maksimum',
        'status',
    ];

    protected $casts = [
        'durasi_menit' => 'integer',
        'total_skor_maksimum' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soal()
    {
        return $this->belongsToMany(Soal::class, 'paket_tes_soal')
            ->withPivot('urutan')
            ->orderBy('paket_tes_soal.urutan');
    }

    public function lowongan()
    {
        return $this->belongsToMany(Lowongan::class, 'lowongan_paket_tes');
    }

    public function penugasanTes()
    {
        return $this->hasMany(PenugasanTes::class);
    }

    public function recalculateTotalSkor(): void
    {
        $this->total_skor_maksimum = (int) $this->soal()->sum('skor_maksimum');
        $this->save();
    }

    public function canBeActivated(): bool
    {
        if ($this->soal()->count() === 0) {
            return false;
        }

        foreach ($this->soal as $s) {
            if ($s->status !== 'aktif' || !$s->isRubrikValid()) {
                return false;
            }
        }

        return true;
    }

    public function toFullSnapshot(): array
    {
        $soalList = $this->soal()->with(['jawabanAcuan', 'rubrik'])->get();

        return [
            'paket_id' => $this->id,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'petunjuk' => $this->petunjuk,
            'durasi_menit' => $this->durasi_menit,
            'total_skor_maksimum' => $this->total_skor_maksimum,
            'soal' => $soalList->map(function ($s) {
                return [
                    'id' => $s->id,
                    'urutan' => $s->pivot->urutan,
                    'kode_soal' => $s->kode_soal,
                    'judul' => $s->judul,
                    'kategori' => $s->kategori,
                    'teks_bacaan' => $s->teks_bacaan,
                    'pertanyaan' => $s->pertanyaan,
                    'skor_maksimum' => $s->skor_maksimum,
                    'sumber' => $s->sumber,
                    'versi' => $s->versi,
                    'is_dummy' => $s->is_dummy,
                    'jawaban_acuan' => $s->jawabanAcuan->map(fn($ja) => [
                        'id' => $ja->id,
                        'jawaban' => $ja->jawaban,
                        'keterangan' => $ja->keterangan,
                    ])->values()->toArray(),
                    'rubrik' => $s->rubrik->map(fn($r) => [
                        'id' => $r->id,
                        'kriteria' => $r->kriteria,
                        'poin_maksimum' => $r->poin_maksimum,
                        'deskripsi' => $r->deskripsi,
                        'urutan' => $r->urutan,
                    ])->values()->toArray(),
                ];
            })->values()->toArray(),
        ];
    }
}
