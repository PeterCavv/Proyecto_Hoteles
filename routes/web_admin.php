<?php

use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index'])->name('users.index');

Route::get('impersonate/{user}', [ImpersonationController::class, 'start'])
    ->middleware('can:impersonate,user')
    ->name('impersonate.start');
