<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order; // Mantén esto para acceder al pedido en la vista

    /**
     * Crea una nueva instancia de mensaje.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Define el sobre del mensaje, incluyendo el asunto.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de tu pedido', // Cambia el asunto si deseas algo más específico
        );
    }

    /**
     * Define el contenido del mensaje.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.order-create', // Asegúrate de que esta vista exista en 'resources/views/emails/order-created.blade.php'
            with: [
                'order' => $this->order,
            ],
        );
    }

    /**
     * Define los archivos adjuntos para el mensaje (opcional).
     */
    public function attachments(): array
    {
        return [];
    }
}
