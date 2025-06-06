<?php

use App\Http\Controllers\HotelController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/index');
    }

    return Inertia::render('Welcome');
})->name('welcome');

Route::get('/attractions', function () {
    return Inertia::render('Attractions');
});

Route::get('/hotels/create', [HotelController::class, 'create'])
    ->name('hotels.create');

Route::post('hotels/create', [HotelController::class, 'store'])
    ->name('hotels.store');
