<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Master Data - Pegawai
Route::prefix('master-data')->name('master-data.')->group(function () {
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('bagian', BagianController::class);
    Route::patch('bagian/{bagian}/toggle-status', [BagianController::class, 'toggleStatus'])->name('bagian.toggle-status');
});

// Absensi
    // Route::get('/', [AbsensiController::class, 'index'])->name('index');
    // Route::get('/create', [AbsensiController::class, 'create'])->name('create');
    // Route::post('/', [AbsensiController::class, 'store'])->name('store');
    // Route::get('/edit/{absensi}', [AbsensiController::class, 'edit'])->name('edit');
    // Route::post('/edit/', [AbsensiController::class, 'update'])->name('update');
    Route::resource('absensi', AbsensiController::class);
    Route::get('/absensi/laporan', [AbsensiController::class, 'laporan'])->name('absensi.laporan');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
