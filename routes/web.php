<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\SignupController;
use App\Http\Controllers\Frontend\ChartsController;
use App\Http\Controllers\Frontend\MapsController;

// Public routes
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/signup', [SignupController::class, 'index'])->name('signup');
Route::post('/register', [SignupController::class, 'register'])->name('register');


// Google OAuth routes
Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('login.google.callback');

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes accessible only to authenticated users
Route::middleware(['auth'])->group(function () {
    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', function () {
            return view('admin.dashboard');
        });
    });

    // Customer routes
    Route::middleware(['role:customer'])->group(function () {
        Route::get('/', [HomeController::class, 'index']);
        Route::resource('/users',UserController::class);
        Route::get('/states/{country}', [UserController::class, 'getStates']);
        Route::get('/cities/{state}', [UserController::class, 'getCities']);
        Route::get('/profile', [ProfileController::class, 'index']);
        Route::get('/charts', [ChartsController::class, 'index']);
        Route::get('/maps', [MapsController::class, 'index']);
    });
});
