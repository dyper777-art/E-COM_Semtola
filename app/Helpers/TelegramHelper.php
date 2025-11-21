<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramHelper
{
    protected static string $botToken = '8529100342:AAFAOy_lp4Ds98B4rnsKjkdfKLIFLioAaZg'; // or use env() here
    protected static string $chatId = '658883193';

    /**
     * Send a message to Telegram
     *
     * @param string $message
     * @return void
     */
    public static function send(string $message): void
    {

        
    $url = "https://api.telegram.org/bot" . self::$botToken . "/sendMessage";

    try {
        $http = Http::withoutVerifying(); // disables SSL verification

        $response = $http->post($url, [
            'chat_id' => self::$chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);

        // Log Telegram response
        Log::info('Telegram response: ' . $response->body());
        Log::info('Telegram response status: ' . $response->status());

    } catch (\Exception $e) {
        Log::error('Telegram send exception: ' . $e->getMessage());
    }
}
}

