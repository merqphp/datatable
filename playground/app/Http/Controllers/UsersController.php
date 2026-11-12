<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Merq\DataTable\Columns\Text;
use Merq\DataTable\Table;

class UsersController extends Controller
{
    public function __invoke(): Response
    {
        $users = User::query()->orderBy('id');

        return Inertia::render('Users', [
            'users' => Table::make($users)->withColumns([
                Text::make('Name')->name('name'),
                Text::make('Email')->name('email'),
            ]),
        ]);
    }
}
