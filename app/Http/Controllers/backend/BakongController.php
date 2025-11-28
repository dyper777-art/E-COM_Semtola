<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;

class BakongController extends Controller
{
    /**
     * Generate KHQR payment code (GET request) - For standalone page
     */
    public function generateQR(Request $request)
    {
        $amount = (int) $request->input('amount', 15000);
        $currencyInput = $request->input('currency', 'USD');
        
        // Simple currency mapping
        $currencyCode = match(strtoupper($currencyInput)) {
            'USD' => 840,
            'KHR' => 116,
            default => 840
        };

        try {
            $info = new IndividualInfo(
                bakongAccountID: 'sem_tola@aclb',
                merchantName:    'Teen',
                merchantCity:    'Phnom Penh',
                currency:        $currencyCode,
                amount:          $amount
            );

            // Use generateIndividual method
            $response = BakongKHQR::generateIndividual($info);

            // Extract payload and md5
            $qrString = $response->data['qr'] ?? null;
            $md5 = $response->data['md5'] ?? null;

            if (!$qrString) {
                throw new \Exception('KHQR did not return QR payload');
            }

            // Generate QR code using QuickChart
            $qrImageUrl = $this->generateQRCode($qrString, 300);

            session(['last_khqr_md5' => $md5]);

            return view('khqr', [
                'qr_image_url' => $qrImageUrl,
                'qr_string' => $qrString,
                'md5' => $md5,
                'amount' => $amount,
                'currency' => $currencyInput,
                'merchant_name' => 'Teen'
            ]);
            
        } catch (\Exception $e) {
            return view('khqr_error', [
                'error' => 'Failed to generate QR code: ' . $e->getMessage(),
                'amount' => $amount,
                'currency' => $currencyInput
            ]);
        }
    }

    /**
     * Generate KHQR and return JSON (for modal)
     */
    public function generateQRJson(Request $request)
    {
        $amount = (int) $request->input('amount', 15000);
        $currencyInput = $request->input('currency', 'USD');
        
        // Simple currency mapping
        $currencyCode = match(strtoupper($currencyInput)) {
            'USD' => 840,
            'KHR' => 116,
            default => 840
        };

        try {
            $info = new IndividualInfo(
                bakongAccountID: 'sem_tola@aclb',
                merchantName:    'Teen',
                merchantCity:    'Phnom Penh',
                currency:        $currencyCode,
                amount:          $amount
            );

            // Use generateIndividual method
            $response = BakongKHQR::generateIndividual($info);

            // Extract payload and md5
            $qrString = $response->data['qr'] ?? null;
            $md5 = $response->data['md5'] ?? null;

            if (!$qrString) {
                throw new \Exception('KHQR did not return QR payload');
            }

            // Generate QR code using QuickChart
            $qrImageUrl = $this->generateQRCode($qrString, 250); // Smaller for modal

            session(['last_khqr_md5' => $md5]);

            return response()->json([
                'success' => true,
                'qr_image_url' => $qrImageUrl,
                'qr_string' => $qrString,
                'md5' => $md5,
                'amount' => $amount,
                'currency' => $currencyInput,
                'merchant_name' => 'Teen'
            ]);
            
        } catch (\Exception $e) {
            Log::error('KHQR JSON Generation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate QR code using QuickChart API
     */
    private function generateQRCode($data, $size = 300)
    {
        $encodedData = urlencode($data);
        return "https://quickchart.io/qr?text={$encodedData}&size={$size}&margin=1";
    }

    /**
     * Check transaction by MD5 (POST request)
     */
    public function checkTransaction(Request $request)
    {
        $md5 = $request->input('md5');

        if (!$md5) {
            return response()->json([
                'success' => false,
                'error' => 'Missing md5 parameter'
            ], 400);
        }

        try {
            $token = env('KHQR_API_TOKEN');
            
            if ($token) {
                $bakong = new BakongKHQR($token);
                $status = $bakong->checkTransactionByMD5($md5);
                
                return response()->json([
                    'success' => true,
                    'status' => $status
                ]);
            } else {
                // Mock response for testing
                return $this->mockTransactionStatus($md5);
            }

        } catch (\Exception $e) {
            Log::error('KHQR Check Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Check failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * KHQR Success Page
     */
    public function success()
    {
        session()->forget('cart');
        
        return view('khqr.success', [
            'title' => 'Payment Successful',
            'message' => 'Your payment was processed successfully via Bakong KHQR!',
            'order_number' => session('order_number', 'N/A'),
            'timestamp' => now()->format('F j, Y, g:i a')
            
        ]);
        
    }

    /**
     * Mock transaction status for testing
     */
    private function mockTransactionStatus($md5)
    {
        $random = rand(1, 10);
        if ($random > 8) {
            $status = 'completed';
        } elseif ($random < 2) {
            $status = 'failed';
        } else {
            $status = 'pending';
        }
        
        return response()->json([
            'success' => true,
            'status' => $status,
            'md5' => $md5,
            'source' => 'mock'
        ]);
    }
}