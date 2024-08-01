<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('login/google', [LoginController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);


Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    });
});

Route::middleware(['role:customer'])->group(function () {
    Route::get('/customer', function () {
        return view('customer.dashboard');
    });
});
