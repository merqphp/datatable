<?php

use App\Models\User;

it('renders shared backend data and refreshes it through Inertia', function (string $frontend) {
    $ada = User::factory()->create([
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

    $ada->update(['email' => 'ada.lovelace@example.com']);

    $page->click('Reload users')
        ->assertSee('Ada Lovelace')
        ->assertSee('ada.lovelace@example.com')
        ->assertDontSee('ada@example.com')
        ->assertSee('Grace Hopper')
        ->assertSee('grace@example.com')
        ->assertPathIs("/{$frontend}/users")
        ->assertNoJavaScriptErrors();
})->with('frontends');

it('keeps column headings when rows are added and removed', function (string $frontend) {
    $page = visit("/{$frontend}/users")
        ->assertSee('Name')
        ->assertSee('Email')
        ->assertSee('No users yet.')
        ->assertMissing('tbody tr');

    $user = User::factory()->create([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
    ]);

    $page->click('Reload users')
        ->assertSee('Ada Lovelace')
        ->assertSee('ada@example.com')
        ->assertDontSee('No users yet.');

    $user->delete();

    $page->click('Reload users')
        ->assertSee('Name')
        ->assertSee('Email')
        ->assertSee('No users yet.')
        ->assertDontSee('Ada Lovelace')
        ->assertMissing('tbody tr')
        ->assertNoJavaScriptErrors();
})->with('frontends');
