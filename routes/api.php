<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::prefix('user')->group(function () {
    Route::post('/test', [AuthController::class, 'test']);
    Route::post('/', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/test', [AuthController::class, 'test']);
});

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);