<?php

use App\Http\Controllers\Hotel\HotelController;

Route::put('/hotels/{hotel}', [HotelController::class, 'update'])
    ->name('hotels.update');

Route::delete('hotels/{hotel}', [HotelController::class, 'delete'])
    ->name('hotels.delete');

Route::put('/hotels/{hotel}/add-features', [HotelController::class, 'addFeatures'])
    ->name('hotels.add-features');
