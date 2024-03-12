<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //1 => Pending, 2 => Paid, 3 => Payment Failed, 4 => Refund Pending, 5 => Refunded, 6 => Refund Failed (With translations to Arabic) 
        $statuses = [
            [
                'id' => 1,
                'name' => ['en' => 'Pending', 'ar' => 'قيد الانتظار']
            ],
            [
                'id' => 2,
                'name' => ['en' => 'Paid', 'ar' => 'تم الدفع']
            ],
            [
                'id' => 3,
                'name' => ['en' => 'Payment Failed', 'ar' => 'فشل الدفع']
            ],
            [
                'id' => 4,
                'name' => ['en' => 'Refund Pending', 'ar' => 'قيد الاسترجاع']
            ],
            [
                'id' => 5,
                'name' => ['en' => 'Refunded', 'ar' => 'تم الاسترجاع']
            ],
            [
                'id' => 6,
                'name' => ['en' => 'Refund Failed', 'ar' => 'فشل الاسترجاع']
            ],
        ];

        foreach ($statuses as $status) {
            PaymentStatus::create($status);
        }
    }
}
