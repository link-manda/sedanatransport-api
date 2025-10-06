<?php

use App\Http\Controllers\Api\V1\CarController;
use App\Http\Controllers\Api\V1\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rute untuk mendapatkan informasi user yang sedang login
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Grup API v1
Route::prefix('v1')->group(function () {
    // Rute yang tidak memerlukan autentikasi
    Route::get('/cars', [CarController::class, 'index']);
    Route::get('/cars/{car}', [CarController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);

    // Rute yang memerlukan autentikasi (grup baru)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/cars', [CarController::class, 'store']);
        Route::put('/cars/{car}', [CarController::class, 'update']);
        Route::delete('/cars/{car}', [CarController::class, 'destroy']);
        // Nanti kita bisa tambahkan rute lain di sini, misal untuk manajemen order
    });
});
