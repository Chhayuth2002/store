<?php

namespace Database\Seeders;

// database/seeders/CustomerSeeder.php

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Adjust the number of customers as needed
        for ($i = 0; $i < 5; $i++) {
            Customer::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // You might want to generate a secure password instead
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
