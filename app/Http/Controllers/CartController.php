<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class CartController extends Controller
{
    //add to cart
    public function add(Request $request, $id)
{
    
    $products = [
            1 => ['id' => 1,'name' => 'Matcha Teen (White)', 'price' => 14.8, 'image' => 'IMG_2991.PNG', 'description' => 'High-quality white matcha tea.'],
            2 => ['id' => 2,'name' => 'Matcha Teen (Green)', 'price' => 14.8, 'image' => 'IMG_3000.jpg', 'description' => 'High-quality white matcha tea.'],
            3 => ['id' => 3,'name' => 'UMI', 'price' => 38, 'image' => 'IMG_3007.PNG', 'description' => 'High-quality white matcha tea.'],
            4 => ['id' => 4,'name' => 'Sadō', 'price' => 27, 'image' => 'IMG_3012.PNG', 'description' => 'High-quality white matcha tea.'],
            5 => ['id' => 5,'name' => 'Ummon', 'price' => 43, 'image' => 'IMG_3014.jpg', 'description' => 'High-quality white matcha tea.'],
            6 => ['id' => 6,'name' => 'Mellow', 'price' => 29, 'image' => 'IMG_3017.jpg', 'description' => 'High-quality white matcha tea.'],
            7 => ['id' => 7,'name' => 'Uji', 'price' => 38, 'image' => 'IMG_3016.jpg', 'description' => 'High-quality white matcha tea.'],
            8 => ['id' => 8,'name' => 'Chymey', 'price' => 32, 'image' => 'IMG_3018.jpg', 'description' => 'High-quality white matcha tea.'],
            9 => ['id' => 9,'name' => 'Marukyu koyamaen ISUZU matcha', 'price' => 41, 'image' => 'IMG_3019.jpg', 'description' => 'High-quality white matcha tea.'],
            10 => ['id' => 10,'name' => 'Yugen (Marukyu Koyamaen)', 'price' => 37, 'image' => 'IMG_3020.jpg', 'description' => 'High-quality white matcha tea.'],
            11 => ['id' => 11,'name' => 'Kanbayashi Shunsho', 'price' => 45.36, 'image' => 'IMG_3021.jpg', 'description' => 'High-quality white matcha tea.'],
            12 => ['id' => 12,'name' => 'Tsubokiri matcha', 'price' => 39, 'image' => 'IMG_3023.jpg', 'description' => 'High-quality white matcha tea.'],

        ];
    // $product = Product::find[$id];
    // Check if product exists in array
    if (!isset($products[$id])) {
        return redirect()->back()->with('error', 'Product not found.');
    }

    $product = $products[$id];
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['quantity']++;
    } else {
        $cart[$id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1,
        ];
    }

    session()->put('cart', $cart);

    return redirect()->back()->with('success', 'Product added to cart.');
}
    // 🔥 SEND TELEGRAM MESSAGE ON SUCCESS **no need anymore
    // public function success(Request $request)
    // {
    //     $cart = session()->get('cart', []);



    //     if (!empty($cart)) {
    //         $message = "🛒 <b>New Successful Order</b>\n\n";

    //         $total = 0;

    //         foreach ($cart as $item) {
    //             $subtotal = $item['price'] * $item['quantity'];
    //             $total += $subtotal;

    //             $message .= "• <b>{$item['name']}</b>\n";
    //             $message .= "   Qty: {$item['quantity']}\n";
    //             $message .= "   Price: \${$item['price']}\n\n";
    //         }

    //         $message .= "=====================\n";
    //         $message .= "💰 <b>Total: \$$total</b>";

    //         TelegramHelper::send($message);
    //     }

    //     // Clear cart
    //     session()->forget('cart');

    //     return redirect()->route('cart.index')
    //         ->with('success', 'Payment successful! Your cart has been cleared.');
    // }

//     public function success(Request $request)
// {
//     // Clear the cart for the user
//     session()->forget('cart');

//     return redirect()->route('cart.index')->with('success', 'Payment successful! Your cart has been cleared.');
// }

    //show cart
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }


    //remove
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

}
