<?php

use App\Http\Controllers\LowonganController;
use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Replace auth.basic with the team's login/HRD middleware when auth is merged.
Route::resource('lowongan', LowonganController::class)
    ->parameters(['lowongan' => 'lowongan'])
    ->middleware('auth.basic');

// Group HR: Bank Soal, Paket Tes, Penugasan Tes, Penilaian Manual
Route::middleware(['auth.basic', 'role:hr'])->group(function () {
    // Bank Soal
    Route::resource('soal', \App\Http\Controllers\SoalController::class);
    Route::patch('soal/{soal}/archive', [\App\Http\Controllers\SoalController::class, 'archive'])->name('soal.archive');

    // Paket Tes
    Route::resource('paket', \App\Http\Controllers\PaketTesController::class);

    // Penugasan Tes
    Route::resource('penugasan', \App\Http\Controllers\PenugasanTesController::class)
        ->only(['index', 'create', 'store', 'show']);

    // Penilaian Manual HR & Publikasi Hasil
    Route::get('penilaian', [\App\Http\Controllers\PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('penilaian/{penugasan}', [\App\Http\Controllers\PenilaianController::class, 'show'])->name('penilaian.show');
    Route::get('penilaian/{penugasan}/edit', [\App\Http\Controllers\PenilaianController::class, 'edit'])->name('penilaian.edit');
    Route::put('penilaian/{penugasan}', [\App\Http\Controllers\PenilaianController::class, 'update'])->name('penilaian.update');
    Route::post('penilaian/{penugasan}/publish', [\App\Http\Controllers\PenilaianController::class, 'togglePublish'])->name('penilaian.publish');
    Route::post('penilaian/{penugasan}/retry-ai', [\App\Http\Controllers\PenilaianController::class, 'retrySmartGrading'])->name('penilaian.retry_ai');

    // Automated Skill Matching (S-BERT)
    Route::get('lowongan/{lowongan}/matching', [\App\Http\Controllers\SkillMatchingController::class, 'index'])->name('lowongan.matching');
    Route::post('lowongan/{lowongan}/matching', [\App\Http\Controllers\SkillMatchingController::class, 'match'])->name('lowongan.matching.run');
    Route::post('api/skill-matching/test', [\App\Http\Controllers\SkillMatchingController::class, 'apiMatch'])->name('api.skill_matching.test');
});

// Group Kandidat: Tes Saya
Route::middleware(['auth.basic', 'role:kandidat'])->group(function () {
    Route::get('tes-saya', [\App\Http\Controllers\TesKandidatController::class, 'index'])->name('kandidat.index');
    Route::get('tes-saya/{penugasan}', [\App\Http\Controllers\TesKandidatController::class, 'show'])->name('kandidat.show');
    Route::post('tes-saya/{penugasan}/mulai', [\App\Http\Controllers\TesKandidatController::class, 'start'])->name('kandidat.start');
    Route::get('tes-saya/{penugasan}/kerjakan', [\App\Http\Controllers\TesKandidatController::class, 'kerjakan'])->name('kandidat.kerjakan');
    Route::post('tes-saya/{penugasan}/autosave', [\App\Http\Controllers\TesKandidatController::class, 'autosave'])->name('kandidat.autosave');
    Route::post('tes-saya/{penugasan}/submit', [\App\Http\Controllers\TesKandidatController::class, 'submit'])->name('kandidat.submit');
});

// ─── Halaman Publik: Pelamar Upload CV ───────────────────────────────────────
Route::get('/upload-cv', [CvController::class, 'create'])->name('cv.create');
Route::post('/upload-cv', [CvController::class, 'store'])->name('cv.store');

// ─── Halaman HRD: Manajemen CV Masuk ─────────────────────────────────────────
// TODO: Tambahkan middleware auth setelah fitur/auth selesai
Route::prefix('hrd')->name('cv.')->group(function () {
    Route::get('/cv',                      [CvController::class, 'index'])->name('index');
    Route::get('/cv/{pelamar}/edit',       [CvController::class, 'edit'])->name('edit');
    Route::put('/cv/{pelamar}',            [CvController::class, 'update'])->name('update');
    Route::delete('/cv/{pelamar}',         [CvController::class, 'destroy'])->name('destroy');
    Route::get('/cv/{pelamar}/download',   [CvController::class, 'download'])->name('download');
    Route::post('/cv/{pelamar}/reparse',   [CvController::class, 'reparse'])->name('reparse');
    Route::get('/cv/{pelamar}/ai-detail',  [CvController::class, 'aiDetail'])->name('ai_detail');
});
