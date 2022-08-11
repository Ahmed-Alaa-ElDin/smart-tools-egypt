<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::updateOrCreate([
            'id' => 1,
            'name' => 'Under Processing',
        ]);
        Status::updateOrCreate([
            'id' => 2,
            'name' => 'Waiting For Payment',
        ]);
        Status::updateOrCreate([
            'id' => 3,
            'name' => 'Created',
        ]);
        Status::updateOrCreate([
            'id' => 4,
            'name' => 'Preparing',
        ]);
        Status::updateOrCreate([
            'id' => 5,
            'name' => 'Under Reviewing',
        ]);
        Status::updateOrCreate([
            'id' => 6,
            'name' => 'Shipped',
        ]);
        Status::updateOrCreate([
            'id' => 7,
            'name' => 'Delivered',
        ]);
        Status::updateOrCreate([
            'id' => 8,
            'name' => 'Cancellation Requested',
        ]);
        Status::updateOrCreate([
            'id' => 9,
            'name' => 'Cancelled',
        ]);
        Status::updateOrCreate([
            'id' => 10,
            'name' => 'Under Editing',
        ]);
        Status::updateOrCreate([
            'id' => 11,
            'name' => 'Edit Requested',
        ]);
        Status::updateOrCreate([
            'id' => 12,
            'name' => 'Edit Approved',
        ]);
        Status::updateOrCreate([
            'id' => 13,
            'name' => 'Edit Rejected',
        ]);
    }
}
