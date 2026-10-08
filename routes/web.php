<?php

use App\Http\Controllers\CvController;
use App\Http\Controllers\DemoUiController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\PaketTesController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PenugasanTesController;
use App\Http\Controllers\SkillMatchingController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\TesKandidatController;
use Illuminate\Support\Facades\Route;

// =============================================================================
// 1. HALAMAN PUBLIK & LANDING PAGE (UI FOUNDATION)
// =============================================================================

Route::get('/', [DemoUiController::class, 'landing'])->name('landing');

Route::prefix('karir')->name('karir.')->group(function () {
    Route::get('/', [DemoUiController::class, 'karirIndex'])->name('index');
    Route::get('/{id}', [DemoUiController::class, 'karirShow'])->name('show');
});

// =============================================================================
// 2. HALAMAN AUTENTIKASI (SIMULASI DEMO DENGAN ROLE SWITCHER)
// =============================================================================

Route::prefix('demo')->name('demo.')->group(function () {
    Route::get('/login', [DemoUiController::class, 'login'])->name('login');
    Route::get('/register', [DemoUiController::class, 'register'])->name('register');
    Route::get('/forgot-password', [DemoUiController::class, 'forgotPassword'])->name('forgot_password');
    Route::get('/reset-password', [DemoUiController::class, 'resetPassword'])->name('reset_password');
});

// Standard auth routes for natural navigation
Route::get('/login', [DemoUiController::class, 'login'])->name('login');
Route::get('/register', [DemoUiController::class, 'register'])->name('register');
Route::get('/forgot-password', [DemoUiController::class, 'forgotPassword'])->name('password.request');
Route::get('/reset-password', [DemoUiController::class, 'resetPassword'])->name('password.reset');

// =============================================================================
// 3. AREA KANDIDAT (PORTAL TALENTA)
// =============================================================================

Route::prefix('kandidat')->name('kandidat.')->group(function () {
    Route::get('/dashboard', [DemoUiController::class, 'kandidatDashboard'])->name('dashboard');
    Route::get('/profil', [DemoUiController::class, 'kandidatProfil'])->name('profil');
    Route::get('/cv', [DemoUiController::class, 'kandidatCv'])->name('cv.index');
    Route::get('/cv/ekstraksi', [DemoUiController::class, 'kandidatCvEkstraksi'])->name('cv.ekstraksi');
    Route::get('/lowongan', [DemoUiController::class, 'kandidatLowongan'])->name('lowongan');
    Route::get('/lamaran', [DemoUiController::class, 'kandidatLamaran'])->name('lamaran.index');
    Route::get('/lamaran/{id}', [DemoUiController::class, 'kandidatLamaranDetail'])->name('lamaran.show');
    Route::get('/tes', [DemoUiController::class, 'kandidatTesIndex'])->name('tes.index');
    Route::get('/tes/{id}/kerjakan', [DemoUiController::class, 'kandidatTesKerjakan'])->name('tes.kerjakan');
    Route::get('/tes/{id}/hasil', [DemoUiController::class, 'kandidatTesHasil'])->name('tes.hasil');
});

// =============================================================================
// 4. AREA HR & RECRUITER (PORTAL SELEKSI & MODUL AI)
// =============================================================================

Route::prefix('hr')->name('hr.')->group(function () {
    Route::get('/dashboard', [DemoUiController::class, 'hrDashboard'])->name('dashboard');
    
    // Modul A: Pengelolaan Rekrutmen
    Route::get('/lowongan', [DemoUiController::class, 'hrLowonganIndex'])->name('lowongan.index');
    Route::get('/lowongan/create', [DemoUiController::class, 'hrLowonganCreate'])->name('lowongan.create');
    Route::get('/lowongan/{id}/edit', [DemoUiController::class, 'hrLowonganEdit'])->name('lowongan.edit');
    Route::get('/pelamar', [DemoUiController::class, 'hrPelamarIndex'])->name('pelamar.index');
    Route::get('/pelamar/{id}', [DemoUiController::class, 'hrPelamarDetail'])->name('pelamar.show');
    Route::get('/pipeline', [DemoUiController::class, 'hrPipeline'])->name('pipeline');
    
    // Modul B: CV & Resume Parser
    Route::get('/cv-parser', [DemoUiController::class, 'hrCvParser'])->name('cv_parser');
    
    // Modul C: Automated Skill Matching (S-BERT)
    Route::get('/skill-matching', [DemoUiController::class, 'hrSkillMatching'])->name('skill_matching');
    
    // Modul D: Smart Grading Test
    Route::get('/smart-grading', [DemoUiController::class, 'hrSmartGradingIndex'])->name('smart_grading.index');
    Route::get('/smart-grading/penilaian/{id}', [DemoUiController::class, 'hrSmartGradingPenilaian'])->name('smart_grading.penilaian');
    
    // Modul E: Rekomendasi Kandidat
    Route::get('/rekomendasi', [DemoUiController::class, 'hrRekomendasi'])->name('rekomendasi');
    
    // Modul F: Deteksi Anomali
    Route::get('/anomali', [DemoUiController::class, 'hrAnomali'])->name('anomali');
    
    // Modul G: Analitik Rekrutmen
    Route::get('/analitik', [DemoUiController::class, 'hrAnalitik'])->name('analitik');
});

// =============================================================================
// 5. AREA ADMINISTRATOR
// =============================================================================

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [DemoUiController::class, 'adminUsers'])->name('users');
    Route::get('/roles', [DemoUiController::class, 'adminRoles'])->name('roles');
    Route::get('/settings', [DemoUiController::class, 'adminSettings'])->name('settings');
    Route::get('/audit-log', [DemoUiController::class, 'adminAuditLog'])->name('audit_log');
});


// =============================================================================
// 6. ROUTE BACKEND ASLI TIM (DIPERTAHANKAN SEPENUHNYA UNTUK INTEGRASI LANJUTAN)
// =============================================================================

Route::resource('lowongan', LowonganController::class)
    ->parameters(['lowongan' => 'lowongan'])
    ->middleware('auth.basic');

// Group HR Backend (Adi)
Route::middleware(['auth.basic', 'role:hr'])->group(function () {
    // Bank Soal
    Route::resource('soal', SoalController::class);
    Route::patch('soal/{soal}/archive', [SoalController::class, 'archive'])->name('soal.archive');

    // Paket Tes
    Route::resource('paket', PaketTesController::class);

    // Penugasan Tes
    Route::resource('penugasan', PenugasanTesController::class)
        ->only(['index', 'create', 'store', 'show']);

    // Penilaian Manual HR & Publikasi Hasil
    Route::get('penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('penilaian/{penugasan}', [PenilaianController::class, 'show'])->name('penilaian.show');
    Route::get('penilaian/{penugasan}/edit', [PenilaianController::class, 'edit'])->name('penilaian.edit');
    Route::put('penilaian/{penugasan}', [PenilaianController::class, 'update'])->name('penilaian.update');
    Route::post('penilaian/{penugasan}/publish', [PenilaianController::class, 'togglePublish'])->name('penilaian.publish');
    Route::post('penilaian/{penugasan}/retry-ai', [PenilaianController::class, 'retrySmartGrading'])->name('penilaian.retry_ai');

    // Automated Skill Matching (S-BERT)
    Route::get('lowongan/{lowongan}/matching', [SkillMatchingController::class, 'index'])->name('lowongan.matching');
    Route::post('lowongan/{lowongan}/matching', [SkillMatchingController::class, 'match'])->name('lowongan.matching.run');
    Route::post('api/skill-matching/test', [SkillMatchingController::class, 'apiMatch'])->name('api.skill_matching.test');
});

// Group Kandidat Backend (Adi)
Route::middleware(['auth.basic', 'role:kandidat'])->group(function () {
    Route::get('tes-saya', [TesKandidatController::class, 'index'])->name('kandidat.backend.index');
    Route::get('tes-saya/{penugasan}', [TesKandidatController::class, 'show'])->name('kandidat.backend.show');
    Route::post('tes-saya/{penugasan}/mulai', [TesKandidatController::class, 'start'])->name('kandidat.backend.start');
    Route::get('tes-saya/{penugasan}/kerjakan', [TesKandidatController::class, 'kerjakan'])->name('kandidat.backend.kerjakan');
    Route::post('tes-saya/{penugasan}/autosave', [TesKandidatController::class, 'autosave'])->name('kandidat.backend.autosave');
    Route::post('tes-saya/{penugasan}/submit', [TesKandidatController::class, 'submit'])->name('kandidat.backend.submit');
});

// Halaman Publik: Pelamar Upload CV (Danul)
Route::get('/upload-cv', [CvController::class, 'create'])->name('cv.create');
Route::post('/upload-cv', [CvController::class, 'store'])->name('cv.store');

// Halaman HRD: Manajemen CV Masuk (Danul)
Route::prefix('hrd')->name('cv.')->group(function () {
    Route::get('/cv',                      [CvController::class, 'index'])->name('index');
    Route::get('/cv/{pelamar}/edit',       [CvController::class, 'edit'])->name('edit');
    Route::put('/cv/{pelamar}',            [CvController::class, 'update'])->name('update');
    Route::delete('/cv/{pelamar}',         [CvController::class, 'destroy'])->name('destroy');
    Route::get('/cv/{pelamar}/download',   [CvController::class, 'download'])->name('download');
    Route::post('/cv/{pelamar}/reparse',   [CvController::class, 'reparse'])->name('reparse');
    Route::get('/cv/{pelamar}/ai-detail',  [CvController::class, 'aiDetail'])->name('ai_detail');
});
