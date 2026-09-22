<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCvRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk update data pelamar.
     */
    public function rules(): array
    {
        return [
            'nama'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255'],
            'nomor_hp'       => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\s]+$/'],
            'posisi_dilamar' => ['required', 'string', 'max:255'],
            'file_cv'        => ['nullable', 'file', 'mimes:pdf,docx,doc', 'max:5120'], // opsional saat edit
        ];
    }

    /**
     * Pesan error validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'nama.required'           => 'Nama lengkap wajib diisi.',
            'nama.max'                => 'Nama maksimal 255 karakter.',
            'email.required'          => 'Alamat email wajib diisi.',
            'email.email'             => 'Format email tidak valid.',
            'nomor_hp.required'       => 'Nomor HP wajib diisi.',
            'nomor_hp.regex'          => 'Nomor HP hanya boleh berisi angka, +, -, dan spasi.',
            'nomor_hp.max'            => 'Nomor HP maksimal 20 karakter.',
            'posisi_dilamar.required' => 'Posisi yang dilamar wajib diisi.',
            'file_cv.mimes'           => 'File CV harus berformat PDF atau Word (.doc, .docx).',
            'file_cv.max'             => 'Ukuran file CV maksimal 5MB.',
        ];
    }

    /**
     * Nama label field untuk pesan error default.
     */
    public function attributes(): array
    {
        return [
            'nama'           => 'Nama Lengkap',
            'email'          => 'Email',
            'nomor_hp'       => 'Nomor HP',
            'posisi_dilamar' => 'Posisi yang Dilamar',
            'file_cv'        => 'File CV',
        ];
    }
}
