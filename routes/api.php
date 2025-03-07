<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;

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


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/pos', [PosController::class, 'store']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/product/create', [ProductController::class, 'store']);
    Route::delete('/product/delete/{id}', [ProductController::class, 'destroy']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::put('/product/update/{id}', [ProductController::class, 'update']);
});
