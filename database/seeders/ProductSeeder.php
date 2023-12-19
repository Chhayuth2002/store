<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all brand and category IDs
        $brandIds = Brand::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        // Adjust the number of products as needed
        for ($i = 0; $i < 20; $i++) {
            $product = Product::create([
                'name' => $faker->word,
                'description' => $faker->sentence,
                'brand_id' => $faker->randomElement($brandIds),
                'available_date' => $faker->dateTimeBetween('-1 month', '+1 month'),
                'is_featured' => $faker->boolean,
                'is_new' => $faker->boolean,
                'status' => $faker->randomElement(['active', 'inactive']),
            ]);

            // Attach random categories to the product
            $product->categories()->attach($faker->randomElements($categoryIds, $faker->numberBetween(1, 3)));
        }
    }
}
