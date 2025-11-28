<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'total_amount',
        'total_amount_khr',
        'currency',
        'status',
        'cart_items',
        'khqr_payload',
        'transaction_id',
        'paid_at'
    ];

    protected $casts = [
        'cart_items' => 'array',
        'paid_at' => 'datetime'
    ];

    public static function generateOrderNumber()
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
    }

    public function markAsPaid($transactionId = null)
    {
        $this->update([
            'status' => 'paid',
            'transaction_id' => $transactionId,
            'paid_at' => now()
        ]);
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }
}