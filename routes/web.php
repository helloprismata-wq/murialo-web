<?php

use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
