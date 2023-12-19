<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Color', 'slug' => 'color'],
            ['name' => 'Size', 'slug' => 'size'],
            // Add more data as needed
        ];

        foreach ($data as $item) {
            Attribute::create($item);
        }
    }
}
