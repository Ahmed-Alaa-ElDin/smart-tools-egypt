<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Create Users
        $ahmed = User::create([
            'f_name' => ['en' => 'Ahmed', 'ar' => 'أحمد'],
            'l_name' => ['en' => 'Alaa', 'ar' => 'علاء'],
            'email' => 'ahmedalaaaldin100@gmail.com',
            'phone' => '01111339306',
            'last_visit_at' => now(),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'password' => Hash::make('123456789'),
        ]);
        // newItem->name = ['en' => 'myName', 'nl' => 'Naam in het Nederlands'];
        $admin = User::create([
            'f_name' => 'Admin',
            'email' => 'admin@gmail.com',
            'last_visit_at' => now(),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'password' => Hash::make('123456789'),
        ]);

        $user = User::create([
            'f_name' => 'User',
            'email' => 'user@gmail.com',
            'last_visit_at' => now(),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'password' => Hash::make('123456789'),
        ]);

        // Assign Roles
        $ahmed->assignRole('Admin');
        $admin->assignRole('Admin');
        $user->assignRole('User');
    }
}
