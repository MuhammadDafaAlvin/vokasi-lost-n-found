<?php

use App\Http\Controllers\AdminReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['admin', 'can:access-admin'])->group(function (): void {
    Route::get('/report', [AdminReportController::class, 'index'])->name('admin.report.index');
    Route::patch('/report/{report}/visibility', [AdminReportController::class, 'updateVisibility'])
        ->whereNumber('report')
        ->name('admin.report.visibility');
});
