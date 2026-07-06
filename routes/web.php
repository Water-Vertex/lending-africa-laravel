<?php

use Illuminate\Support\Facades\Route;

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


/*
|--------------------------------------------------------------------------
| TEMPLATE 2 — user2 (Alons style)
|--------------------------------------------------------------------------
*/
Route::get('/v2', function () {
    return view('user2.pages.home');
})->name('home2');

Route::post('/v2/apply', function () {
    return back()->with('success', 'Application submitted successfully!');
})->name('apply2.store');

Route::post('/v2/contact', function () {
    return back()->with('success', 'Message sent successfully!');
})->name('contact2.store');


/*
|--------------------------------------------------------------------------
| TEMPLATE 3 — user3 (Clean modern, signup form hero)
|--------------------------------------------------------------------------
*/
Route::get('/v3', function () {
    return view('user3.pages.home');
})->name('home3');

Route::post('/v3/apply', function () {
    return back()->with('success', 'Application submitted successfully!');
})->name('apply3.store');

Route::post('/v3/contact', function () {
    return back()->with('success', 'Message sent successfully!');
})->name('contact3.store');


/*
|--------------------------------------------------------------------------
| TEMPLATE 4 — user4 (Ledger / Statement style, split-screen hero)
|--------------------------------------------------------------------------
*/
Route::get('/v4', function () {
    return view('user4.pages.home');
})->name('home4');

Route::post('/v4/apply', function () {
    return back()->with('success', 'Application submitted successfully!');
})->name('apply4.store');

Route::post('/v4/contact', function () {
    return back()->with('success', 'Message sent successfully!');
})->name('contact4.store');