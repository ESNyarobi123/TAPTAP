<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('super_admin');
});

test('admin payments index returns success', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.payments.index'));

    $response->assertOk();
    $response->assertViewHas('payments');
});

test('admin payments export returns csv', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.payments.export'));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    $response->assertHeader('content-disposition');
});
