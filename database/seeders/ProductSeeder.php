<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category' => 'Electronics',
                'name' => 'Wireless Headphones',
                'description' => 'High quality wireless headphones with noise cancellation.',
                'price' => '14',
            ],
            [
                'category' => 'Electronics',
                'name' => 'Smart Watch',
                'description' => 'Water resistant smart watch with fitness tracking.',
                'price' => '35',
            ],
            [
                'category' => 'Fashion',
                'name' => 'Men Casual Shirt',
                'description' => 'Comfortable cotton casual shirt for men.',
                'price' => '13',
            ],
            [
                'category' => 'Fashion',
                'name' => 'Women Handbag',
                'description' => 'Stylish leather handbag.',
                'price' => '52',
            ],
            [
                'category' => 'Home',
                'name' => 'LED Table Lamp',
                'description' => 'Modern LED lamp with adjustable brightness.',
                'price' => '8',
            ],
            [
                'category' => 'Home',
                'name' => 'Wall Clock',
                'description' => 'Minimalist wall clock design.',
                'price' => '12',
            ],
            [
                'category' => 'Sports',
                'name' => 'Football',
                'description' => 'Professional size football.',
                'price' => '16',
            ],
            [
                'category' => 'Sports',
                'name' => 'Gym Dumbbells',
                'description' => 'Pair of 10kg dumbbells.',
                'price' => '26',
            ],
            [
                'category' => 'Sports',
                'name' => 'Running Shoes',
                'description' => 'Lightweight and comfortable running shoes with breathable mesh and durable sole.',
                'price' => '88',
            ],
            [
                'category' => 'Beauty',
                'name' => 'Hair Dryer',
                'description' => 'Fast drying professional hair dryer.',
                'price' => '19',
            ],
        ];

        foreach ($products as $index => $item) {

            Product::create([
                'category'      => $item['category'],
                'name'          => $item['name'],
                'slug'          => Str::slug($item['name']),
                'sku'           => 'SKU-' . ($index + 1001),
                'description'   => $item['description'],
                'main_image'    => 'uploads/products/product'.($index + 1).'.webp',
                'price'         => $item['price'],
                'reviews_count' => rand(0, 150),
                'rating'        => rand(3, 5),
                'is_active'     => 'active',
                'is_popular'    => ($index < 3) ? '1' : '0',
            ]);
        }
    }
}
