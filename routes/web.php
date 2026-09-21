<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/dashboard-hrd', function () {
    return Inertia::render('DashboardHrd', [
        'stats' => [
            'lowonganAktif' => 4,
            'totalPelamar' => 27,
            'pelamarMingguIni' => 6,
            'anomaliTerdeteksi' => 0,
        ],
        'lowongan' => [
            ['nama' => 'Backend developer', 'pelamar' => 12, 'keterangan' => '3 kandidat siap direview'],
            ['nama' => 'Data analyst', 'pelamar' => 9, 'keterangan' => 'AI masih memproses skor'],
            ['nama' => 'UI/UX designer', 'pelamar' => 6, 'keterangan' => 'Baru dibuka 2 hari lalu'],
        ],
        'aiInsight' => [
            'kandidat' => 'Kandidat C',
            'posisi' => 'Backend developer',
            'skor' => 0.77,
            'keterangan' => 'unggul di Python, FastAPI, dan Docker',
        ],
    ]);
});
