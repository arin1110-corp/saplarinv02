<?php

use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SAPLARIN Mobile API
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthApiController::class, 'me']);

    Route::post('/logout', [AuthApiController::class, 'logout']);
});