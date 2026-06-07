<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrangTuaController;
use App\Http\Controllers\Api\PengajarController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\LevelPembelajaranController;
use App\Http\Controllers\Api\ModulPembelajaranController;
use App\Http\Controllers\Api\TransaksiModulController;
use App\Http\Controllers\Api\KeuanganController;

Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('/orang-tua', OrangTuaController::class);
Route::apiResource('/pengajar', PengajarController::class);
Route::apiResource('/siswa', SiswaController::class);
Route::apiResource('/level-pembelajaran', LevelPembelajaranController::class);
Route::apiResource('/modul-pembelajaran', ModulPembelajaranController::class);
Route::apiResource('/transaksi-modul', TransaksiModulController::class);
Route::apiResource('/keuangan', KeuanganController::class);
