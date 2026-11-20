<?php

use App\Http\Controllers\AuthLoginController;
use App\Http\Controllers\AuthRegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('guest')->group(function () {
    Route::get('/register', [AuthRegisterController::class, 'create'])->name('register');
    Route::post('/register', [AuthRegisterController::class, 'store']);

    Route::get('/login', [AuthLoginController::class, 'create'])->name('login');
    Route::post('/login', [AuthLoginController::class, 'store']);
});

Route::inertia('/', 'welcome-page')->name('home');
