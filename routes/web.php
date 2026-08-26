<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Website\LoanApplicationInquiryController;
use App\Http\Controllers\Website\CustomerController;
use App\Http\Controllers\Website\CustomerByStaffController;
use App\Http\Controllers\Website\StaffProfileController;
use App\Http\Controllers\Website\PolicyController;
use App\Http\Controllers\Website\AgreementController;
use App\Http\Controllers\Api\FaqController;

 Route::get('/customer-add', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customer-add', [CustomerController::class, 'store'])->name('customer.store');
Route::get('/api/faqs', [App\Http\Controllers\Api\FaqController::class, 'index']);
// Agreement submission — customer facing
Route::get('/loan-agreement/{token}',  [AgreementController::class, 'show'])->name('agreement.show');
Route::post('/loan-agreement/{token}', [AgreementController::class, 'submit'])->name('agreement.submit');
// Customer Edit Form — token se access
Route::get('/customer-edit/{token}', [CustomerController::class, 'editByToken'])->name('customer.edit.token');
Route::post('/customer-edit/{token}', [CustomerController::class, 'updateByToken'])->name('customer.update.token');
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
    $personalProduct = \App\Models\LoanProduct::where('loan_type', 'personal')
        ->where('status', 'active')
        ->first();

    $smeProduct = \App\Models\LoanProduct::where('loan_type', 'sme')
        ->where('status', 'active')
        ->first();

    return view('user.pages.home', compact('personalProduct', 'smeProduct'));
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