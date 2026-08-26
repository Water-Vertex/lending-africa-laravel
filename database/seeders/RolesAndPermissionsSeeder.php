<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'web';

        // ========== APKI LENDING APP KE ASAL MODULES ==========
        $modules = [
            'dashboard',
            'customers',
            'loan_applications',
            'loan_products',
            'loan_approvals',
            'loan_disbursements',
            'loan_installments',
            'loan_payments',
            'agreements',
            'collaterals',
            'collateral_types',
            'banks',
            'staff',
            'users',
            'roles',
            'permissions',
            'faqs',
            'policies',
            'contacts',
            'co_signers',
            'loan_inquiries',
            'reports',
            'settings',
        ];

        // Har module ke liye view/create/edit/delete permissions
        foreach ($modules as $module) {
            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}_{$module}",
                    'guard_name' => $guard,
                ]);
            }
        }

        // ========== ROLES ==========
        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => $guard,
        ]);

        Role::firstOrCreate(['name' => 'manager', 'guard_name' => $guard]);
        Role::firstOrCreate(['name' => 'accounts', 'guard_name' => $guard]);
        Role::firstOrCreate(['name' => 'executive', 'guard_name' => $guard]);

        // Admin ko sab permissions mil jayen
        $admin->syncPermissions(Permission::all());
        $this->command->info('✅ Admin got ' . Permission::count() . ' permissions');

        // Existing admin user ko role assign karo
        $adminUser = User::where('email', 'admin@dotbitz.com')->first();
        if ($adminUser) {
            $adminUser->role_id = $admin->id;
            $adminUser->save();
            $this->command->info('✅ Existing admin assigned admin role');
        }
    }
}