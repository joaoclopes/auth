<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Middleware\TenantMiddleware;

Route::prefix('auth')->group(function () {
    Route::post('/', [AuthController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login'])->middleware(TenantMiddleware::class);
});

Route::prefix('enterprise')->group(function () {
    Route::post('/', [EnterpriseController::class, 'store']);
});

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);