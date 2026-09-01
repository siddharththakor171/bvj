<?php

test('the application root renders customer home page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('B V JEWELLERS');
});
