<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Supercategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Super Categories
        $clothes = Supercategory::create([
            'name' => ['en' => 'Clothes', 'ar' => 'ملابس'],
        ]);
        $cars = Supercategory::create([
            'name' => ['en' => 'Cars', 'ar' => 'سيارات'],
        ]);
        $homes = Supercategory::create([
            'name' => ['en' => 'Homes', 'ar' => 'منازل'],
        ]);
        $electronics = Supercategory::create([
            'name' => ['en' => 'Electronics', 'ar' => 'إلكترونيات'],
        ]);

        // -------------------------------------
        // -------------------------------------
        // -------------------------------------
        // Categories
        $watches = Category::create([
            'name' => ['en' => 'Watches', 'ar' => 'ساعات'],
            'supercategory_id' => $clothes->id,
        ]);

        $women = Category::create([
            'name' => ['en' => 'Women', 'ar' => 'ملابس نساء'],
            'supercategory_id' => $clothes->id,
        ]);

        $sports = Category::create([
            'name' => ['en' => 'Sports', 'ar' => 'ملابس رياضية'],
            'supercategory_id' => $clothes->id,
        ]);

        // -------------------------------------

        $automatic = Category::create([
            'name' => ['en' => 'Automatic', 'ar' => 'آلية'],
            'supercategory_id' => $cars->id,
        ]);

        $manual = Category::create([
            'name' => ['en' => 'Manual', 'ar' => 'يدوية'],
            'supercategory_id' => $cars->id,
        ]);

        // -------------------------------------

        $kitchen = Category::create([
            'name' => ['en' => 'Kitchen', 'ar' => 'المطابخ'],
            'supercategory_id' => $homes->id,
        ]);

        $bedrooms = Category::create([
            'name' => ['en' => 'Bedrooms', 'ar' => 'غرف النوم'],
            'supercategory_id' => $homes->id,
        ]);

        // -------------------------------------

        $mobiles = Category::create([
            'name' => ['en' => 'Mobiles', 'ar' => 'الموبايلات'],
            'supercategory_id' => $electronics->id,
        ]);

        $laptops = Category::create([
            'name' => ['en' => 'Laptops', 'ar' => 'لاب توب'],
            'supercategory_id' => $electronics->id,
        ]);

        // -------------------------------------
        // -------------------------------------
        // -------------------------------------

        // Sub Categories
        $shorts = Subcategory::create([
            'name' => ['en' => 'Shorts', 'ar' => 'شورتات'],
            'category_id' => $sports->id,
        ]);

        $skirts = Subcategory::create([
            'name' => ['en' => 'Skirts', 'ar' => 'جيب'],
            'category_id' => $women->id,
        ]);

        $analogue = Subcategory::create([
            'name' => ['en' => 'Analogue', 'ar' => 'تناظرية'],
            'category_id' => $watches->id,
        ]);

        $microbus = Subcategory::create([
            'name' => ['en' => 'Microbus', 'ar' => 'ميكروباص'],
            'category_id' => $automatic->id,
        ]);

        $sedan = Subcategory::create([
            'name' => ['en' => 'Sedan', 'ar' => 'سيدان'],
            'category_id' => $manual->id,
        ]);

        $fridges = Subcategory::create([
            'name' => ['en' => 'Fridges', 'ar' => 'ثلاجات'],
            'category_id' => $kitchen->id,
        ]);

        $beds = Subcategory::create([
            'name' => ['en' => 'Beds', 'ar' => 'أسرة'],
            'category_id' => $bedrooms->id,
        ]);

        $touchscreen = Subcategory::create([
            'name' => ['en' => 'Touchscreen', 'ar' => 'باللمس'],
            'category_id' => $mobiles->id,
        ]);

        $black = Subcategory::create([
            'name' => ['en' => 'Black', 'ar' => 'أسود'],
            'category_id' => $laptops->id,
        ]);

        // -------------------------------------
        // -------------------------------------
        // -------------------------------------
        // Brands
        $fiat = Brand::create([
            'name' => 'Fiat',
        ]);

        $skoda = Brand::create([
            'name' => 'Skoda',
        ]);

        $adidas = Brand::create([
            'name' => 'Adidas',
        ]);

        $polo = Brand::create([
            'name' => 'Polo',
        ]);

        // -------------------------------------
        // -------------------------------------
        // -------------------------------------
        // Products

        Product::create([
            'name' => ['en' => 'Drill', 'ar' => 'شنيور'],
            'slug' =>'sad-sada-sada-as',
            'barcode' =>'2323432',
            'base_price' =>'423.34',
            'brand_id' => 1,
            'subcategory_id' => 1,
        ]);

        Product::create([
            'name' => ['en' => 'Plaster', 'ar' => 'بلاستر'],
            'slug' =>'sad-sada-dsadas-as',
            'barcode' =>'3242342',
            'base_price' =>'213.34',
            'brand_id' => 2,
            'subcategory_id' => 3,

        ]);

        Product::create([
            'name' => ['en' => 'Cutter', 'ar' => 'قاطع'],
            'slug' =>'sad-3fds-sada-as',
            'barcode' =>'1312',
            'base_price' =>'3.34',
            'brand_id' => 3,
            'subcategory_id' => 6,

        ]);

        Product::create([
            'name' => ['en' => 'Dell 233', 'ar' => 'ديل'],
            'slug' =>'sad-wqeqwe-sada-as',
            'barcode' =>'234234',
            'base_price' =>'123.34',
            'brand_id' => 2,
            'subcategory_id' => 7,

        ]);

        Product::create([
            'name' => ['en' => 'HP sad', 'ar' => 'اتش بي'],
            'slug' =>'sad-dsadasd-sada-as',
            'barcode' =>'324234234',
            'base_price' =>'876.34',
            'brand_id' => 4,
            'subcategory_id' => 8,

        ]);

        Product::create([
            'name' => ['en' => 'samsung', 'ar' => 'سامسونج'],
            'slug' =>'sad-sada-sada-as',
            'barcode' =>'2323432',
            'base_price' =>'423.34',
            'brand_id' => 1,
            'subcategory_id' => 3,

        ]);

        Product::create([
            'name' => ['en' => 'Lenovo', 'ar' => 'لينوفو'],
            'slug' =>'sad-sada-sada-as',
            'barcode' =>'2323432',
            'base_price' =>'423.34',
            'brand_id' => 1,
            'subcategory_id' => 9,
        ]);
    }
}
