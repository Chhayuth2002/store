<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            ['name' => 'Brand 1', 'slug' => 'brand-1', 'url' => 'https://picsum.photos/', 'description' =>  'Brand 1 description'],
            ['name' => 'Brand 2', 'slug' => 'brand-2', 'url' => 'https://picsum.photos/', 'description' =>  'Brand 1 description'],
            // Add more data as needed
        ];

        foreach ($data as $item) {
            Brand::create($item);
        }
    }
}
