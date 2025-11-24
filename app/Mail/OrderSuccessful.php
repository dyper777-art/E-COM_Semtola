<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;   
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderSuccessful extends Mailable
{
    public array $cart;
    public float $total;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(array $cart, float $total)
    {
        $this->cart = $cart;
        $this->total = $total;
    }
    
    public function build()
    {
        return $this->subject('Your Order was Successful')
                    ->markdown('emails.orders.successful', [
                        'cart' => $this->cart,
                        'total' => $this->total,
                    ]);
    }
    
    /**
     * 
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Successful',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
