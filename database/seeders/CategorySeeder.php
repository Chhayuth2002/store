<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            ['name' => 'Men', 'slug' => 'men', 'is_visible' => true, 'description' => 'Men description', 'order' => 1, 'parent_id' => null],
            ['name' => 'Clothing', 'slug' => 'clothing', 'is_visible' => true, 'description' => 'Men\'s Clothing description', 'order' => 2, 'parent_id' => 1],
            ['name' => 'T-Shirts', 'slug' => 't-shirts', 'is_visible' => true, 'description' => 'Men\'s T-Shirts description', 'order' => 3, 'parent_id' => 2],
            ['name' => 'Jeans', 'slug' => 'jeans', 'is_visible' => true, 'description' => 'Men\'s Jeans description', 'order' => 3, 'parent_id' => 2],
            ['name' => 'Accessories', 'slug' => 'accessories', 'is_visible' => true, 'description' => 'Men\'s Accessories description', 'order' => 2, 'parent_id' => 1],
            ['name' => 'Watch', 'slug' => 'watch', 'is_visible' => true, 'description' => 'Men\'s Watch description', 'order' => 3, 'parent_id' => 5],
            ['name' => 'Women', 'slug' => 'women', 'is_visible' => true, 'description' => 'Women description', 'order' => 1, 'parent_id' => null],
            ['name' => 'Clothing', 'slug' => 'clothing', 'is_visible' => true, 'description' => 'Women\'s Clothing description', 'order' => 2, 'parent_id' => 7],
            ['name' => 'Dresses', 'slug' => 'dresses', 'is_visible' => true, 'description' => 'Women\'s Dresses description', 'order' => 3, 'parent_id' => 8],
            ['name' => 'Blouses', 'slug' => 'blouses', 'is_visible' => true, 'description' => 'Women\'s Blouses description', 'order' => 3, 'parent_id' => 8],
            ['name' => 'Accessories', 'slug' => 'accessories', 'is_visible' => true, 'description' => 'Women\'s Accessories description', 'order' => 2, 'parent_id' => 7],
            ['name' => 'Watch', 'slug' => 'watch', 'is_visible' => true, 'description' => 'Women\'s Watch description', 'order' => 3, 'parent_id' => 11],
        ];



        foreach ($categories as $item) {
            Category::create($item);
        }
    }
}
