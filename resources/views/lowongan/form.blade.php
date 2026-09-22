@extends('lowongan.layout')
@section('title', $lowongan->exists ? 'Edit lowongan' : 'Tambah lowongan')
@section('content')
<a class="back-link" href="{{ route('lowongan.index') }}">← Semua lowongan</a>

<div class="heading">
    <div>
        <p class="eyebrow">KELOLA PELUANG</p>
        <h1>{{ $lowongan->exists ? '✏ Edit lowongan' : '+ Tambah lowongan' }}</h1>
        <p class="muted">Lengkapi informasi agar kandidat memahami posisi yang ditawarkan.</p>
    </div>
</div>

<form class="panel" action="{{ $lowongan->exists ? route('lowongan.update', $lowongan) : route('lowongan.store') }}" method="post">
    @csrf
    @if ($lowongan->exists) @method('PUT') @endif

    <p class="muted" style="margin-bottom: 20px;">Kolom bertanda <strong style="color: var(--accent);">*</strong> wajib diisi. Gaji dalam rupiah per bulan.</p>

    <div class="grid">
        @foreach (['judul' => 'Judul posisi', 'perusahaan' => 'Perusahaan', 'lokasi' => 'Lokasi'] as $field => $label)
        <div>
            <label for="{{ $field }}">{{ $label }} <span style="color: var(--accent);">*</span></label>
            <input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $lowongan->$field) }}" required maxlength="255" @error($field) aria-invalid="true" @enderror>
        </div>
        @endforeach

        <div>
            <label for="tipe_pekerjaan">Tipe pekerjaan <span style="color: var(--accent);">*</span></label>
            <select id="tipe_pekerjaan" name="tipe_pekerjaan" required>
                @foreach (\App\Models\Lowongan::TIPE as $value => $label)
                    <option value="{{ $value }}" @selected(old('tipe_pekerjaan', $lowongan->tipe_pekerjaan) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        @foreach (['gaji_min' => 'Gaji minimum (Rp)', 'gaji_max' => 'Gaji maksimum (Rp)'] as $field => $label)
        <div>
            <label for="{{ $field }}">{{ $label }}</label>
            <input id="{{ $field }}" type="number" name="{{ $field }}" min="0" max="999999999999" step="1" value="{{ old($field, $lowongan->$field) }}" placeholder="Opsional">
        </div>
        @endforeach

        <div>
            <label for="batas_lamaran">Batas lamaran</label>
            <input id="batas_lamaran" type="date" name="batas_lamaran" value="{{ old('batas_lamaran', $lowongan->batas_lamaran?->format('Y-m-d')) }}">
        </div>

        <div>
            <label for="status">Status <span style="color: var(--accent);">*</span></label>
            <select id="status" name="status" required>
                @foreach (\App\Models\Lowongan::STATUS as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $lowongan->status) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @foreach (['deskripsi' => 'Deskripsi pekerjaan', 'persyaratan' => 'Persyaratan'] as $field => $label)
    <div class="field">
        <label for="{{ $field }}">{{ $label }} <span style="color: var(--accent);">*</span></label>
        <textarea id="{{ $field }}" name="{{ $field }}" rows="6" required maxlength="20000">{{ old($field, $lowongan->$field) }}</textarea>
    </div>
    @endforeach

    <div class="field">
        <label for="skills">Keahlian yang dibutuhkan</label>
        <textarea id="skills" name="skills" rows="3" maxlength="5000" placeholder="Contoh: PHP, Laravel, MySQL">{{ old('skills', $lowongan->skills) }}</textarea>
        <p class="form-hint">Pisahkan keahlian dengan koma untuk memudahkan integrasi skill matching AI.</p>
    </div>

    <div class="actions">
        <button class="button" type="submit">{{ $lowongan->exists ? '💾 Simpan perubahan' : '🚀 Buat lowongan' }}</button>
        <a href="{{ $lowongan->exists ? route('lowongan.show', $lowongan) : route('lowongan.index') }}">Batal</a>
    </div>
</form>
@endsection
