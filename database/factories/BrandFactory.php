<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'top' => $this->faker->boolean(),
            'logo_path' => null,
            'country_id' => Country::factory(),
            'meta_title' => $this->faker->sentence(),
            'meta_description' => $this->faker->paragraph(),
        ];
    }
}