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
        OrderStatus::create([
            'id' => 1,
            'name' => 'Under Processing',
        ]);
        OrderStatus::create([
            'id' => 2,
            'name' => 'Pending',
        ]);
        OrderStatus::create([
            'id' => 3,
            'name' => 'Created',
        ]);
        OrderStatus::create([
            'id' => 4,
            'name' => 'Preparing',
        ]);
        OrderStatus::create([
            'id' => 5,
            'name' => 'Under Reviewing',
        ]);
        OrderStatus::create([
            'id' => 6,
            'name' => 'Shipped',
        ]);
        OrderStatus::create([
            'id' => 7,
            'name' => 'Delivered',
        ]);
        OrderStatus::create([
            'id' => 8,
            'name' => 'Cancelled',
        ]);
    }
}
