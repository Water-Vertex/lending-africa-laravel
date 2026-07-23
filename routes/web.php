<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Website\LoanApplicationInquiryController;
use App\Http\Controllers\Website\CustomerController;
use App\Http\Controllers\Website\CustomerByStaffController;
use App\Http\Controllers\Website\StaffProfileController;
use App\Http\Controllers\Website\PolicyController;


Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('/customer-add', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customer-add', [CustomerController::class, 'store'])->name('customer.store');
});

/*
|--------------------------------------------------------------------------
| TEMPLATE 1 — user (Bnker style)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('user.pages.home');
})->name('home');


// Policy Routes
Route::get('/policy/{slug}', [PolicyController::class, 'show'])->name('policy.show');

/*
|--------------------------------------------------------------------------
| Loan Application
|--------------------------------------------------------------------------
*/

Route::post(
    '/loan-application',
    [LoanApplicationInquiryController::class, 'store']
)->name('loan.application.store');

/*
|--------------------------------------------------------------------------
| Contact
|--------------------------------------------------------------------------
*/

Route::get('/contact', function () {
    return redirect()->route('home');
});

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// |--------------------------------------------------------------------------
// | STAFF LOGIN (web / blade — session guard 'staff')
// |--------------------------------------------------------------------------