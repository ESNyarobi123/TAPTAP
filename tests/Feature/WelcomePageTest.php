<?php

test('homepage renders welcome landing page', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('TipTap Rafiki', false);
    $response->assertSee('Review, pay and tip', false);
    $response->assertSee('in one platform', false);
    $response->assertSee('mobile payment or bank', false);
    $response->assertSee('Start free trial', false);
});

test('homepage includes phone conversation mockup', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('TipTap Grill', false);
    $response->assertSee('ReplyNumberToChoose', false);
});
