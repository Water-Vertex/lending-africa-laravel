<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Website\LoanApplicationInquiryController;
use App\Http\Controllers\Website\CustomerController;
use App\Http\Controllers\Website\CustomerByStaffController;
use App\Http\Controllers\Website\StaffProfileController;


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

// Route::post('/apply', function () {
//     return back()->with('success', 'Application submitted successfully!');
// })->name('apply.store');

/*
|--------------------------------------------------------------------------
| Loan Application
|--------------------------------------------------------------------------
*/

Route::post(
    '/loan-application',
    [LoanApplicationInquiryController::class, 'store']
)->name('loan.application.store');


Route::post('/contact', function () {
    return back()->with('success', 'Message sent successfully!');
})->name('contact.store');



// |--------------------------------------------------------------------------
// | STAFF LOGIN (web / blade — session guard 'staff')
// |--------------------------------------------------------------------------

