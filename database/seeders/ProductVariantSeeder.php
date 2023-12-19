<?php

namespace Database\Seeders;

// database/seeders/ProductVariantSeeder.php

use App\Models\Attribute;
use Illuminate\Database\Seeder;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\AttributeOption;
use App\Models\ProductAttribute;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Get all product IDs and attribute option IDs
        $productIds = Product::pluck('id')->toArray();
        $attributeOptionIds = AttributeOption::pluck('id')->toArray();
        $attributeIds = Attribute::pluck('id')->toArray();

        // Adjust the number of product variants as needed
        for ($i = 0; $i < 20; $i++) {

            $title = $faker->catchPhrase;
            $productVariant = ProductVariant::create([
                'product_id' => $faker->randomElement($productIds),
                'sku' => $faker->unique()->isbn10,
                'name' => $title,
                'slug' => Str::slug($title),
                'price' => $faker->randomFloat(2, 10, 100),
                'inventory_quantity' => $faker->numberBetween(0, 100),
            ]);

            /// Attach random attribute options to the product variant
            $productAttribute = new ProductAttribute([
                'attribute_option_id' => $faker->randomElement($attributeOptionIds),
                // Provide a valid attribute_id here, based on your actual data
                'attribute_id' => $faker->randomElement($attributeIds) // Replace 1 with a valid attribute_id
            ]);

            $productVariant->productAttributes()->save($productAttribute);
        }
    }
}
