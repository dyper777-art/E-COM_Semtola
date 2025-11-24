<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\TelegramHelper;

use Stripe\Webhook;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Log and send Telegram for debugging
        \Log::info('Stripe Webhook received: ' . $request->getContent());
        TelegramHelper::send("Stripe Webhook Hit:\n" . $request->getContent());

        Log::info($request->getContent()); // show payload


        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            \Log::error('Stripe webhook error: ' . $e->getMessage());
            return response('Invalid', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $cart = isset($session->metadata->cart)
                ? json_decode($session->metadata->cart, true)
                : [];

            $message = "🛒 <b>New Stripe Order Paid</b>\n\n";
            $total = 0;

            foreach ($cart as $item) {
                $qty = $item['quantity'];
                $price = $item['price'];
                $subtotal = $qty * $price;
                $total += $subtotal;

                $message .= "📦 {$item['name']}\n";
                $message .= "Qty: {$qty} | Price: \${$price}\n";
                $message .= "Subtotal: \${$subtotal}\n\n";
            }

            $message .= "🧾 <b>Total:</b> \${$total}\n";
            $message .= "📧 Customer Email: {$email}\n";
            $message .= "📅 Date: " . date('d/m/Y') . "\n";
            $message .= "🕒 Time: " . date('h:i A') . "\n";

            TelegramHelper::send($message);
            TelegramHelper::send("Stripe Webhook Hit:\n" . $request->getContent());


        }

        return response('Webhook handled', 200);
    }
}
