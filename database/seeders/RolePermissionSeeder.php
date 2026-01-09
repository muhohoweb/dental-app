<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache for roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Define permissions grouped by module
        $permissions = [
            // Order Management
            'order-management' => [
                'view-all-orders',
                'create-orders',
                'edit-orders',
                'delete-orders',
                'assign-orders',
                'view-own-orders',
                'update-order-status',
            ],

            // Service Management
            'service-management' => [
                'view-services',
                'create-services',
                'edit-services',
                'delete-services',
                'manage-pricing',
            ],

            // Instruction & Case Management
            'instruction-management' => [
                'view-instructions',
                'create-instructions',
                'edit-instructions',
                'view-assigned-cases',
                'update-case-status',
            ],

            // SOP Management
            'sop-management' => [
                'view-sops',
                'create-sops',
                'edit-sops',
                'delete-sops',
                'manage-sop-versions',
            ],

            // Technician Management
            'technician-management' => [
                'view-technicians',
                'create-technicians',
                'edit-technicians',
                'delete-technicians',
                'assign-tasks',
                'view-technician-workload',
            ],

            // Invoice Management
            'invoice-management' => [
                'view-all-invoices',
                'view-own-invoices',
                'generate-invoices',
                'edit-invoices',
                'download-invoices',
            ],

            // Payment Management
            'payment-management' => [
                'view-all-payments',
                'view-own-payments',
                'verify-payments',
                'approve-payments',
                'manage-payment-methods',
            ],

            // Client Management
            'client-management' => [
                'view-all-clients',
                'create-clients',
                'edit-clients',
                'delete-clients',
                'view-client-history',
            ],

            // File Management
            'file-management' => [
                'upload-files',
                'view-files',
                'download-files',
                'delete-files',
            ],

            // Communication
            'communication' => [
                'send-messages',
                'receive-messages',
                'create-notifications',
            ],

            // Reports & Analytics
            'reporting' => [
                'view-reports',
                'generate-reports',
                'view-analytics',
                'export-data',
            ],

            // System Administration
            'system-admin' => [
                'manage-roles',
                'manage-permissions',
                'view-system-logs',
                'manage-settings',
                'backup-data',
            ],
        ];

        // Define roles based on dental lab requirements
        $roles = [
            // Admin (Lab Owner / Manager) - Full system access
            'admin' => [
                'order-management',
                'service-management',
                'instruction-management',
                'sop-management',
                'technician-management',
                'invoice-management',
                'payment-management',
                'client-management',
                'file-management',
                'communication',
                'reporting',
                'system-admin',
            ],

            // Lab Technician - Limited to assigned work
            'technician' => [
                'view-assigned-cases',
                'update-case-status',
                'view-instructions',
                'view-sops',
                'view-files',
                'upload-files',
                'send-messages',
                'receive-messages',
            ],

            // Client (Dentist / Clinic) - Order and track
            'client' => [
                'create-orders',
                'view-own-orders',
                'view-own-invoices',
                'download-invoices',
                'view-own-payments',
                'upload-files',
                'view-files',
                'send-messages',
                'receive-messages',
                'view-services',
            ],
        ];

        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Clear existing data
            DB::table('model_has_permissions')->truncate();
            DB::table('model_has_roles')->truncate();
            DB::table('role_has_permissions')->truncate();
            Permission::truncate();
            Role::truncate();

            // Create all permissions
            $allPermissions = [];
            foreach ($permissions as $group => $permissionList) {
                foreach ($permissionList as $permission) {
                    $allPermissions[] = $permission;
                }
            }

            // Remove duplicates and create permissions
            $uniquePermissions = array_unique($allPermissions);
            foreach ($uniquePermissions as $permissionName) {
                Permission::create([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);
            }

            // Create roles and assign permissions
            foreach ($roles as $roleName => $rolePermissions) {
                $role = Role::create([
                    'name' => $roleName,
                    'guard_name' => 'web',
                ]);

                $permissionsToAssign = [];

                foreach ($rolePermissions as $permissionKey) {
                    if (isset($permissions[$permissionKey])) {
                        // It's a permission group
                        $permissionsToAssign = array_merge($permissionsToAssign, $permissions[$permissionKey]);
                    } else {
                        // It's an individual permission
                        $permissionsToAssign[] = $permissionKey;
                    }
                }

                // Remove duplicates and assign permissions
                $permissionsToAssign = array_unique($permissionsToAssign);
                foreach ($permissionsToAssign as $permissionName) {
                    $permission = Permission::where('name', $permissionName)->first();
                    if ($permission) {
                        $role->givePermissionTo($permission);
                    }
                }
            }

            // Truncate users table and create dummy users
            User::truncate();

            // Create Admin User
            $adminUser = User::create([
                'name' => 'Lab Manager',
                'email' => 'admin@dentallab.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]);
            $adminUser->assignRole('admin');
            $this->command->info("Admin user created: {$adminUser->email} | Password: password123");

            // Create Technician User
            $technicianUser = User::create([
                'name' => 'John Technician',
                'email' => 'technician@dentallab.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]);
            $technicianUser->assignRole('technician');
            $this->command->info("Technician user created: {$technicianUser->email} | Password: password123");

            // Create Client User (Dentist)
            $clientUser = User::create([
                'name' => 'Dr. Sarah Smith',
                'email' => 'client@dentist.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]);
            $clientUser->assignRole('client');
            $this->command->info("Client user created: {$clientUser->email} | Password: password123");

            $this->command->info('');
            $this->command->info('===========================================');
            $this->command->info('Dental lab roles and permissions seeded successfully!');
            $this->command->info('Created roles: admin, technician, client');
            $this->command->info('===========================================');
            $this->command->info('Test Credentials:');
            $this->command->info('1. Admin: admin@dentallab.com / password123');
            $this->command->info('2. Technician: technician@dentallab.com / password123');
            $this->command->info('3. Client: client@dentist.com / password123');
            $this->command->info('===========================================');

        } catch (\Exception $e) {
            $this->command->error('Error seeding roles and permissions: ' . $e->getMessage());
            throw $e;
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Clear cache after seeding
        app()['cache']->forget('spatie.permission.cache');
    }
}
