<?php

use App\Http\Controllers\AuthLoginController;
use App\Http\Controllers\AuthRegisterController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome-page')->name('home');

Route::prefix('auth')->middleware('guest')->group(function (): void {
    Route::controller(AuthLoginController::class)->name('login.')->group(function (): void {
        Route::get('/login', 'create')->name('create');
        Route::post('/login', 'store')->middleware('throttle:6,1')->name('store');
    });
    Route::controller(AuthRegisterController::class)->name('register.')->group(function (): void {
        Route::get('/register', 'create')->name('create');
        Route::post('/register', 'store')->name('store');
    });
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthLoginController::class, 'destroy'])->name('auth.logout');

    require base_path('routes/modules/admin.php');
});
