<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\CarController;
use App\Http\Controllers\Api\V1\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- RUTE PUBLIK & AUTENTIKASI (di luar prefix /api) ---
// Sanctum secara default mencari URL ini di root
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Rute ini HARUS menggunakan prefix /api agar dilindungi oleh middleware 'api'
Route::middleware('auth:sanctum')->get('/api/user', function (Request $request) {
    return $request->user();
});


// --- GRUP API V1 (di dalam prefix /api) ---
Route::prefix('api/v1')->group(function () {
    // Rute yang TIDAK memerlukan autentikasi
    Route::get('/cars', [CarController::class, 'index']);
    Route::get('/cars/{car}', [CarController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);

    // Rute yang MEMERLUKAN autentikasi
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/cars', [CarController::class, 'store']);
        Route::put('/cars/{car}', [CarController::class, 'update']);
        Route::delete('/cars/{car}', [CarController::class, 'destroy']);
    });
});
