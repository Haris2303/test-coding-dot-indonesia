<?php

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

Route::middleware(\App\Http\Middleware\WebAuthMiddleware::class)->group(function () {
    Route::get('/', function () {
        return view('books');
    });
});

Route::get('/login', [\App\Http\Controllers\LoginController::class, 'index'])->middleware('guest');
Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login']);

Route::get('/register', [\App\Http\Controllers\RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [\App\Http\Controllers\RegisterController::class, 'register']);
