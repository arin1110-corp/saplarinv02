<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BBMApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SAPLARIN Mobile API
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/me', [AuthApiController::class, 'me']);
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // BBM
    Route::get('/bbm', [BBMApiController::class, 'index']);
    Route::post('/bbm', [BBMApiController::class, 'store']);
    Route::get('/bbm/{uid}', [BBMApiController::class, 'show']);
    Route::post('/bbm/{uid}/laporan', [BBMApiController::class, 'uploadLaporan']);
});