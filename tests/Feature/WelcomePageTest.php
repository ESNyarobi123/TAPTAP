<?php

test('homepage renders welcome landing page', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('TipTap Bot', false);
    $response->assertSee('Order food', false);
    $response->assertSee('Start free trial', false);
});

test('homepage includes phone conversation mockup', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Grilled Fish', false);
    $response->assertSee('Order #1042', false);
});
