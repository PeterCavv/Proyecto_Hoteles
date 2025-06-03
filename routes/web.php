<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
