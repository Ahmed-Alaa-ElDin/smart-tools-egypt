<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer = Role::create([
            'name' => 'Customer'
        ]);

        $admin = Role::create([
            'name' => 'Admin'
        ]);

        $adminPermissions = [
            ['name' => "See Dashboard"],

            ['name' => "See All Users"],
            ['name' => "Add New User"],
            ['name' => "See User's Details"],
            ['name' => "Edit User"],
            ['name' => "Edit User's Role"],
            ['name' => "Soft Delete User"],
            ['name' => "Force Delete User"],
            ['name' => "Restore User"],

            ['name' => "See All Roles"],
            ['name' => "Add New Role"],
            ['name' => "See Role's Permissions"],
            ['name' => "See Role's Users"],
            ['name' => "Edit Role"],
            ['name' => "Delete Role"],

            ['name' => "See Delivery System"],
            ['name' => "Add Delivery"],
            ['name' => "Edit Delivery"],
            ['name' => "See Delivery's Details"],
            ['name' => "Add Zone"],
            ['name' => "Edit Zone"],
            ['name' => "Soft Delete Delivery"],
            ['name' => "Force Delete Delivery"],
            ['name' => "Restore Delivery"],
            ['name' => "Activate Delivery"],

        ];

        $admin->givePermissionTo($adminPermissions);

    }
}
