<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StripeWebhookController;
use App\Helpers\TelegramHelper;

// Load FRONT routes (only once)
require __DIR__.'/front/home.php';
require __DIR__.'/front/about.php';
require __DIR__.'/front/cart.php';
require __DIR__.'/front/product.php';
require __DIR__.'/front/catalog.php';

// Product details
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// Route::get('/cart/success', [CartController::class, 'success'])->name('cart.success');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Stripe Checkout
Route::post('/stripe/checkout', [StripeController::class, 'createCheckoutSession'])->name('stripe.checkout');
Route::get('/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
Route::get('/checkout/success', [StripeController::class, 'success'])->name('checkout.success');

// Stripe Webhook

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);


// Route::get('/test-stripe-webhook', function () {
//     // Simulated Stripe session data
//     $fakeSession = [
//         'metadata' => [
//             'cart' => json_encode([
//                 ['name' => 'Matcha Teen (White)', 'price' => 14.8, 'quantity' => 2],
//                 ['name' => 'UMI', 'price' => 38, 'quantity' => 1],
//             ])
//         ]
//     ];

//     // Build Telegram message like your webhook does
//     $cart = json_decode($fakeSession['metadata']['cart'], true);
//     $message = "🛒 Simulated Stripe Paid Order\n\n";
//     $total = 0;

//     foreach ($cart as $item) {
//         $qty = $item['quantity'];
//         $price = $item['price'];
//         $sub = $qty * $price;
//         $total += $sub;

//         $message .= "📦 {$item['name']}\n";
//         $message .= "Qty: {$qty} | Price: \${$price}\n";
//         $message .= "Subtotal: \${$sub}\n\n";
//     }

//     $message .= "🧾 Total: \${$total}\n";
//     $message .= "📧 Customer Email: {$session->customer_details->email}\n";
//     $message .= "📅 " . date('d/m/Y') . "\n";
//     $message .= "🕒 " . date('h:i A') . "\n";

//     TelegramHelper::send($message);

//     return "Test webhook sent! Check your Telegram.";
// });

// Route::get('/test-telegram', function () {
//     $message = "✅ Test message from Laravel at " . date('Y-m-d H:i:s');
    
//     // Send to Telegram
//     TelegramHelper::send($message);
    
//     return "Test message sent! Check your Telegram.";
// });
