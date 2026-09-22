<?php

namespace App\Http\Requests;

use App\Models\Soal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SoalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isHr();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $soalId = $this->route('soal') ? $this->route('soal')->id : null;

        return [
            'kode_soal' => [
                'required',
                'string',
                'max:50',
                Rule::unique('soal', 'kode_soal')->ignore($soalId),
            ],
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', Rule::in(['pemahaman_informasi', 'instruksi', 'penalaran'])],
            'teks_bacaan' => ['nullable', 'string'],
            'pertanyaan' => ['required', 'string'],
            'skor_maksimum' => ['required', 'integer', 'min:1', 'max:100'],
            'sumber' => ['nullable', 'string', 'max:255'],
            'is_dummy' => ['nullable', 'boolean'],
            'status' => ['required', 'string', Rule::in(['draft', 'aktif', 'arsip'])],
            'jawaban_acuan' => ['required', 'array', 'min:1'],
            'jawaban_acuan.*.jawaban' => ['required', 'string'],
            'jawaban_acuan.*.keterangan' => ['nullable', 'string'],
            'rubrik' => ['required', 'array', 'min:1'],
            'rubrik.*.kriteria' => ['required', 'string'],
            'rubrik.*.poin_maksimum' => ['required', 'integer', 'min:1'],
            'rubrik.*.deskripsi' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_soal.required' => 'Kode soal wajib diisi.',
            'kode_soal.unique' => 'Kode soal sudah digunakan, gunakan kode unik lain.',
            'judul.required' => 'Judul soal wajib diisi.',
            'kategori.required' => 'Kategori kemampuan wajib dipilih.',
            'pertanyaan.required' => 'Pertanyaan soal wajib diisi.',
            'skor_maksimum.required' => 'Skor maksimum wajib diisi.',
            'jawaban_acuan.required' => 'Minimal sertakan satu contoh jawaban acuan.',
            'jawaban_acuan.*.jawaban.required' => 'Teks jawaban acuan tidak boleh kosong.',
            'rubrik.required' => 'Rubrik penilaian wajib memiliki minimal satu kriteria.',
            'rubrik.*.kriteria.required' => 'Nama kriteria rubrik wajib diisi.',
            'rubrik.*.poin_maksimum.required' => 'Poin maksimum kriteria rubrik wajib diisi.',
        ];
    }
}
