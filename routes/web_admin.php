<?php

use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index'])->name('users.index');

Route::get('impersonate/{user}', [ImpersonationController::class, 'start'])
    ->middleware('can:impersonate,user')
    ->name('impersonate.start');

Route::get('/reports/{user}/reviews-pdf', [ReportController::class, 'reviewsPdf'])
    ->name('reports.reviews.pdf');

// Features

Route::get('/features', [FeatureController::class, 'index'])
    ->name('feature.index');

Route::get('/features/{feature}', [FeatureController::class, 'show'])
    ->name('feature.show');

Route::put('/features/{feature}', [FeatureController::class, 'update'])
    ->name('feature.update');

Route::post('/features/create', [FeatureController::class, 'store'])
    ->name('feature.store');

Route::delete('/features/{feature}', [FeatureController::class, 'destroy'])
    ->name('feature.destroy');
