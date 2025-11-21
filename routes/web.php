<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TamuController;

// Portal (halaman pertama)
Route::get('/', function () {
    return view('portal');
})->name('portal');

// Form isi tamu
Route::get('/isi', [TamuController::class, 'create'])->name('tamu.create');
// Route::post('/isi', [TamuController::class, 'store'])->name('tamu.store');
Route::post('/isi', [TamuController::class, 'store'])
    ->name('tamu.store')
    ->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ]);

// Daftar tamu frontend (publik)
Route::get('/daftar', [TamuController::class, 'daftar'])->name('tamu.daftar');

// Backend (login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [TamuController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin', [TamuController::class, 'admin'])->name('admin');
    Route::get('/admin/create', [TamuController::class, 'create'])->name('admin.create');
    Route::get('/admin/{id}/edit', [TamuController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/{id}', [TamuController::class, 'update'])->name('admin.update');
    Route::delete('/admin/{id}', [TamuController::class, 'destroy'])->name('admin.destroy');
    Route::get('/admin/export-csv', [TamuController::class, 'exportCsv'])->name('admin.csv');
});

require __DIR__.'/jetstream.php';
