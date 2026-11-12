<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/', '/react/users');

Route::get('/{frontend}/users', UsersController::class)
    ->whereIn('frontend', ['react', 'vue'])
    ->name('users.index');

Route::get('/{frontend}/composition', fn () => Inertia::render('Composition'))
    ->whereIn('frontend', ['react', 'vue'])
    ->name('composition');
