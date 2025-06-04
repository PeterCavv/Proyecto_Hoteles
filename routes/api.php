<?php

use App\Http\Controllers\Api\V1\CityController;
use Illuminate\Support\Facades\Route;

Route::apiResource('cities', CityController::class);
