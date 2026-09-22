<?php

namespace Database\Seeders;

use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun HRD utama untuk testing — gunakan email & password ini di browser
        $hrd = User::firstOrCreate(
            ['email' => 'hrd@murialo.test'],
            [
                'name' => 'HRD Murialo',
                'password' => 'password',
                'role' => 'hr',
            ]
        );

        if (Lowongan::where('user_id', $hrd->id)->count() === 0) {
            // 15 lowongan milik HRD utama dengan variasi status
            Lowongan::factory()->count(6)->aktif()->for($hrd)->create();
            Lowongan::factory()->count(5)->draft()->for($hrd)->create();
            Lowongan::factory()->count(4)->ditutup()->for($hrd)->create();
        }

        // Akun kedua sebagai user lain (untuk tes ownership)
        $user2 = User::firstOrCreate(
            ['email' => 'rekruter@murialo.test'],
            [
                'name' => 'Rekruter Demo',
                'password' => 'password',
                'role' => 'hr',
            ]
        );

        if (Lowongan::where('user_id', $user2->id)->count() === 0) {
            Lowongan::factory()->count(3)->aktif()->for($user2)->create();
        }

        // Seed data demo untuk Smart Grading (Bank Soal, Paket Tes, Penugasan, Penilaian)
        $this->call(SmartGradingDemoSeeder::class);
    }
}
