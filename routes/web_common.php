<?php

use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Inertia\Inertia;

Route::get('/index', function () {
    return Inertia::render('Common/Index');
})->name('common.index');

Route::get('/profile/{user}', [UserController::class, 'show'])
    ->name('profile.show');

Route::put('/profile/{user}', [UserController::class, 'update'])
    ->name('profile.update');

Route::delete('/profile/{user}', [ProfileController::class, 'destroy'])
    ->name('profile.destroy');

Route::get('/impersonate/stop', [ImpersonationController::class, 'stop'])
    ->name('impersonate.stop');

Route::get('/hotels/search', [HotelController::class, 'index'])
    ->name('hotels.index');

Route::get('hotels/{hotel}', [HotelController::class, 'show'])
    ->name('hotels.show');

