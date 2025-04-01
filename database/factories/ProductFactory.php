<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => ['en' => $this->faker->word(), 'ar' => $this->faker->word()],
            'slug' => ['en' => $this->faker->slug(), 'ar' => $this->faker->slug()],
            'barcode' => $this->faker->ean13(),
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
            'quantity' => $this->faker->numberBetween(0, 100),
            'low_stock' => $this->faker->numberBetween(1, 10),
            'original_price' => $this->faker->randomFloat(2, 100, 1000),
            'base_price' => $this->faker->randomFloat(2, 100, 1000),
            'final_price' => $this->faker->randomFloat(2, 100, 1000),
            'points' => $this->faker->numberBetween(0, 100),
            'description' => ['en' => $this->faker->paragraph(), 'ar' => $this->faker->paragraph()],
            'model' => $this->faker->word(),
            'refundable' => $this->faker->boolean(),
            'free_shipping' => $this->faker->boolean(),
            'publish' => true,
            'under_reviewing' => false,
            'created_by' => null,
            'brand_id' => Brand::factory()
        ];
    }
}
