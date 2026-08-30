<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BBMApiController;
use App\Http\Controllers\Api\BookingRuangApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SAPLARIN Mobile API
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    Route::get('/me', [AuthApiController::class, 'me']);
    Route::post('/logout', [AuthApiController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | BBM
    |--------------------------------------------------------------------------
    */

    Route::get('/bbm', [BBMApiController::class, 'index']);
    Route::post('/bbm', [BBMApiController::class, 'store']);
    Route::get('/bbm/{uid}', [BBMApiController::class, 'show']);
    Route::post('/bbm/{uid}/laporan', [BBMApiController::class, 'uploadLaporan']);

    /*
    |--------------------------------------------------------------------------
    | RUANG RAPAT
    |--------------------------------------------------------------------------
    */

    Route::get('/ruang-rapat/rooms', [BookingRuangApiController::class, 'rooms']);

    Route::get('/booking-ruang', [BookingRuangApiController::class, 'index']);

    Route::get('/booking-ruang/events', [BookingRuangApiController::class, 'events']);

    Route::get('/booking-ruang/{uid}', [BookingRuangApiController::class, 'show']);

    Route::post('/booking-ruang/check-availability', [BookingRuangApiController::class, 'checkAvailability']);

    Route::post('/booking-ruang', [BookingRuangApiController::class, 'store']);

    Route::post('/booking-ruang/{uid}/batal', [BookingRuangApiController::class, 'cancel']);
});