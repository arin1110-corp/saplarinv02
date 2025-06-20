<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('homepage');
});
Route::get('/input-spj', function () {
    return view('loginuser');
});
Route::get('/loginuser', function () {
    return view('loginuser');
});
Route::get('/daftarakunuser', function () {
    return view('daftaruser');
});
