<?php

namespace Database\Seeders;

// database/seeders/OrderItemSeeder.php

use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\ProductVariant;
use Faker\Factory as Faker;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Get all order and product variant IDs
        $orderIds = Order::pluck('id')->toArray();
        $productVariantIds = ProductVariant::pluck('id')->toArray();

        // Adjust the number of order items as needed
        for ($i = 0; $i < 30; $i++) {
            OrderItem::create([
                'order_id' => $faker->randomElement($orderIds),
                'product_variant_id' => $faker->randomElement($productVariantIds),
                'quantity' => $faker->numberBetween(1, 5),
                'price' => $faker->randomFloat(2, 10, 100),
            ]);
        }
    }
}
