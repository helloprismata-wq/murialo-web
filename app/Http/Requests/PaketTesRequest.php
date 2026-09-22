<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaketTesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isHr();
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'petunjuk' => ['nullable', 'string'],
            'durasi_menit' => ['required', 'integer', 'min:5', 'max:300'],
            'status' => ['required', 'string', Rule::in(['draft', 'aktif', 'arsip'])],
            'soal_ids' => ['required', 'array', 'min:1'],
            'soal_ids.*' => ['required', 'exists:soal,id'],
            'lowongan_ids' => ['nullable', 'array'],
            'lowongan_ids.*' => ['exists:lowongan,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama paket tes wajib diisi.',
            'durasi_menit.required' => 'Durasi pengerjaan wajib diisi.',
            'durasi_menit.min' => 'Durasi minimal pengerjaan adalah 5 menit.',
            'soal_ids.required' => 'Pilih minimal satu soal untuk dimasukkan ke dalam paket tes.',
        ];
    }
}
