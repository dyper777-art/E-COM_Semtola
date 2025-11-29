<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Helpers\TelegramHelper;
use App\Models\Order;
use Illuminate\Http\Request;

// Show cart
Route::get('/cart', function () {
    $cart = session()->get('cart', []);
    return view('cart.index', compact('cart')); // Make sure the view exists!
})->name('cart.index');


// Remove item
Route::delete('/cart/remove/{id}', function ($id) {
    $cart = session()->get('cart', []);
    if (isset($cart[$id]))
        unset($cart[$id]);
    session()->put('cart', $cart);
    return redirect()->route('cart.index')->with('success', 'Item removed!');
})->name('cart.remove');



// Checkout
Route::post('/checkout', function (Request $request) {
    $cart = session()->get('cart', []);
    if (empty($cart))
        return redirect()->route('cart.index')->with('error', 'Cart is empty!');

    $date = date('d/m/Y');
    $time = date('h:i A');
    $message = "🛒 New Order Completed\n\n";
    $totalPrice = 0;
    $message .= "$request->name\n";
    $message .= "$request->phone\n";

    foreach ($cart as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $totalPrice += $subtotal;
        $message .= "📦 Product: {$item['name']}\n";
        $message .= "💲 Price: \${$item['price']}\n";
        $message .= "🔢 Quantity: {$item['quantity']}\n";
        $message .= "💰 Total: \${$subtotal}\n\n";
    }
    $message .= "🧾 Grand Total: \${$totalPrice}\n";
    $message .= "📅 Date: {$date}\n";
    $message .= "🕐 Time: {$time}\n";

    // Save order (if you have an orders table)
    Order::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'total' => $totalPrice,
        'items' => json_encode($cart),
    ]);

    // Then send Telegram
    TelegramHelper::send($message);

    // Clear cart
    Session::forget('cart');
    return redirect()->route('cart.index')->with('success', '✅ Payment successful! Cart cleared.');
})->name('checkout.process');
