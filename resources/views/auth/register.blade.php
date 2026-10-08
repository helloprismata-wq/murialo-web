@extends('layouts.auth')

@section('title', 'Daftar Akun Kandidat · MURIALO')

@section('content')
<div class="space-y-6">
    <div class="text-center space-y-2">
        <h1 class="text-2xl font-bold tracking-tight text-[#0F172A]">Daftar Akun Baru</h1>
        <p class="text-xs text-[#64748B]">Mulai perjalanan karier Anda bersama platform seleksi MURIALO</p>
    </div>

    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 sm:p-8 shadow-2xs space-y-5">
        <form action="{{ route('kandidat.dashboard') }}" method="GET" class="space-y-4">
            <x-input
                label="Nama Lengkap"
                name="name"
                placeholder="Contoh: Budi Santoso"
                required
            />

            <x-input
                label="Alamat Email"
                name="email"
                type="email"
                placeholder="budi.santoso@email.com"
                required
            />

            <x-input
                label="Nomor WhatsApp / Telepon"
                name="phone"
                placeholder="+62 812-xxxx-xxxx"
                required
            />

            <x-input
                label="Kata Sandi"
                name="password"
                type="password"
                placeholder="Minimal 8 karakter"
                required
            />

            <x-checkbox
                name="terms"
                label="Saya menyetujui Ketentuan Layanan dan Kebijakan Privasi data rekrutmen."
                checked
                required
            />

            <x-button type="submit" variant="primary" size="lg" class="w-full shadow-sm">
                Daftar Akun Kandidat
            </x-button>
        </form>

        <div class="pt-4 border-t border-[#E2E8F0] text-center text-xs text-[#64748B]">
            Sudah memiliki akun terdaftar?
            <a href="{{ route('demo.login') }}" class="font-semibold text-[#2563EB] hover:underline ml-1">
                Masuk di sini
            </a>
        </div>
    </div>
</div>
@endsection
