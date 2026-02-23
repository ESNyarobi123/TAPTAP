<?php

use App\Models\Restaurant;
use App\Models\User;
use App\Models\WaiterSalaryPayment;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    $this->restaurant = Restaurant::create(['name' => 'Test Restaurant', 'location' => 'Dar', 'is_active' => true]);
    $this->manager = User::factory()->create(['restaurant_id' => $this->restaurant->id]);
    $this->manager->assignRole('manager');
    $this->waiter = User::factory()->create(['restaurant_id' => $this->restaurant->id]);
    $this->waiter->assignRole('waiter');
});

test('manager can access payroll index', function () {
    $response = $this->actingAs($this->manager)->get(route('manager.payroll.index'));

    $response->assertOk();
});

test('manager can access payroll history', function () {
    $response = $this->actingAs($this->manager)->get(route('manager.payroll.history'));

    $response->assertOk();
});

test('waiter can access salary slip index', function () {
    $response = $this->actingAs($this->waiter)->get(route('waiter.salary-slip.index'));

    $response->assertOk();
});

test('waiter can view own salary slip when payment exists', function () {
    WaiterSalaryPayment::create([
        'restaurant_id' => $this->restaurant->id,
        'user_id' => $this->waiter->id,
        'period_month' => now()->format('Y-m'),
        'basic_salary' => 500000,
        'allowances' => 0,
        'paye' => 0,
        'nssf' => 0,
        'net_pay' => 500000,
        'paid_at' => now(),
        'confirmed_by' => $this->manager->id,
    ]);

    $response = $this->actingAs($this->waiter)->get(route('waiter.salary-slip.show', ['period' => now()->format('Y-m')]));

    $response->assertOk();
});
