<?php

use App\Models\Feedback;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('super_admin');

    $this->manager = User::factory()->create();
    $this->manager->assignRole('manager');

    $this->restaurant = Restaurant::create([
        'name' => 'Cape Kitchen',
        'location' => 'Cape Town',
        'phone' => '0210000000',
        'is_active' => true,
    ]);

    $this->manager->update(['restaurant_id' => $this->restaurant->id]);
});

test('super admin can view restaurants index', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.restaurants.index'));

    $response->assertOk();
    $response->assertViewIs('admin.restaurants.index');
    $response->assertSee('Cape Kitchen');
    $response->assertSee('Restaurant Partners');
});

test('restaurants index can be filtered by search and status', function () {
    Restaurant::create([
        'name' => 'Inactive Spot',
        'location' => 'Durban',
        'phone' => '0310000000',
        'is_active' => false,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.restaurants.index', ['q' => 'Cape', 'status' => 'active']))
        ->assertOk()
        ->assertSee('Cape Kitchen')
        ->assertDontSee('Inactive Spot');

    $this->actingAs($this->admin)
        ->get(route('admin.restaurants.index', ['status' => 'inactive']))
        ->assertOk()
        ->assertSee('Inactive Spot')
        ->assertDontSee('Cape Kitchen');
});

test('super admin can create restaurant with manager', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.restaurants.store'), [
            'restaurant_name' => 'New Bistro',
            'location' => 'Pretoria',
            'phone' => '0120000000',
            'manager_name' => 'Bistro Manager',
            'manager_email' => 'manager@bistro.test',
            'manager_password' => 'password123',
            'manager_password_confirmation' => 'password123',
            'is_active' => '1',
        ])
        ->assertRedirect();

    $restaurant = Restaurant::query()->where('name', 'New Bistro')->first();
    expect($restaurant)->not->toBeNull();
    expect((bool) $restaurant->is_active)->toBeTrue();

    $manager = User::query()->where('email', 'manager@bistro.test')->first();
    expect($manager)->not->toBeNull();
    expect($manager->restaurant_id)->toBe($restaurant->id);
    expect($manager->hasRole('manager'))->toBeTrue();
});

test('restaurant create validates duplicate manager email', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.restaurants.store'), [
            'restaurant_name' => 'Another Place',
            'location' => 'Durban',
            'phone' => '0310000001',
            'manager_name' => 'Dup Manager',
            'manager_email' => $this->manager->email,
            'manager_password' => 'password123',
            'manager_password_confirmation' => 'password123',
        ])
        ->assertSessionHasErrors('manager_email');
});

test('restaurant show displays real overview stats', function () {
    $waiter = User::factory()->create(['restaurant_id' => $this->restaurant->id]);
    $waiter->assignRole('waiter');

    $order = Order::create([
        'restaurant_id' => $this->restaurant->id,
        'table_number' => 'T2',
        'status' => 'completed',
        'total_amount' => 12000,
    ]);

    Payment::create([
        'order_id' => $order->id,
        'restaurant_id' => $this->restaurant->id,
        'amount' => 12000,
        'method' => 'cash',
        'status' => 'paid',
    ]);

    Feedback::withoutGlobalScopes()->create([
        'restaurant_id' => $this->restaurant->id,
        'order_id' => $order->id,
        'waiter_id' => $waiter->id,
        'rating' => 5,
        'comment' => 'Great service',
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.restaurants.show', $this->restaurant));

    $response->assertOk();
    $response->assertViewIs('admin.restaurants.show');
    $response->assertViewHas('overview', fn (array $overview) => $overview['total_earnings'] === 12000.0
        && $overview['total_orders'] === 1
        && $overview['avg_rating'] === 5.0);
    $response->assertSee('12,000');
    $response->assertSee('5');

    $this->actingAs($this->admin)
        ->get(route('admin.restaurants.show', ['restaurant' => $this->restaurant, 'tab' => 'staff']))
        ->assertOk()
        ->assertSee($this->manager->email);
});

test('restaurant show supports tab navigation', function () {
    $order = Order::create([
        'restaurant_id' => $this->restaurant->id,
        'table_number' => 'T5',
        'status' => 'pending',
        'total_amount' => 5000,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.restaurants.show', ['restaurant' => $this->restaurant, 'tab' => 'orders']))
        ->assertOk()
        ->assertSee('Full history')
        ->assertSee('Table T5')
        ->assertSee((string) $order->id);

    $this->actingAs($this->admin)
        ->get(route('admin.restaurants.show', ['restaurant' => $this->restaurant, 'tab' => 'staff']))
        ->assertOk()
        ->assertSee('Impersonate')
        ->assertSee($this->manager->email);
});

test('super admin can update a restaurant', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.restaurants.update', $this->restaurant), [
            'name' => 'Updated Kitchen',
            'location' => 'Stellenbosch',
            'phone' => '0211111111',
        ])
        ->assertRedirect(route('admin.restaurants.show', $this->restaurant));

    $this->restaurant->refresh();
    expect($this->restaurant->name)->toBe('Updated Kitchen');
    expect($this->restaurant->location)->toBe('Stellenbosch');
});

test('manager cannot access admin restaurants', function () {
    $this->actingAs($this->manager)
        ->get(route('admin.restaurants.index'))
        ->assertForbidden();

    $this->actingAs($this->manager)
        ->get(route('admin.restaurants.show', $this->restaurant))
        ->assertForbidden();
});

test('guest is redirected from admin restaurants', function () {
    $this->get(route('admin.restaurants.index'))
        ->assertRedirect(route('login'));
});
