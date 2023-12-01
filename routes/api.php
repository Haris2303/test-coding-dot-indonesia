<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/users/login', [\App\Http\Controllers\UserController::class, 'login']);

// Group middleware authentication
Route::middleware(\App\Http\Middleware\ApiAuthMiddleware::class)->group(function () {
    // Route API users
    Route::get('/users/current', [\App\Http\Controllers\UserController::class, 'get']);
    Route::patch('/users/current', [\App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/users/logout', [\App\Http\Controllers\UserController::class, 'logout']);

    // Route API Categories
    Route::post('/categories', [\App\Http\Controllers\CategoryController::class, 'create']);
    Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'getAll']);
    Route::put('/category/{id}', [\App\Http\Controllers\CategoryController::class, 'update']);
    Route::get('/category/{id}', [\App\Http\Controllers\CategoryController::class, 'get']);
    Route::delete('/category/{id}', [\App\Http\Controllers\CategoryController::class, 'delete']);

    // Route API Books
    Route::get('/books', [\App\Http\Controllers\BookController::class, 'search']);
    Route::post('/category/{idCategory}/books', [\App\Http\Controllers\BookController::class, 'create']);
    Route::get('/category/{idCategory}/books', [\App\Http\Controllers\BookController::class, 'getByCategory']);
    Route::put('/category/{idCategory}/book/{id}', [\App\Http\Controllers\BookController::class, 'update']);
    Route::get('/category/{idCategory}/book/{id}', [\App\Http\Controllers\BookController::class, 'get']);
    Route::delete('/category/{idCategory}/book/{id}', [\App\Http\Controllers\BookController::class, 'delete']);
});
