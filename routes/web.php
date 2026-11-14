<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->group(function () {
        // Route khusus admin
    });
Route::inertia('/', 'welcome-page')->name('home');
