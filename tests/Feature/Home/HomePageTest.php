<?php

it('renders the landing page through Inertia', function () {
    $this->get('/')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Home'));
});
