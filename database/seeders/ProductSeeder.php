<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Matcha Teen (White)', 'price' => 14.8, 'image' => 'IMG_2991.PNG', 'description' => 'High-quality white matcha tea.'],
            ['name' => 'Matcha Teen (Green)', 'price' => 14.8, 'image' => 'IMG_3000.jpg', 'description' => 'High-quality white matcha tea.'],
            ['name' => 'UMI', 'price' => 38, 'image' => 'IMG_3007.PNG', 'description' => 'High-quality white matcha tea.'],
            ['name' => 'Sadō', 'price' => 27, 'image' => 'IMG_3012.PNG', 'description' => 'High-quality white matcha tea.'],
            ['name' => 'Ummon', 'price' => 43, 'image' => 'IMG_3014.jpg', 'description' => 'High-quality white matcha tea.'],
            ['name' => 'Mellow', 'price' => 29, 'image' => 'IMG_3017.jpg', 'description' => 'High-quality white matcha tea.'],
            ['name' => 'Uji', 'price' => 38, 'image' => 'IMG_3016.jpg', 'description' => 'High-quality white matcha tea.'],
            ['name' => 'Chymey', 'price' => 32, 'image' => 'IMG_3018.jpg', 'description' => 'High-quality white matcha tea.'],
            ['name' => 'Marukyu koyamaen ISUZU matcha', 'price' => 41, 'image' => 'IMG_3019.jpg', 'description' => 'High-quality white matcha tea.'],
            ['name' => 'Yugen (Marukyu Koyamaen)', 'price' => 37, 'image' => 'IMG_3020.jpg', 'description' => 'High-quality white matcha tea.'],
            ['name' => 'Kanbayashi Shunsho', 'price' => 45.36, 'image' => 'IMG_3021.jpg', 'description' => 'High-quality white matcha tea.'],
            ['name' => 'Tsubokiri matcha', 'price' => 39, 'image' => 'IMG_3023.jpg', 'description' => 'High-quality white matcha tea.'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']], // avoid duplicates
                $product
            );
        }
    }
}
