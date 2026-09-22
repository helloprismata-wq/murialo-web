<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenugasanTesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isHr();
    }

    public function rules(): array
    {
        return [
            'lamaran_id' => ['required', 'exists:lamaran,id'],
            'paket_tes_id' => ['required', 'exists:paket_tes,id'],
            'waktu_tersedia' => ['required', 'date'],
            'batas_waktu' => ['required', 'date', 'after:waktu_tersedia'],
        ];
    }

    public function messages(): array
    {
        return [
            'lamaran_id.required' => 'Pilih lamaran kandidat yang akan ditugaskan.',
            'paket_tes_id.required' => 'Pilih paket tes yang akan ditugaskan.',
            'waktu_tersedia.required' => 'Tentukan waktu tes mulai tersedia.',
            'batas_waktu.required' => 'Tentukan batas akhir pengerjaan tes.',
            'batas_waktu.after' => 'Batas waktu harus lebih lama dari waktu tes mulai tersedia.',
        ];
    }
}
