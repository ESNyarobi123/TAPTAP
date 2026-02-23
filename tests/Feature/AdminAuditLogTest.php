<?php

use App\Models\AdminActivityLog;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('super_admin');
});

test('approving withdrawal creates audit log', function () {
    $restaurant = Restaurant::create(['name' => 'Test Restaurant', 'location' => 'Dar', 'is_active' => true]);
    $withdrawal = Withdrawal::create([
        'restaurant_id' => $restaurant->id,
        'amount' => 100000,
        'payment_method' => 'bank',
        'payment_details' => 'Account 123',
        'status' => 'pending',
    ]);

    $this->actingAs($this->admin)->post(route('admin.withdrawals.approve', $withdrawal), [
        'admin_note' => 'Paid via bank transfer',
        '_token' => csrf_token(),
    ])->assertRedirect();

    $withdrawal->refresh();
    expect($withdrawal->status)->toBe('approved');

    $log = AdminActivityLog::where('action', 'withdrawal.approved')->where('subject_id', $withdrawal->id)->first();
    expect($log)->not->toBeNull();
    expect($log->user_id)->toBe($this->admin->id);
    expect($log->old_values)->toHaveKey('status');
    expect($log->meta)->toHaveKey('admin_note');
});

test('toggling restaurant status creates audit log', function () {
    $restaurant = Restaurant::create(['name' => 'Test Restaurant', 'location' => 'Dar', 'is_active' => true]);

    $this->actingAs($this->admin)->post(route('admin.restaurants.toggle-status', $restaurant), [
        '_token' => csrf_token(),
    ])->assertRedirect();

    $restaurant->refresh();
    expect((bool) $restaurant->is_active)->toBeFalse();

    $log = AdminActivityLog::where('action', 'restaurant.toggle_status')->where('subject_id', $restaurant->id)->first();
    expect($log)->not->toBeNull();
    expect((bool) ($log->old_values['is_active'] ?? false))->toBeTrue();
    expect((bool) ($log->new_values['is_active'] ?? true))->toBeFalse();
});
