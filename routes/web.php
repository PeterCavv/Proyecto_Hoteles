<?php

use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
require base_path('routes/web_public.php');

// Common routes
Route::middleware(['auth'])->group(function () {
    require base_path('routes/web_common.php');
});

// Admin routes
Route::middleware(['auth', 'role:'.RoleEnum::ADMIN->value])->group(function () {
    require base_path('routes/web_admin.php');
});

// Hotel routes
Route::middleware(['auth'])->group(function (){
    require base_path('routes/web_hotel.php');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
