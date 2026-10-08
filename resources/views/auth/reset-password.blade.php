@extends('layouts.auth')

@section('title', 'Atur Ulang Kata Sandi · MURIALO')

@section('content')
<div class="space-y-6">
    <div class="text-center space-y-2">
        <h1 class="text-2xl font-bold tracking-tight text-[#0F172A]">Atur Ulang Kata Sandi</h1>
        <p class="text-xs text-[#64748B]">Buat kata sandi baru yang aman untuk akun Anda</p>
    </div>

    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 sm:p-8 shadow-2xs space-y-5" x-data="{ success: false }">
        <div x-show="!success" class="space-y-4">
            <x-input
                label="Kata Sandi Baru"
                name="password"
                type="password"
                placeholder="Minimal 8 karakter"
                required
            />

            <x-input
                label="Konfirmasi Kata Sandi Baru"
                name="password_confirmation"
                type="password"
                placeholder="Ulangi kata sandi baru"
                required
            />

            <x-button type="button" @click="success = true" variant="primary" size="lg" class="w-full">
                Simpan Kata Sandi Baru
            </x-button>
        </div>

        <div x-show="success" style="display: none;" class="space-y-4 text-center py-4">
            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-xl font-bold">
                ✓
            </div>
            <h3 class="text-base font-bold text-[#0F172A]">Kata Sandi Diperbarui</h3>
            <p class="text-xs text-[#64748B] leading-relaxed">
                Kata sandi baru Anda telah berhasil disimpan dalam simulasi. Anda dapat masuk kembali ke akun.
            </p>
            <x-button href="{{ route('demo.login') }}" variant="primary" size="md" class="w-full">
                Masuk Sekarang
            </x-button>
        </div>
    </div>
</div>
@endsection
