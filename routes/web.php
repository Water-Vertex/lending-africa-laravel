<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Website\LoanApplicationInquiryController;
use App\Http\Controllers\Website\CustomerController;

// TEMPORARY: staff login abhi ban nahi (auth pending), isliye direct route.
// Jab staff auth + dashboard ban jaye, "Customer Registration" button
// dashboard ke andar isi route pe point karega, aur ye route middleware
// 'auth:staff' (ya jo bhi guard ho) ke peeche chala jayega.
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
