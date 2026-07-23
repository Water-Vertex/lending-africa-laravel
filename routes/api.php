<?php

use App\Http\Controllers\Api\AdminDashboardController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CollateralController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\LoanProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\LoanApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoanApplicationInquiryController;
use App\Http\Controllers\Api\CustomerByStaffController;

// ============================================================
// PUBLIC ROUTES (No Auth Required)
// ============================================================

// Admin Login
Route::post('admin/login', [AuthController::class, 'login']);

// Staff Login
Route::post('staff/login', [AuthController::class, 'staffLogin']);

// ============================================================
// AUTHENTICATED ROUTES (Sanctum Auth Required)
// ============================================================
Route::middleware('auth:sanctum')->group(function () {

    // ---------- Admin Routes ----------
    Route::prefix('admin')->group(function () {

        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // Dashboard
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats']);

        // Loan Products
        Route::apiResource('/loan-products', LoanProductController::class);
        Route::patch('/loan-products/{loanProduct}/toggle-status', [LoanProductController::class, 'toggleStatus']);

        // Roles
        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::get('/roles/{id}', [RoleController::class, 'show']);
        Route::put('/roles/{id}', [RoleController::class, 'update']);
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

        // Loan Inquiries
        Route::get('/loan-inquiries', [LoanApplicationInquiryController::class, 'index']);
        Route::get('/loan-inquiries/stats', [LoanApplicationInquiryController::class, 'stats']);
        Route::get('/loan-inquiries/{id}', [LoanApplicationInquiryController::class, 'show']);


                // Loan Applications
        Route::get('/loan-applications', [App\Http\Controllers\Api\LoanApplicationController::class, 'index']);
        Route::get('/loan-applications/stats', [App\Http\Controllers\Api\LoanApplicationController::class, 'stats']);
        Route::get('/loan-applications/{id}', [App\Http\Controllers\Api\LoanApplicationController::class, 'show']);
        Route::patch('/loan-applications/{id}/status', [App\Http\Controllers\Api\LoanApplicationController::class, 'updateStatus']);

        // Users
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Collaterals
        Route::get('/collaterals', [CollateralController::class, 'index']);
        Route::post('/collaterals', [CollateralController::class, 'store']);
        Route::get('/collaterals/{id}', [CollateralController::class, 'show']);
        Route::put('/collaterals/{id}', [CollateralController::class, 'update']);
        Route::delete('/collaterals/{id}', [CollateralController::class, 'destroy']);

//    // Collateral Types (collateral_types table)
//     Route::get('/admin/collateral-types',           [CollateralController::class, 'typesIndex']);
//     Route::post('/admin/collateral-types',          [CollateralController::class, 'typesStore']);
//     Route::get('/admin/collateral-types/{id}',      [CollateralController::class, 'typesShow']);
//     Route::put('/admin/collateral-types/{id}',      [CollateralController::class, 'typesUpdate']);
//     Route::delete('/admin/collateral-types/{id}',   [CollateralController::class, 'typesDestroy']);
Route::get('/collaterals-form/loan-applications', [CollateralController::class, 'loanApplicationsList']);
    Route::get('/collaterals-form/collateral-types', [CollateralController::class, 'collateralTypesList']);

    Route::apiResource('customers', CustomerController::class);
    Route::put('/admin/reset-password', [ProfileController::class, 'changePassword']);


Route::get('/faqs', [FaqController::class, 'index']);
Route::post('/faqs', [FaqController::class, 'store']);
Route::get('/faqs/{id}', [FaqController::class, 'show']);
Route::put('/faqs/{id}', [FaqController::class, 'update']);
Route::delete('/admin/faqs/{id}', [FaqController::class, 'destroy']);
// Loan Applications (read + status change only)
Route::get('/loan-applications',              [LoanApplicationController::class, 'index']);
Route::get('/loan-applications/stats',         [LoanApplicationController::class, 'stats']);
Route::get('/loan-applications/{id}',          [LoanApplicationController::class, 'show']);
Route::patch('/loan-applications/{id}/status', [LoanApplicationController::class, 'updateStatus']);
 

        // Collateral Types
        Route::get('/collateral-types', [CollateralController::class, 'typesIndex']);
        Route::post('/collateral-types', [CollateralController::class, 'typesStore']);
        Route::get('/collateral-types/{id}', [CollateralController::class, 'typesShow']);
        Route::put('/collateral-types/{id}', [CollateralController::class, 'typesUpdate']);
        Route::delete('/collateral-types/{id}', [CollateralController::class, 'typesDestroy']);

        // Customers
        Route::apiResource('customers', CustomerController::class);
        Route::prefix('customers')->group(function () {
            Route::get('/stats', [CustomerController::class, 'stats']);
            Route::post('/bulk-status', [CustomerController::class, 'bulkStatusUpdate']);
            Route::patch('/{customer}/status', [CustomerController::class, 'updateStatus']);
            Route::post('/{customer}/documents', [CustomerController::class, 'addDocuments']);
        });

        // Banks
        Route::get('/banks', [BankController::class, 'index']);
        Route::post('/banks', [BankController::class, 'store']);
        Route::get('/banks/{id}', [BankController::class, 'show']);
        Route::put('/banks/{id}', [BankController::class, 'update']);
        Route::delete('/banks/{id}', [BankController::class, 'destroy']);

        // Staff
        Route::get('/staff/last-code', [StaffController::class, 'getLastStaffCode']);
        Route::get('/staff', [StaffController::class, 'index']);
        Route::post('/staff', [StaffController::class, 'store']);
        Route::get('/staff/{id}', [StaffController::class, 'show']);
        Route::put('/staff/{id}', [StaffController::class, 'update']);
        Route::delete('/staff/{id}', [StaffController::class, 'destroy']);
    });

    // ---------- Staff Routes ----------
    Route::prefix('staff')->group(function () {

        // Logout
        Route::post('/logout', [AuthController::class, 'staffLogout']);
        Route::get('/profile', [StaffController::class, 'profile']);
    Route::put('/profile', [StaffController::class, 'updateProfile']);

        Route::get('/loan-products', [CustomerByStaffController::class, 'loanProducts']);

        // Staff Customers
        Route::get('/customers', [CustomerByStaffController::class, 'index']);
        Route::post('/customers', [CustomerByStaffController::class, 'store']);
    });
});