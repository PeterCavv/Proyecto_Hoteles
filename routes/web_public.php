<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/index');
    }

    return Inertia::render('Welcome');
})->name('welcome');
