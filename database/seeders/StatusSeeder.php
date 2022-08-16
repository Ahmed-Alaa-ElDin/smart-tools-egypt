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
        // Status::updateOrCreate([
        //     'id' => 7,
        //     'name' => 'Delivered',
        // ]);
        Status::updateOrCreate([
            'id' => 8,
            'name' => 'Cancellation Requested',
        ]);
        Status::updateOrCreate([
            'id' => 9,
            'name' => 'Cancelled',
        ]);
        Status::updateOrCreate([
            'id' => 15,
            'name' => 'Under Editing',
        ]);
        Status::updateOrCreate([
            'id' => 16,
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
        Status::updateOrCreate([
            'id' => 14,
            'name' => 'Waiting For Refund',
        ]);

        // Bosta Statuses
        Status::updateOrCreate([
            'id' => 10,
            'name' => 'Pickup requested',
        ]);
        Status::updateOrCreate([
            'id' => 11,
            'name' => 'Waiting for route',
        ]);
        Status::updateOrCreate([
            'id' => 20,
            'name' => 'Route Assigned',
        ]);
        Status::updateOrCreate([
            'id' => 21,
            'name' => 'Picked up from business',
        ]);
        Status::updateOrCreate([
            'id' => 22,
            'name' => 'Picking up from consignee',
        ]);
        Status::updateOrCreate([
            'id' => 23,
            'name' => 'Picked up from consignee',
        ]);
        Status::updateOrCreate([
            'id' => 24,
            'name' => 'Received at warehouse',
        ]);
        Status::updateOrCreate([
            'id' => 30,
            'name' => 'In transit between Hubs',
        ]);
        Status::updateOrCreate([
            'id' => 40,
            'name' => 'Picking up',
        ]);
        Status::updateOrCreate([
            'id' => 41,
            'name' => 'Picked up',
        ]);
        Status::updateOrCreate([
            'id' => 42,
            'name' => 'Pending Customer Signature',
        ]);
        Status::updateOrCreate([
            'id' => 43,
            'name' => 'Debriefed Successfully',
        ]);
        Status::updateOrCreate([
            'id' => 45,
            'name' => 'Delivered',
        ]);
        Status::updateOrCreate([
            'id' => 46,
            'name' => 'Returned to business',
        ]);
        Status::updateOrCreate([
            'id' => 47,
            'name' => 'Exception',
        ]);
        Status::updateOrCreate([
            'id' => 48,
            'name' => 'Terminated',
        ]);
        Status::updateOrCreate([
            'id' => 49,
            'name' => 'Canceled (uncovered area)',
        ]);
        Status::updateOrCreate([
            'id' => 50,
            'name' => 'Collection Failed',
        ]);
        Status::updateOrCreate([
            'id' => 100,
            'name' => 'Lost',
        ]);
        Status::updateOrCreate([
            'id' => 101,
            'name' => 'Damaged',
        ]);
        Status::updateOrCreate([
            'id' => 102,
            'name' => 'Investigation',
        ]);
        Status::updateOrCreate([
            'id' => 103,
            'name' => 'Awaiting your action',
        ]);
        Status::updateOrCreate([
            'id' => 104,
            'name' => 'Archived',
        ]);
        Status::updateOrCreate([
            'id' => 105,
            'name' => 'On hold',
        ]);

    }
}
