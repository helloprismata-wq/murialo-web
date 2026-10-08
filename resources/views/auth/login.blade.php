@extends('layouts.auth')

@section('title', 'Masuk Akun · MURIALO')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="text-center space-y-2">
        <h1 class="text-2xl font-bold tracking-tight text-[#0F172A]">Selamat Datang Kembali</h1>
        <p class="text-xs text-[#64748B]">Masuk ke platform rekrutmen enterprise MURIALO</p>
    </div>

    {{-- 1-Click Role Switcher Demo Cards --}}
    <div class="bg-blue-50/70 border border-blue-200 rounded-2xl p-4 space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-[#1E40AF] uppercase tracking-wider">Akses Cepat Demo:</span>
            <span class="text-[10px] text-blue-600 font-semibold">1-Klik Langsung Masuk</span>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <a href="{{ route('hr.dashboard') }}" class="p-2 rounded-xl bg-white border border-blue-200 hover:border-blue-500 hover:shadow-xs transition-smooth text-center block group">
                <span class="w-2 h-2 rounded-full bg-blue-500 mx-auto block mb-1"></span>
                <span class="text-xs font-bold text-[#0F172A] block group-hover:text-[#2563EB]">HR Lead</span>
                <span class="text-[10px] text-[#64748B] block truncate">Recruiter</span>
            </a>
            <a href="{{ route('kandidat.dashboard') }}" class="p-2 rounded-xl bg-white border border-blue-200 hover:border-blue-500 hover:shadow-xs transition-smooth text-center block group">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mx-auto block mb-1"></span>
                <span class="text-xs font-bold text-[#0F172A] block group-hover:text-[#2563EB]">Kandidat</span>
                <span class="text-[10px] text-[#64748B] block truncate">Budi Santoso</span>
            </a>
            <a href="{{ route('admin.users') }}" class="p-2 rounded-xl bg-white border border-blue-200 hover:border-blue-500 hover:shadow-xs transition-smooth text-center block group">
                <span class="w-2 h-2 rounded-full bg-purple-500 mx-auto block mb-1"></span>
                <span class="text-xs font-bold text-[#0F172A] block group-hover:text-[#2563EB]">Admin</span>
                <span class="text-[10px] text-[#64748B] block truncate">Superadmin</span>
            </a>
        </div>
    </div>

    {{-- Login Form Box --}}
    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 sm:p-8 shadow-2xs space-y-5">
        <form action="{{ route('hr.dashboard') }}" method="GET" class="space-y-4">
            <x-input
                label="Alamat Email"
                name="email"
                type="email"
                value="hrd@murialo.test"
                placeholder="nama@perusahaan.com"
                required
            />

            <div>
                <x-input
                    label="Kata Sandi"
                    name="password"
                    type="password"
                    value="password"
                    placeholder="••••••••"
                    required
                />
                <div class="flex items-center justify-between mt-2">
                    <x-checkbox label="Ingat saya" name="remember" checked />
                    <a href="{{ route('demo.forgot_password') }}" class="text-xs font-medium text-[#2563EB] hover:underline">
                        Lupa kata sandi?
                    </a>
                </div>
            </div>

            <x-button type="submit" variant="primary" size="lg" class="w-full shadow-sm">
                Masuk ke Akun
            </x-button>
        </form>

        <div class="pt-4 border-t border-[#E2E8F0] text-center text-xs text-[#64748B]">
            Belum memiliki akun kandidat?
            <a href="{{ route('demo.register') }}" class="font-semibold text-[#2563EB] hover:underline ml-1">
                Daftar sekarang
            </a>
        </div>
    </div>
</div>
@endsection
