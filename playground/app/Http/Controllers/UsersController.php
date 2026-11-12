<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Users', [
            'users' => User::query()->orderBy('id')->get(['id', 'name', 'email']),
        ]);
    }
}
