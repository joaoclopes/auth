<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\FormController;
use App\Http\Middleware\TenantMiddleware;

Route::prefix('auth')->group(function () {
    Route::post('/', [AuthController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login'])->middleware(TenantMiddleware::class);
    Route::post('/duplicate', [AuthController::class, 'selectDuplicatedUser'])->middleware(TenantMiddleware::class);
    Route::post('/validate', [AuthController::class, 'validateToken']);
    Route::post('/validate-code', [AuthController::class, 'validateConfirmationCode']);
    Route::post('/confirm', [AuthController::class, 'confirmEmail']);
});

Route::prefix('enterprise')->group(function () {
    Route::post('/', [EnterpriseController::class, 'store']);
});

Route::prefix('form')->group(function () {
    Route::get('/', [FormController::class, 'getForm']);
});

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);