<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Tables\Columns\Email;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Merq\DataTable\Columns\Text;
use Merq\DataTable\Table;

class UsersController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $query = User::query()->orderBy('id');
        $users = match ($request->query('source')) {
            'array' => $query->get()->keyBy('id')->toArray(),
            'collection' => $query->get(),
            default => $query,
        };

        return Inertia::render('Users', [
            'users' => Table::make($users)->withColumns([
                Text::make('Name')->name('name'),
                Email::make('Email')->name('email'),
            ])->perPageOptions([2, 5, 25])
                ->withQueryPrefix($request->boolean('prefixed') ? 'users_' : null),
        ]);
    }
}
