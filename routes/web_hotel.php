<?php

use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Inertia\Inertia;

Route::put('/hotels/{hotel}', [HotelController::class, 'update'])
    ->name('hotels.update');

Route::delete('hotels/{hotel}', [HotelController::class, 'delete'])
    ->name('hotels.delete');

Route::put('/hotels/{hotel}/add-features', [HotelController::class, 'addFeatures'])
    ->name('hotels.add-features');
