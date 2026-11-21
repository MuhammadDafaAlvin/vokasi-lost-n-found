<?php

use App\Http\Controllers\AuthLoginController;
use App\Http\Controllers\AuthRegisterController;
use App\Http\Controllers\ReportController;
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

    Route::controller(ReportController::class)->prefix('reports')->name('report.')->group(function (): void {
        Route::get('/my', 'mine')->name('mine');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{report}/edit', 'edit')->whereNumber('report')->name('edit');
        Route::get('/{report}', 'show')->whereNumber('report')->name('show');
        Route::put('/{report}', 'update')->whereNumber('report')->name('update');
        Route::patch('/{report}/status', 'updateStatus')->whereNumber('report')->name('status.update');
        Route::get('/', 'index')->name('index');
    });

    require base_path('routes/modules/admin.php');
});
