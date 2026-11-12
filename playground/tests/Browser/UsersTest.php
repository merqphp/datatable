<?php

use App\Models\User;

it('renders shared backend data and refreshes it through Inertia', function (string $frontend) {
    User::factory()->create([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
    ]);

    $page = visit("/{$frontend}/users")
        ->assertSee('Ada Lovelace')
        ->assertSee('ada@example.com')
        ->assertDontSee('Grace Hopper');

    User::factory()->create([
        'name' => 'Grace Hopper',
        'email' => 'grace@example.com',
    ]);

    $page->click('Reload users')
        ->assertSee('Ada Lovelace')
        ->assertSee('Grace Hopper')
        ->assertSee('grace@example.com')
        ->assertPathIs("/{$frontend}/users")
        ->assertNoJavaScriptErrors();
})->with('frontends');
