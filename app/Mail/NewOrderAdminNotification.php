<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Course Baru - ' . ($this->order->order_code ?? 'QEP'),
        );
    }

    public function content(): Content
    {
        $orderDate = $this->order->order_date ?? $this->order->created_at;
        $formattedOrderDate = $orderDate
            ? $orderDate->copy()->setTimezone('Asia/Jakarta')->format('d-m-Y H:i \W\I\B')
            : '-';

        return new Content(
            view: 'emails.admin.new-order-notification',
            with: [
                'order' => $this->order,
                'student' => $this->order->student,
                'profile' => $this->order->student?->studentProfile,
                'courseLevel' => $this->order->courseLevel,
                'courseProgram' => $this->order->courseLevel?->courseProgram,
                'formattedOrderDate' => $formattedOrderDate,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
