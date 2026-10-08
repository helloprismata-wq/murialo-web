@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi · MURIALO')

@section('content')
<div class="space-y-6">
    <div class="text-center space-y-2">
        <h1 class="text-2xl font-bold tracking-tight text-[#0F172A]">Lupa Kata Sandi?</h1>
        <p class="text-xs text-[#64748B]">Masukkan alamat email terdaftar untuk menerima tautan pemulihan</p>
    </div>

    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 sm:p-8 shadow-2xs space-y-5" x-data="{ sent: false }">
        <div x-show="!sent" class="space-y-4">
            <x-input
                label="Alamat Email Terdaftar"
                name="email"
                type="email"
                placeholder="nama@email.com"
                value="budi.santoso@email.com"
                required
            />

            <x-button type="button" @click="sent = true" variant="primary" size="lg" class="w-full">
                Kirim Tautan Pemulihan
            </x-button>
        </div>

        <div x-show="sent" style="display: none;" class="space-y-4 text-center py-4">
            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-xl font-bold">
                ✓
            </div>
            <h3 class="text-base font-bold text-[#0F172A]">Tautan Terkirim!</h3>
            <p class="text-xs text-[#64748B] leading-relaxed">
                Kami telah mengirimkan simulasi tautan reset password ke email Anda. Silakan lanjutkan ke halaman pembuatan kata sandi baru.
            </p>
            <x-button href="{{ route('demo.reset_password') }}" variant="primary" size="md" class="w-full">
                Lanjut ke Reset Password
            </x-button>
        </div>

        <div class="pt-4 border-t border-[#E2E8F0] text-center text-xs text-[#64748B]">
            Ingat kata sandi Anda?
            <a href="{{ route('demo.login') }}" class="font-semibold text-[#2563EB] hover:underline ml-1">
                Kembali ke login
            </a>
        </div>
    </div>
</div>
@endsection
