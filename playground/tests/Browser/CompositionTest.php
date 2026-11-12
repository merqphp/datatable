<?php

it('composes custom elements while preserving content, attributes, events and refs', function (string $frontend) {
    $page = visit("/{$frontend}/composition")
        ->assertSee('Name')
        ->assertSee('Ada Lovelace')
        ->assertCount('thead > tr > th[data-composed="heading"]', 1)
        ->assertCount('tbody > tr[data-composed="row"] > td[data-composed="cell"]', 1)
        ->assertAttribute('[data-composed="heading"]', 'scope', 'col')
        ->assertAttributeContains('[data-composed="cell"]', 'class', 'px-4')
        ->assertAttributeContains('[data-composed="cell"]', 'class', 'font-medium')
        ->assertAttributeMissing('[data-composed="cell"]', 'cell')
        ->assertAttributeMissing('tr[data-composed="row"]', 'row')
        ->assertPresent('td > strong')
        ->click('Ada Lovelace')
        ->assertSee('Parent clicks: 1; child clicks: 1')
        ->click('Focus cell')
        ->assertSee('Refs point to cell')
        ->assertNoJavaScriptErrors();

    expect($page->script('document.activeElement.dataset.composed'))->toBe('cell');
})->with('frontends');
