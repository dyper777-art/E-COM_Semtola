<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/product/{id}', [ProductController::class, 'show'])
    ->name('product');
Route::get('/product/{id}', [ProductController::class, 'show']);


