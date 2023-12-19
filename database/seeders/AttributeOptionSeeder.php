<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $data = [
            ['attribute_id' => 1, 'slug' => 'red', 'value' => 'Red'],
            ['attribute_id' => 1, 'slug' => 'blue', 'value' => 'Blue'],
            ['attribute_id' => 2, 'slug' => 'small', 'value' => 'Small'],
            ['attribute_id' => 2, 'slug' => 'meduim', 'value' => 'Meduim'],
            // Add more data as needed
        ];

        foreach ($data as $item) {
            AttributeOption::create($item);
        }
    }
}
