<?php

use App\Features\Home\Controllers\HomeController;
use App\Features\Reports\Controllers\LoadReportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/reports/loads', [LoadReportsController::class, 'loadsReport'])
        ->name('reports.loads');
    Route::get('/reports/late-submissions', [LoadReportsController::class, 'lateSubmissions'])
        ->name('reports.late-submissions');
    Route::get('/reports/not-submitted', [LoadReportsController::class, 'notSubmitted'])
        ->name('reports.not-submitted');
    Route::get('/reports/faculty', [LoadReportsController::class, 'facultyReport'])
        ->name('reports.faculty');
});
