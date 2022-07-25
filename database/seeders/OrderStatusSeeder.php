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
            'name' => 'under_processing',
        ]);
        OrderStatus::create([
            'id' => 2,
            'name' => 'pending',
        ]);
        OrderStatus::create([
            'id' => 3,
            'name' => 'created',
        ]);
        OrderStatus::create([
            'id' => 4,
            'name' => 'preparing',
        ]);
        OrderStatus::create([
            'id' => 5,
            'name' => 'under_reviewing',
        ]);
        OrderStatus::create([
            'id' => 6,
            'name' => 'shipped',
        ]);
        OrderStatus::create([
            'id' => 7,
            'name' => 'delivered',
        ]);
        OrderStatus::create([
            'id' => 8,
            'name' => 'cancelled',
        ]);
    }
}
