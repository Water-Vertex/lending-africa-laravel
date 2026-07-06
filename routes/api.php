<?php
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CollateralController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/admin/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/admin/logout', [AuthController::class, 'logout']);
    Route::get('/admin/me',      [AuthController::class, 'me']);

    // Dashboard
    Route::get('/admin/dashboard/stats', [AdminDashboardController::class, 'stats']);
       // Roles
    Route::get('/admin/roles',           [RoleController::class, 'index']);
    Route::post('/admin/roles',          [RoleController::class, 'store']);
    Route::get('/admin/roles/{id}',      [RoleController::class, 'show']);
    Route::put('/admin/roles/{id}',      [RoleController::class, 'update']);
    Route::delete('/admin/roles/{id}',   [RoleController::class, 'destroy']);




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


});
