<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The Order model instance.
     *
     * @var \App\Models\Order
     */
    public $order;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function __construct($order)
    {
        // Inject the Order model into the Mailable
        $this->order = $order;
        // Ensure the order items are loaded before sending
        $this->order->load('items.product'); 
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmation #' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.confirmation',
            with: [
                'order' => $this->order,
            ],
        );
    }
}