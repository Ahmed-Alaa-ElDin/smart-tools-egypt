<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //1 => cash, 2 => card, 3 => installments, 4 => vodafone cash, 10 => wallet, 11 => points
        $methods = [
            [
                'id' => 1,
                'name' => ['en' => 'Cash', 'ar' => 'كاش']
            ],
            [
                'id' => 2,
                'name' => ['en' => 'Card', 'ar' => 'بطاقة']
            ],
            [
                'id' => 3,
                'name' => ['en' => 'Installments', 'ar' => 'تقسيط']
            ],
            [
                'id' => 4,
                'name' => ['en' => 'Vodafone Cash', 'ar' => 'فودافون كاش']
            ],
            [
                'id' => 10,
                'name' => ['en' => 'Wallet', 'ar' => 'محفظة']
            ],
            [
                'id' => 11,
                'name' => ['en' => 'Points', 'ar' => 'نقاط']
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }
    }
}
