<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::resource('users', UserController::class);
Route::resource('lapangans', LapanganController::class);
Route::resource('pemesanans', PemesananController::class);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [\App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard/statistik', [\App\Http\Controllers\HomeController::class, 'statistik']);
Route::get('/dashboard/events', [\App\Http\Controllers\HomeController::class, 'events']);
Route::post('pemesanans/{id}/update-status', [\App\Http\Controllers\PemesananController::class, 'updateStatus'])->name('pemesanans.updateStatus');

Route::get('/laporan/mingguan', [LaporanController::class, 'laporanMingguan'])->name('laporan.mingguan');
Route::get('/laporan/bulanan', [LaporanController::class, 'laporanBulanan'])->name('laporan.bulanan');
Route::get('/laporan/mingguan/pdf', [LaporanController::class, 'cetakMingguan'])->name('laporan.mingguan.pdf');
Route::get('/laporan/bulanan/pdf', [LaporanController::class, 'cetakBulanan'])->name('laporan.bulanan.pdf');
