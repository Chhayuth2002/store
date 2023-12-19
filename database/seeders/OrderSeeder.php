<?php

namespace Database\Seeders;

// database/seeders/OrderSeeder.php

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Get all customer IDs
        $customerIds = Customer::pluck('id')->toArray();

        // Adjust the number of orders as needed
        for ($i = 0; $i < 10; $i++) {
            $createdAt = $faker->dateTimeBetween('-10 months', 'now');
            $updatedAt = $faker->dateTimeBetween($createdAt, 'now');

            Order::create([
                'number' => $faker->unique()->randomNumber(8),
                'customer_id' => $faker->randomElement($customerIds),
                'total_price' => $faker->randomFloat(2, 50, 500),
                'status' => $faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
                'country' => $faker->country,
                'street' => $faker->streetAddress,
                'city' => $faker->city,
                'state' => $faker->state,
                'zip' => $faker->postcode,
                'note' => $faker->word,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }
    }
}
