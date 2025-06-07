<?php

use App\Http\Controllers\Api\V1\AttractionController;
use App\Http\Controllers\Api\V1\CityController;
use App\Http\Controllers\Api\V1\ReservationController;
use Illuminate\Support\Facades\Route;

Route::apiResource('cities', CityController::class);

Route::apiResource('attractions', AttractionController::class);

Route::apiResource('reservations', ReservationController::class)->middleware(['auth']);


