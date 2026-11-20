<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['admin', 'can:access-admin'])->group(function () {
    Route::prefix('report')->group(function () {
        Route::inertia('', 'admin/reports/index')->name('admin.report.index');
    });
});
