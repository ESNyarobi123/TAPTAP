<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            // Super Admin
            'manage_restaurants', 'manage_system_users', 'view_all_reports', 'manage_system_settings',
            // Manager
            'manage_menu', 'manage_waiters', 'view_orders', 'update_orders', 'view_payments', 'confirm_payments', 'view_feedback', 'view_reports_restaurant',
            // Waiter
            'update_orders_status', 'view_tips',
            // Bot
            'api_restaurant_search', 'api_get_menu', 'api_create_order', 'api_create_payment', 'api_check_payment', 'api_submit_feedback', 'api_submit_tip'
        ];

        foreach ($permissions as $permission) {
            try {
                Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            } catch (\Exception $e) {
                $this->command->error("Failed to create permission: $permission - " . $e->getMessage());
            }
        }

        // Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(['manage_restaurants', 'manage_system_users', 'view_all_reports', 'manage_system_settings']);

        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions(['manage_menu', 'manage_waiters', 'view_orders', 'update_orders', 'view_payments', 'confirm_payments', 'view_feedback', 'view_reports_restaurant']);

        $waiter = Role::firstOrCreate(['name' => 'waiter', 'guard_name' => 'web']);
        $waiter->syncPermissions(['view_orders', 'update_orders_status', 'view_tips']);

        $botService = Role::firstOrCreate(['name' => 'bot_service', 'guard_name' => 'web']);
        $botService->syncPermissions(['api_restaurant_search', 'api_get_menu', 'api_create_order', 'api_create_payment', 'api_check_payment', 'api_submit_feedback', 'api_submit_tip']);
    }
}
