<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/catalog', [CatalogController::class, 'index'])
    ->name(name: 'catalog');
// Route::post('/cart/add/{id}', function ($id) {
//     \Log::info('Add to cart called with ID: ' . $id);
// })->name('cart.add');
Route::match(['get', 'post'], '/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');

