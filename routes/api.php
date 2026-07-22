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

// Public routes
Route::post('admin/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/admin/logout', [AuthController::class, 'logout']);
    Route::get('/admin/me',      [AuthController::class, 'me']);

    // Dashboard
    Route::get('/admin/dashboard/stats', [AdminDashboardController::class, 'stats']);

    // Loan Products
    Route::apiResource('/admin/loan-products', LoanProductController::class);
    Route::patch('/admin/loan-products/{loanProduct}/toggle-status', [LoanProductController::class, 'toggleStatus']);

        // Roles
    Route::get('/admin/roles',           [RoleController::class, 'index']);
    Route::post('/admin/roles',          [RoleController::class, 'store']);
    Route::get('/admin/roles/{id}',      [RoleController::class, 'show']);
    Route::put('/admin/roles/{id}',      [RoleController::class, 'update']);
    Route::delete('/admin/roles/{id}',   [RoleController::class, 'destroy']);


    Route::get('/admin/loan-inquiries', [LoanApplicationInquiryController::class, 'index']);
    Route::get('/admin/loan-inquiries/stats', [LoanApplicationInquiryController::class, 'stats']);
    Route::get('/admin/loan-inquiries/{id}', [LoanApplicationInquiryController::class, 'show']);

    // Users
    Route::get('/admin/users',           [UserController::class, 'index']);
    Route::post('/admin/users',          [UserController::class, 'store']);
    Route::get('/admin/users/{id}',      [UserController::class, 'show']);
    Route::put('/admin/users/{id}',      [UserController::class, 'update']);
    Route::delete('/admin/users/{id}',   [UserController::class, 'destroy']);
 // Collaterals (collaterals table)
    Route::get('/admin/collaterals',           [CollateralController::class, 'index']);
    Route::post('/admin/collaterals',          [CollateralController::class, 'store']);
    Route::get('/admin/collaterals/{id}',      [CollateralController::class, 'show']);
    Route::put('/admin/collaterals/{id}',      [CollateralController::class, 'update']);
    Route::delete('/admin/collaterals/{id}',   [CollateralController::class, 'destroy']);

    // Collateral Types (collateral_types table)
    Route::get('/admin/collateral-types',           [CollateralController::class, 'typesIndex']);
    Route::post('/admin/collateral-types',          [CollateralController::class, 'typesStore']);
    Route::get('/admin/collateral-types/{id}',      [CollateralController::class, 'typesShow']);
    Route::put('/admin/collateral-types/{id}',      [CollateralController::class, 'typesUpdate']);
    Route::delete('/admin/collateral-types/{id}',   [CollateralController::class, 'typesDestroy']);
Route::get('/admin/collaterals-form/loan-applications', [CollateralController::class, 'loanApplicationsList']);
    Route::get('/admin/collaterals-form/collateral-types', [CollateralController::class, 'collateralTypesList']);

    Route::apiResource('customers', CustomerController::class);
    Route::put('/admin/reset-password', [ProfileController::class, 'changePassword']);


Route::get('/admin/faqs', [FaqController::class, 'index']);
Route::post('/admin/faqs', [FaqController::class, 'store']);
Route::get('/admin/faqs/{id}', [FaqController::class, 'show']);
Route::put('/admin/faqs/{id}', [FaqController::class, 'update']);
Route::delete('/admin/faqs/{id}', [FaqController::class, 'destroy']);
// Loan Applications (read + status change only)
Route::get('/admin/loan-applications',              [LoanApplicationController::class, 'index']);
Route::get('/admin/loan-applications/stats',         [LoanApplicationController::class, 'stats']);
Route::get('/admin/loan-applications/{id}',          [LoanApplicationController::class, 'show']);
Route::patch('/admin/loan-applications/{id}/status', [LoanApplicationController::class, 'updateStatus']);
 

    // Additional custom routes
    Route::prefix('customers')->group(function () {
    Route::get('/stats', [CustomerController::class, 'stats']);
    Route::post('/bulk-status', [CustomerController::class, 'bulkStatusUpdate']);
    Route::patch('/{customer}/status', [CustomerController::class, 'updateStatus']);
    Route::post('/{customer}/documents', [CustomerController::class, 'addDocuments']);
});

    
   
     Route::get('/admin/banks',           [BankController::class, 'index']);
Route::post('/admin/banks',          [BankController::class, 'store']);
Route::get('/admin/banks/{id}',      [BankController::class, 'show']);
Route::put('/admin/banks/{id}',      [BankController::class, 'update']);
Route::delete('/admin/banks/{id}',   [BankController::class, 'destroy']);

    // Staff
      Route::get('/admin/staff/last-code', [StaffController::class, 'getLastStaffCode']);
    Route::get('/admin/staff',           [StaffController::class, 'index']);
    Route::post('/admin/staff',          [StaffController::class, 'store']);
    Route::get('/admin/staff/{id}',      [StaffController::class, 'show']);
    Route::put('/admin/staff/{id}',      [StaffController::class, 'update']);
    Route::delete('/admin/staff/{id}',   [StaffController::class, 'destroy']);

});