<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\LoanProductController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/admin/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/admin/logout', [AuthController::class, 'logout']);
    Route::get('/admin/me',      [AuthController::class, 'me']);

    // Dashboard
    Route::get('/admin/dashboard/stats', [AdminDashboardController::class, 'stats']);

    // Loan Products
    Route::apiResource('/admin/loan-products', LoanProductController::class);
    Route::patch('/admin/loan-products/{loanProduct}/toggle-status', [LoanProductController::class, 'toggleStatus']);

});