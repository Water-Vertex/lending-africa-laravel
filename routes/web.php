<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Website\LoanApplicationInquiryController;

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
