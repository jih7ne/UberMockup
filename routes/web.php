<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;

// Routes for guests (not logged in users)
// middleware('guest') = only non-logged-in users can access these
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login'); // Shows login form
    Route::get('/register', Register::class)->name('register'); // Shows register form
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request'); // Shows forgot password form
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset'); // Shows reset password form
});

// Routes for authenticated users (logged in)
// middleware('auth') = only logged-in users can access these
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $drivers = \App\Models\User::availableDrivers()->get();
        return view('dashboard', compact('drivers'));
    })->name('dashboard');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
// Home page redirects to login
Route::get('/', function () {
    return redirect()->route('login');
});