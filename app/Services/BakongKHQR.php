<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BakongKHQR
{
    /**
     * Generate individual KHQR with the specific format you want
     */
    public static function generateIndividual(array $info)
    {
        try {
            // Generate the specific QR string format you want
            $qrString = self::generateSpecificFormat($info);
            
            $md5 = md5($qrString . time());
            $transactionId = self::generateTransactionId();

            return (object) [
                'success' => true,
                'data' => [
                    'qr' => $qrString,
                    'md5' => $md5,
                    'transaction_id' => $transactionId,
                    'amount' => $info['amount'],
                    'currency' => $info['currency'],
                    'merchant_name' => $info['merchantName'],
                    'expires_at' => now()->addMinutes(15)->toISOString(),
                ]
            ];

        } catch (\Exception $e) {
            Log::error('KHQR Generation Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate the specific format you want:
     * "00020101021229180014jonhsmith@nbcq52045999530311654035005802KH5910Jonh Smith6010PHNOM PENH99170013173949577872263046894"
     */
    private static function generateSpecificFormat(array $info)
    {
        $account = $info['bakongAccountID'] ?? 'sem_tola@aclb';
        $merchant = $info['merchantName'] ?? 'Teen';
        $city = $info['merchantCity'] ?? 'Phnom Penh';
        $currency = $info['currency'] ?? 'USD';
        $amount = $info['amount'] ?? 0;
        
        // Format amount without decimals for KHR, with decimals for USD
        if ($currency === 'USD') {
            $amountFormatted = number_format($amount, 2, '.', '');
            $currencyCode = '840'; // USD
        } else {
            $amountFormatted = number_format($amount, 0, '.', ''); // No decimals for KHR
            $currencyCode = '116'; // KHR
        }
        
        // Generate unique reference (like 1739495778722 in your example)
        $uniqueRef = self::generateUniqueReference();

        // Build the exact format you want
        $elements = [
            '00' => '01', // Payload Format Indicator
            '01' => '12', // Point of Initiation Method
            '29' => [ // Merchant Account Information
                '00' => 'A000000010010010', // Globally Unique Identifier
                '01' => $account, // Bakong Account ID
            ],
            '52' => '045999', // Merchant Category Code (store)
            '53' => $currencyCode, // Currency
            '54' => $amountFormatted, // Amount
            '58' => 'KH', // Country
            '59' => $merchant, // Merchant Name
            '60' => strtoupper($city), // Merchant City (uppercase)
            '99' => $uniqueRef, // Unique Reference
        ];

        $qrString = self::buildEMVCOString($elements);
        
        // Add CRC
        $crc = self::calculateCRC($qrString);
        $qrString .= '6304' . $crc;

        return $qrString;
    }

    /**
     * Build EMVCO string format
     */
    private static function buildEMVCOString(array $elements)
    {
        $qrString = '';

        foreach ($elements as $id => $value) {
            if (is_array($value)) {
                $subString = '';
                foreach ($value as $subId => $subValue) {
                    $subString .= $subId . str_pad(strlen($subValue), 2, '0', STR_PAD_LEFT) . $subValue;
                }
                $qrString .= $id . str_pad(strlen($subString), 2, '0', STR_PAD_LEFT) . $subString;
            } else {
                $qrString .= $id . str_pad(strlen($value), 2, '0', STR_PAD_LEFT) . $value;
            }
        }

        return $qrString;
    }

    /**
     * Calculate CRC16 checksum
     */
    private static function calculateCRC($data)
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc <<= 1;
                }
            }
        }
        return strtoupper(dechex($crc & 0xFFFF));
    }

    /**
     * Generate unique reference (13 digits like 1739495778722)
     */
    private static function generateUniqueReference()
    {
        return substr(str_replace('.', '', microtime(true)) . rand(100, 999), 0, 13);
    }

    /**
     * Generate transaction ID
     */
    private static function generateTransactionId()
    {
        return 'TXN' . time() . rand(1000, 9999);
    }

    /**
     * Generate from array (convenience method)
     */
    public static function generateFromArray(array $info)
    {
        return self::generateIndividual($info);
    }
}