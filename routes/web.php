<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StripeWebhookController;
use App\Helpers\TelegramHelper;

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
