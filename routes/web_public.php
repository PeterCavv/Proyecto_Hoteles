<?php

use App\Http\Controllers\Public\HotelController;
use App\Models\Attraction;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/index');
    }

    return Inertia::render('Welcome');
})->name('welcome');

// Attractions

Route::get('/attractions', function () {
    return Inertia::render('Public/Attractions');
});

Route::get('/attractions/create', function () {
    return Inertia::render('Public/AttractionCreate');
})->name('attractions.create');

Route::get('/attractions/{attraction}', function (Attraction $attraction) {
    return Inertia::render('Public/AttractionShow', [
        'id' => $attraction->id
    ]);
});

Route::get('/attractions/edit/{attraction}', function (Attraction $attraction) {
    return Inertia::render('Public/AttractionEdit', [
        'id' => $attraction->id
    ]);
});


// Hotels

Route::get('/hotels/create', [HotelController::class, 'create'])
    ->name('hotels.create');

Route::post('hotels/create', [HotelController::class, 'store'])
    ->name('hotels.store');
