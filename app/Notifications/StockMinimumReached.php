<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockMinimumReached extends Notification
{
    use Queueable;

    protected $product;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param $product
     * @param $user
     */
    public function __construct($product, $user)
    {
        $this->product = $product;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Pode incluir 'mail' e 'database'
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Estoque Mínimo Atingido')
            ->line('O estoque do produto abaixo atingiu o limite mínimo para afiliados:')
            ->line('Produto: ' . $this->product->name)
            ->line('Estoque Atual: ' . $this->product->available_quantity)
            ->line('Estoque Mínimo Afiliado: ' . $this->product->estoque_minimo_afiliado)
            ->action('Ver Produto', url('/products/' . $this->product->id))
            ->line('Tome as devidas providências para repor o estoque.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'image' => $this->product->getImage(),
            'id' => $this->product->getId(),
            'product_name' => $this->product->title,
            'available_quantity' => $this->product->available_quantity,
            'estoque_minimo_afiliado' => $this->product->estoque_minimo_afiliado,
            "mensagem" => "O estoque do produto atingiu o limite mínimo para afiliados"
        ];
    }
}
