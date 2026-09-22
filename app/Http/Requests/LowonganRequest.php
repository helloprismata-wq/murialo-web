<?php

namespace App\Http\Requests;

use App\Models\Lowongan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LowonganRequest extends FormRequest
{
    public function authorize(): bool
    {
        $lowongan = $this->route('lowongan');

        return $this->user() !== null && (! $lowongan || $lowongan->user_id === $this->user()->id);
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'perusahaan' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'tipe_pekerjaan' => ['required', Rule::in(array_keys(Lowongan::TIPE))],
            'deskripsi' => ['required', 'string', 'max:20000'],
            'persyaratan' => ['required', 'string', 'max:20000'],
            'skills' => ['nullable', 'string', 'max:5000'],
            'gaji_min' => ['nullable', 'integer', 'min:0', 'max:999999999999'],
            'gaji_max' => ['nullable', 'integer', 'min:0', 'max:999999999999', Rule::when($this->filled('gaji_min'), ['gte:gaji_min'])],
            'batas_lamaran' => ['nullable', 'date_format:Y-m-d'],
            'status' => ['required', Rule::in(array_keys(Lowongan::STATUS))],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute melebihi batas yang diizinkan (:max).',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'in' => 'Pilihan :attribute tidak valid.',
            'date_format' => ':attribute harus berupa tanggal yang valid.',
            'gte' => 'Gaji maksimum harus lebih besar atau sama dengan gaji minimum.',
        ];
    }

    public function attributes(): array
    {
        return ['judul' => 'Judul', 'perusahaan' => 'Perusahaan', 'lokasi' => 'Lokasi', 'tipe_pekerjaan' => 'Tipe pekerjaan', 'deskripsi' => 'Deskripsi', 'persyaratan' => 'Persyaratan', 'skills' => 'Keahlian', 'gaji_min' => 'Gaji minimum', 'gaji_max' => 'Gaji maksimum', 'batas_lamaran' => 'Batas lamaran', 'status' => 'Status'];
    }
}
