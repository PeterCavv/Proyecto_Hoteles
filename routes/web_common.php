<?php

use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Api\V1\ReservationController;
use App\Http\Controllers\Common\HotelController;
use App\Http\Controllers\Common\ProfileController;
use App\Http\Controllers\Common\UserController;
use Inertia\Inertia;

Route::get('/index', function () {
    return Inertia::render('Common/Index');
})->name('common.index');

Route::get('/reservations', function () {
    return Inertia::render('Common/ReservationIndex');
});


Route::get('/profile/{user}', [UserController::class, 'show'])
    ->name('profile.show');

Route::put('/profile/{user}', [UserController::class, 'update'])
    ->name('profile.update');

Route::delete('/profile/{user}', [ProfileController::class, 'destroy'])
    ->name('profile.destroy');

Route::get('/profile/{user}/reviews', [UserController::class, 'reviews'])
    ->name('review.index');

Route::get('/impersonate/stop', [ImpersonationController::class, 'stop'])
    ->name('impersonate.stop');

Route::get('/hotels/search', [HotelController::class, 'index'])
    ->name('hotels.index');

Route::get('hotels/{hotel}', [HotelController::class, 'show'])
    ->name('hotels.show');

