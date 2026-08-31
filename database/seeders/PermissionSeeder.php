<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     $permissions = [
    //         // Industries
    //         ['key' => 'industries.view', 'module' => 'Industries', 'label' => 'View Industries'],
    //         ['key' => 'industries.create', 'module' => 'Industries', 'label' => 'Create Industries'],
    //         ['key' => 'industries.edit', 'module' => 'Industries', 'label' => 'Edit Industries'],
    //         ['key' => 'industries.delete', 'module' => 'Industries', 'label' => 'Delete Industries'],

    //         // Services
    //         ['key' => 'services.view', 'module' => 'Services', 'label' => 'View Services'],
    //         ['key' => 'services.create', 'module' => 'Services', 'label' => 'Create Services'],
    //         ['key' => 'services.edit', 'module' => 'Services', 'label' => 'Edit Services'],
    //         ['key' => 'services.delete', 'module' => 'Services', 'label' => 'Delete Services'],

    //         // Workflow Templates
    //         ['key' => 'workflow_templates.view', 'module' => 'Workflow Templates', 'label' => 'View Workflow Templates'],
    //         ['key' => 'workflow_templates.create', 'module' => 'Workflow Templates', 'label' => 'Create Workflow Templates'],
    //         ['key' => 'workflow_templates.edit', 'module' => 'Workflow Templates', 'label' => 'Edit Workflow Templates'],
    //         ['key' => 'workflow_templates.delete', 'module' => 'Workflow Templates', 'label' => 'Delete Workflow Templates'],

    //         // Vendors
    //         ['key' => 'vendors.view', 'module' => 'Vendors', 'label' => 'View Vendors'],
    //         ['key' => 'vendors.approve', 'module' => 'Vendors', 'label' => 'Approve / Reject Vendors'],
    //         ['key' => 'vendors.edit', 'module' => 'Vendors', 'label' => 'Edit Vendor Details'],
    //         ['key' => 'vendors.suspend', 'module' => 'Vendors', 'label' => 'Suspend Vendors'],

    //         // Staff & Permissions
    //         ['key' => 'staff.view', 'module' => 'Staff & Permissions', 'label' => 'View Staff'],
    //         ['key' => 'staff.manage', 'module' => 'Staff & Permissions', 'label' => 'Create / Edit / Assign Permissions'],

    //         // Orders
    //         ['key' => 'orders.view', 'module' => 'Orders', 'label' => 'View All Orders'],

    //         // Payments
    //         ['key' => 'payments.view', 'module' => 'Payments', 'label' => 'View Payments'],

    //         // Reports
    //         ['key' => 'reports.view', 'module' => 'Reports', 'label' => 'View Reports'],

    //         // Settings
    //         ['key' => 'settings.manage', 'module' => 'Settings', 'label' => 'Manage Platform Settings'],
    //     ];

    //     foreach ($permissions as $permission) {
    //         Permission::updateOrCreate(
    //             ['key' => $permission['key']],
    //             $permission
    //         );
    //     }
    // }

    /**
     * Module => list of actions (must match the matrix shown on the Assign Permissions page).
     */
    protected array $matrix = [
        'Industries'          => ['View', 'Create', 'Edit', 'Delete'],
        'Services'            => ['View', 'Create', 'Edit', 'Delete'],
        'Workflow Templates'  => ['View', 'Create', 'Edit', 'Delete'],
        'Vendors'             => ['View', 'Approve', 'Edit', 'Suspend'],
        'Staff & Permissions' => ['View', 'Manage'],
        'Orders'              => ['View'],
        'Payments'            => ['View'],
        'Reports'             => ['View'],
        'Settings'            => ['Manage'],
    ];

    public function run(): void
    {
        foreach ($this->matrix as $module => $actions) {
            foreach ($actions as $action) {
                Permission::updateOrCreate(
                    ['key' => Str::slug($module, '_') . '.' . Str::lower($action)],
                    ['module' => $module, 'label' => $action]
                );
            }
        }
    }
}
