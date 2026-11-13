<?php

use App\Models\User;

it('paginates each source through Inertia and resets the page when its size changes', function (string $frontend, string $source) {
    foreach (range(1, 6) as $number) {
        User::factory()->create([
            'name' => sprintf('User %02d', $number),
            'email' => "user{$number}@example.com",
        ]);
    }

    $page = visit("/{$frontend}/users?source={$source}&keep=example")
        ->assertSee('User 01')
        ->assertSee('User 02')
        ->assertDontSee('User 03')
        ->assertCount('tbody > tr', 2)
        ->assertSee('Page 1 of 3')
        ->assertButtonDisabled('Previous');

    $page->script(<<<'JS'
        window.paginationDocument = document;
        document.addEventListener('inertia:before', (event) => {
            window.paginationReloadOnly = event.detail.visit.only;
        });
        JS);

    $page->click('Next')
        ->assertSee('User 03')
        ->assertSee('User 04')
        ->assertDontSee('User 01')
        ->assertCount('tbody > tr', 2)
        ->assertSee('Page 2 of 3')
        ->assertQueryStringHas('page', '2')
        ->assertQueryStringHas('limit', '2')
        ->assertQueryStringHas('source', $source)
        ->assertQueryStringHas('keep', 'example')
        ->assertScript('window.paginationDocument === document')
        ->assertScript('window.paginationReloadOnly', ['users']);

    $page->click('Next')
        ->assertSee('User 05')
        ->assertSee('User 06')
        ->assertSee('Page 3 of 3')
        ->assertButtonDisabled('Next');

    $page->back()
        ->assertSee('Page 2 of 3')
        ->assertSee('User 03')
        ->assertDontSee('User 05');

    $page->select('select[aria-label="Rows per page"]', '5')
        ->assertSee('Page 1 of 2')
        ->assertSee('User 01')
        ->assertSee('User 05')
        ->assertDontSee('User 06')
        ->assertCount('tbody > tr', 5)
        ->assertQueryStringMissing('page')
        ->assertQueryStringHas('limit', '5')
        ->assertQueryStringHas('source', $source)
        ->assertQueryStringHas('keep', 'example')
        ->assertButtonDisabled('Previous')
        ->assertScript('window.paginationDocument === document')
        ->assertNoJavaScriptErrors();

    $page->back()
        ->assertSee('Page 2 of 3')
        ->assertSee('User 03')
        ->assertCount('tbody > tr', 2)
        ->assertSelected('select[aria-label="Rows per page"]', '2')
        ->assertNoJavaScriptErrors();
})->with('frontends')->with(['eloquent', 'collection', 'array']);

it('resets only its own page and retains unrelated query data', function (string $frontend) {
    User::factory()->count(6)->sequence(
        fn ($sequence) => ['name' => 'User '.($sequence->index + 1)],
    )->create();

    $page = visit("/{$frontend}/users?prefixed=1&users_page=2&users_limit=2&page=7&limit=25&filters[status]=active")
        ->assertSee('Page 2 of 3')
        ->assertSee('User 3');

    $page->click('Next')
        ->assertSee('Page 3 of 3')
        ->assertQueryStringHas('users_page', '3')
        ->assertQueryStringHas('page', '7');

    $page->select('select[aria-label="Rows per page"]', '5')
        ->assertSee('Page 1 of 2')
        ->assertCount('tbody > tr', 5)
        ->assertQueryStringMissing('users_page')
        ->assertQueryStringHas('users_limit', '5')
        ->assertQueryStringHas('page', '7')
        ->assertQueryStringHas('limit', '25')
        ->assertScript('new URLSearchParams(location.search).get("filters[status]")', 'active')
        ->assertNoJavaScriptErrors();
})->with('frontends');

it('returns to an available page after its last row is deleted', function (string $frontend) {
    $users = User::factory()->count(5)->sequence(
        fn ($sequence) => ['name' => 'User '.($sequence->index + 1)],
    )->create();

    $page = visit("/{$frontend}/users?page=3&limit=2")
        ->assertSee('User 5')
        ->assertSee('Page 3 of 3')
        ->assertCount('tbody > tr', 1);

    $users->last()->delete();

    $page->click('Reload users')
        ->assertSee('User 3')
        ->assertSee('User 4')
        ->assertDontSee('User 5')
        ->assertSee('Page 2 of 2')
        ->assertCount('tbody > tr', 2)
        ->assertButtonDisabled('Next')
        ->assertNoJavaScriptErrors();
})->with('frontends');
