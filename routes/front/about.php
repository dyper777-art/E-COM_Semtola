<?php

use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;

Route::get('/about', [AboutController::class, 'index'])
    ->name(name: 'about');

