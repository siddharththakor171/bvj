<?php

test('the application root redirects to dashboard', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('admin.dashboard'));
});
