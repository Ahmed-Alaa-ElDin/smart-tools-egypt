<?php

namespace Database\Seeders;

use App\Models\Governorate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Governorate::create([
            'id' => 1,
            'name' => ['en' => 'Cairo', 'ar' => 'القاهرة'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 2,
            'name' => ['en' => 'Giza', 'ar' => 'الجيزة'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 3,
            'name' => ['en' => 'Alexandria', 'ar' => 'الأسكندرية'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 4,
            'name' => ['en' => 'Dakahlia', 'ar' => 'الدقهلية'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 5,
            'name' => ['en' => 'Red Sea', 'ar' => 'البحر الأحمر'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 6,
            'name' => ['en' => 'Beheira', 'ar' => 'البحيرة'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 7,
            'name' => ['en' => 'Fayoum', 'ar' => 'الفيوم'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 8,
            'name' => ['en' => 'Gharbiya', 'ar' => 'الغربية'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 9,
            'name' => ['en' => 'Ismailia', 'ar' => 'الإسماعلية'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 10,
            'name' => ['en' => 'Menofia', 'ar' => 'المنوفية'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 11,
            'name' => ['en' => 'Minya', 'ar' => 'المنيا'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 12,
            'name' => ['en' => 'Qaliubiya', 'ar' => 'القليوبية'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 13,
            'name' => ['en' => 'New Valley', 'ar' => 'الوادي الجديد'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 14,
            'name' => ['en' => 'Suez', 'ar' => 'السويس'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 15,
            'name' => ['en' => 'Aswan', 'ar' => 'اسوان'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 16,
            'name' => ['en' => 'Assiut', 'ar' => 'اسيوط'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 17,
            'name' => ['en' => 'Beni Suef', 'ar' => 'بني سويف'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 18,
            'name' => ['en' => 'Port Said', 'ar' => 'بورسعيد'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 19,
            'name' => ['en' => 'Damietta', 'ar' => 'دمياط'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 20,
            'name' => ['en' => 'Sharkia', 'ar' => 'الشرقية'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 21,
            'name' => ['en' => 'South Sinai', 'ar' => 'جنوب سيناء'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 22,
            'name' => ['en' => 'Kafr Al sheikh', 'ar' => 'كفر الشيخ'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 23,
            'name' => ['en' => 'Matrouh', 'ar' => 'مطروح'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 24,
            'name' => ['en' => 'Luxor', 'ar' => 'الأقصر'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 25,
            'name' => ['en' => 'Qena', 'ar' => 'قنا'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 26,
            'name' => ['en' => 'North Sinai', 'ar' => 'شمال سيناء'],
            'country_id' => 1
        ]);

        Governorate::create([
            'id' => 27,
            'name' => ['en' => 'Sohag', 'ar' => 'سوهاج'],
            'country_id' => 1
        ]);

    }
}
