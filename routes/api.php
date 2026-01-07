<?php

use App\Http\Controllers\Api\LapanganController;
use App\Http\Controllers\Api\PemesananController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/password', [UserController::class, 'changePassword']);
});

Route::post('/register', [AuthController::class, 'register']);


Route::apiResource('users', UserController::class);
Route::apiResource('lapangans', LapanganController::class);
Route::apiResource('pemesanans', PemesananController::class);
Route::get('/cek-ketersediaan', [PemesananController::class, 'cekKetersediaan']);
Route::post('pemesanans/{id}/upload-bukti', [PemesananController::class, 'uploadBukti']);
Route::get('/riwayat-transaksi', [PemesananController::class, 'riwayatTransaksi'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->post('/pemesanans/{id}/pay', [PemesananController::class, 'pay']);

// webhook TANPA auth
//Route::post('/payments/midtrans-webhook', [PemesananController::class, 'midtransWebhook']);


Route::post('/payments/midtrans-webhook', [PaymentController::class, 'midtransWebhook']);

