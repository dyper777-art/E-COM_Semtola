<?php

use App\Http\Controllers\StripeController;

Route::post('/stripe/webhook', [StripeController::class, 'webhook'])->name('stripe.webhook');
