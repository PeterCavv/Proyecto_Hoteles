<?php

use Inertia\Inertia;

Route::get('/index', function () {
    return Inertia::render('Common/Index');
})->name('common.index');
