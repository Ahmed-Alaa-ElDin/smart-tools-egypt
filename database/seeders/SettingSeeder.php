<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate([
            "id" => 1
        ], [
            'back_pagination' => 10,
            'front_pagination' => 12,
            'points_conversion_rate' => 0.1,
            'points_expiry' => 90,
            'return_period' => 14,
            'last_box_name' => [
                'ar' => 'عرض آخر كرتونة',
                'en' => 'Last Box Offer',
            ],
            'last_box_quantity' => 3,
            'new_arrival_name' => [
                'ar' => 'عرض جديد الموقع',
                'en' => 'New Arrival Offer',
            ],
            'new_arrival_period' => 14,
            'max_price_offer_name' => [
                'ar' => 'منتجات أقل من 500 جنية',
                'en' => 'Products less than 500 EGP',
            ],
            'max_price_offer' => 500,
            'whatsapp_number' => '01010097248',
            'facebook_page_name' => 'SmartToolsEgypt',
        ]);
    }
}
