<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/react/users');

Route::get('/{frontend}/users', UsersController::class)
    ->whereIn('frontend', ['react', 'vue'])
    ->name('users.index');
