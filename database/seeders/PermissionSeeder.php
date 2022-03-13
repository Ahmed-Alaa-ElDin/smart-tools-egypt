<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // Dashboard
            ['name' => "See Dashboard"],

            // Users
            ['name' => "See All Users"],
            ['name' => "Add New User"],
            ['name' => "See User's Details"],
            ['name' => "Edit User"],
            ['name' => "Edit User's Role"],
            ['name' => "Soft Delete User"],
            ['name' => "Force Delete User"],
            ['name' => "Restore User"],

            // Roles
            ['name' => "See All Roles"],
            ['name' => "Add New Role"],
            ['name' => "See Role's Permissions"],
            ['name' => "See Role's Users"],
            ['name' => "Edit Role"],
            ['name' => "Delete Role"],

            // Delivery System
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

            // Country
            ['name' => "See All Countries"],
            ['name' => "Add Country"],
            ['name' => "Edit Country"],
            ['name' => "Soft Delete Country"],
            ['name' => "Force Delete Country"],
            ['name' => "Restore Country"],

        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
