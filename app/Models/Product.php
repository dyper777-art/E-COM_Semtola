<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Product extends Model
{
    public function add(Request $request)
    {
        $id = $request->input('product_id');
        $products = [
            1 => ['id' => 1, 'name' => 'Matcha Teen (White)', 'price' => 14.8, 'image' => 'IMG_2991.PNG', 'description' => 'High-quality white matcha tea.'],
            2 => ['id' => 2, 'name' => 'Matcha Teen (Green)', 'price' => 14.8, 'image' => 'IMG_3000.jpg', 'description' => 'High-quality white matcha tea.'],
            3 => ['id' => 3, 'name' => 'UMI', 'price' => 38, 'image' => 'IMG_3007.PNG', 'description' => 'High-quality white matcha tea.'],
            4 => ['id' => 4, 'name' => 'Sadō', 'price' => 27, 'image' => 'IMG_3012.PNG', 'description' => 'High-quality white matcha tea.'],
            5 => ['id' => 5, 'name' => 'Ummon', 'price' => 43, 'image' => 'IMG_3014.jpg', 'description' => 'High-quality white matcha tea.'],
            6 => ['id' => 6, 'name' => 'Mellow', 'price' => 29, 'image' => 'IMG_3017.jpg', 'description' => 'High-quality white matcha tea.'],
            7 => ['id' => 7, 'name' => 'Uji', 'price' => 38, 'image' => 'IMG_3016.jpg', 'description' => 'High-quality white matcha tea.'],
            8 => ['id' => 8, 'name' => 'Chymey', 'price' => 32, 'image' => 'IMG_3018.jpg', 'description' => 'High-quality white matcha tea.'],
            9 => ['id' => 9, 'name' => 'Marukyu koyamaen ISUZU matcha', 'price' => 41, 'image' => 'IMG_3019.jpg', 'description' => 'High-quality white matcha tea.'],
            10 => ['id' => 10, 'name' => 'Yugen (Marukyu Koyamaen)', 'price' => 37, 'image' => 'IMG_3020.jpg', 'description' => 'High-quality white matcha tea.'],
            11 => ['id' => 11, 'name' => 'Kanbayashi Shunsho', 'price' => 45.36, 'image' => 'IMG_3021.jpg', 'description' => 'High-quality white matcha tea.'],
            12 => ['id' => 12, 'name' => 'Tsubokiri matcha', 'price' => 39, 'image' => 'IMG_3023.jpg', 'description' => 'High-quality white matcha tea.'],

        ];
    }
}
