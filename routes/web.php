<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\KHQRController;
use App\Http\Controllers\backend\BakongController;
use App\Http\Controllers\OrderController;

// Load FRONT routes (only once)
require __DIR__ . '/front/home.php';
require __DIR__ . '/front/about.php';
require __DIR__ . '/front/cart.php';
require __DIR__ . '/front/product.php';
require __DIR__ . '/front/catalog.php';

// Product details
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Stripe Checkout
Route::post('/stripe/checkout', [StripeController::class, 'createCheckoutSession'])->name('stripe.checkout');
Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success'); // Changed path
Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
Route::get('/checkout/success', [StripeController::class, 'success'])->name('checkout.success');

// Stripe Webhook
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);

// KHQR Routes
Route::get('/bakong/qr', [BakongController::class, 'generateQR'])->name('khqr.qr');
Route::get('/bakong/generate-qr-json', [BakongController::class, 'generateQRJson']); // Add this line
Route::get('/bakong/check-transaction', [BakongController::class, 'checkTransaction'])->name('khqr.check');
Route::get('/bakong/success', [BakongController::class, 'success'])->name('khqr.success');

// In routes/web.php - Add this temporary route
Route::get('/test-khqr-debug', function() {
    return response()->json([
        'success' => true,
        'qr_image_url' => 'https://chart.googleapis.com/chart?cht=qr&chs=250x250&chl=TEST_QR_DATA&choe=UTF-8',
        'md5' => 'test_md5_' . time(),
        'amount' => 10,
        'currency' => 'USD',
        'merchant_name' => 'Teen'
    ]);
});
