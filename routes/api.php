<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('users', [UserController::class, 'index']);
Route::get('/products', [ProductController::class , 'index']);
Route::get('/products/{id}', [ProductController::class , 'show']);

Route::middleware(['auth:api'])->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/products', [ProductController::class , 'store']);
        Route::put('/products/{id}', [ProductController::class, 'edit']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        // Route::get('/products', [ProductController::class , 'index']);
    });

});
