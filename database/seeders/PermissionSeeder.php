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
            ['name' => "Add User"],
            ['name' => "See User's Details"],
            ['name' => "Edit User"],
            ['name' => "Edit User's Role"],
            ['name' => "Deleted User"],
            ['name' => "Force Delete User"],
            ['name' => "Restore User"],

            // Roles
            ['name' => "See All Roles"],
            ['name' => "Add Role"],
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
            ['name' => "Deleted Delivery"],
            ['name' => "Force Delete Delivery"],
            ['name' => "Restore Delivery"],
            ['name' => "Activate Delivery"],

            // Country
            ['name' => "See All Countries"],
            ['name' => "Add Country"],
            ['name' => "Edit Country"],
            ['name' => "Deleted Country"],
            ['name' => "Force Delete Country"],
            ['name' => "Restore Country"],

            // Governorate
            ['name' => "See All Governorates"],
            ['name' => "Add Governorate"],
            ['name' => "Edit Governorate"],
            ['name' => "Deleted Governorate"],
            ['name' => "Force Delete Governorate"],
            ['name' => "Restore Governorate"],

            // City
            ['name' => "See All Cities"],
            ['name' => "Add City"],
            ['name' => "Edit City"],
            ['name' => "Deleted City"],
            ['name' => "Force Delete City"],
            ['name' => "Restore City"],

        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
