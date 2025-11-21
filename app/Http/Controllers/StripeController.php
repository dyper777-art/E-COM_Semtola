<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Helpers\TelegramHelper;
use App\Mail\OrderSuccessful;
use Illuminate\Support\Facades\Mail;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request)
    {



        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));


        $line_items = [];
        foreach ($cart as $id => $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $item['name']],
                    'unit_amount' => (int) round($item['price'] * 100),
                ],
                'quantity' => $item['quantity'],
            ];
        }


        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],

            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel'),
            'customer_email' => $request->email, // <-- here inside the array
            'phone_number_collection' => [
                'enabled' => true, // <--- This enables phone input
                ],
            'metadata' => [
                'cart' => json_encode($cart),
            ],
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

    $cart = session()->get('cart', []);

    if (!empty($cart)) {

        // Stripe::setApiKey(env('STRIPE_SECRET'));
        $session_id = $request->query('session_id');

    if (!$session_id) {
        return redirect()->route('cart.index')->with('error', 'Session ID missing.');
    }

    // 1. Get checkout session
    $session = \Stripe\Checkout\Session::retrieve($session_id);

    // 2. Get customer info safely
    $session = Session::retrieve($session_id);

    $email = $session->customer_details->email ?? 'No email';
    $name  = $session->customer_details->name ?? 'No name';
    $phone = $session->customer_details->phone ?? 'No phone';

        $message = "🛒 <b>New Successful Order</b>\n\n";
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
            $message .= "👤 <b>Name:</b> {$name}\n";
            $message .= "📧 <b>Email:</b> {$email}\n";
            $message .= "☎️ <b>Phone:</b> {$phone}\n\n";
            $message .= "📅 Date: " . date('d/m/Y') . "\n";
            $message .= "🕒 Time: " . date('h:i A') . "\n";

        \App\Helpers\TelegramHelper::send($message);
        // after retrieving $cart and calculating $total
        // Mail::to($email)->send(new \App\Mail\OrderSuccessful($cart, $total));


    }

    // clear the cart
    session()->forget('cart');

    return redirect()->route('cart.index')
        ->with('success', 'Payment successful! Your cart has been cleared.');
}

    public function cancel()
    {
        return redirect()->route('cart.index')->with('error', 'Payment canceled.');
    }

    // public function handleWebhook(Request $request)
    // {
    //     \Log::info('Stripe Webhook received: ' . $request->getContent());

    //     // also send raw webhook test message
    //     TelegramHelper::send("Stripe Webhook Hit:\n" . $request->getContent());

    //     $payload = $request->getContent();
    //     $sig = $request->header('Stripe-Signature');
    //     $secret = env('STRIPE_WEBHOOK_SECRET');

    //     try {
    //         $event = Webhook::constructEvent($payload, $sig, $secret);
    //     } catch (\Exception $e) {
    //         \Log::error("Invalid webhook: " . $e->getMessage());
    //         return response('Invalid', 400);
    //     }

    //     if ($event->type === 'checkout.session.completed') {

    //         $session = $event->data->object;

    //         $cart = isset($session->metadata->cart)
    //             ? json_decode($session->metadata->cart, true)
    //             : [];

    //         $message = "🛒 New Stripe Paid Order\n\n";
    //         $total = 0;

    //         foreach ($cart as $item) {
    //             $qty = $item['quantity'];
    //             $price = $item['price'];
    //             $sub = $qty * $price;
    //             $total += $sub;

    //             $message .= "📦 {$item['name']}\n";
    //             $message .= "Qty: {$qty} | Price: \${$price}\n";
    //             $message .= "Subtotal: \${$sub}\n\n";
    //         }

    //         $message .= "🧾 Total: \${$total}\n";
    //         $message .= "📅 " . date('d/m/Y') . "\n";
    //         $message .= "🕒 " . date('h:i A') . "\n";

    //         TelegramHelper::send($message);
    //     }

    //     return response('OK', 200);
    // }
    
    
}
