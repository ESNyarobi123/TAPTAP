<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('super_admin');
});

test('admin orders index returns success', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

    $response->assertOk();
    $response->assertViewHas('orders');
    $response->assertViewHas('restaurants');
});

test('admin orders index accepts status filter', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['status' => 'completed']));

    $response->assertOk();
});

test('admin orders export returns csv', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.orders.export'));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    $response->assertHeader('content-disposition');
});
