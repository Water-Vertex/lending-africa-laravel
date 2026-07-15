<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| TEMPLATE 1 — user (Bnker style)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('user.pages.home');
})->name('home');

Route::post('/apply', function () {
    return back()->with('success', 'Application submitted successfully!');
})->name('apply.store');

Route::post('/contact', function () {
    return back()->with('success', 'Message sent successfully!');
})->name('contact.store');



// |--------------------------------------------------------------------------
// | STAFF LOGIN (web / blade — session guard 'staff')
// |--------------------------------------------------------------------------

Route::prefix('staff')->name('staff.')->group(function () {

    Route::get('/login',  [AuthController::class, 'showStaffLoginForm'])->name('login.show');
    Route::post('/login', [AuthController::class, 'staffLogin'])->name('login');

    Route::middleware('auth:staff')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'staffDashboard'])->name('dashboard');
        Route::post('/logout',   [AuthController::class, 'staffLogout'])->name('logout');
    });

});
