<?php

test('returns a successful response trying to get login', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});
