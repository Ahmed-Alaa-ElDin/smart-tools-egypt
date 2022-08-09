<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStatus::updateOrCreate([
            'id' => 1,
            'name' => 'Under Processing',
        ]);
        OrderStatus::updateOrCreate([
            'id' => 2,
            'name' => 'Pending',
        ]);
        OrderStatus::updateOrCreate([
            'id' => 3,
            'name' => 'Created',
        ]);
        OrderStatus::updateOrCreate([
            'id' => 4,
            'name' => 'Preparing',
        ]);
        OrderStatus::updateOrCreate([
            'id' => 5,
            'name' => 'Under Reviewing',
        ]);
        OrderStatus::updateOrCreate([
            'id' => 6,
            'name' => 'Shipped',
        ]);
        OrderStatus::updateOrCreate([
            'id' => 7,
            'name' => 'Delivered',
        ]);
        OrderStatus::updateOrCreate([
            'id' => 8,
            'name' => 'Cancellation Requested',
        ]);
        OrderStatus::updateOrCreate([
            'id' => 9,
            'name' => 'Cancelled',
        ]);
        OrderStatus::updateOrCreate([
            'id' => 10,
            'name' => 'Under Editing',
        ]);
        OrderStatus::updateOrCreate([
            'id' => 11,
            'name' => 'Edit Requested',
        ]);
        OrderStatus::updateOrCreate([
            'id' => 12,
            'name' => 'Edit Approved',
        ]);
        OrderStatus::updateOrCreate([
            'id' => 13,
            'name' => 'Edit Rejected',
        ]);
    }
}
