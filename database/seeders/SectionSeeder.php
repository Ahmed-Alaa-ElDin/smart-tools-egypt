<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Section::create([
            'title' => [
                "en" => "Today's Deal",
                "ar" => "عرض اليوم"
            ],
            'type' => 0,
            'active' => 1,
            'rank' => 127,
            'today_deals' => 1
        ]);
    }
}
