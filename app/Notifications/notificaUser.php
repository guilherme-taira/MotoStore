<?php

namespace App\Notifications;

use App\Models\Products;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class notificaUser extends Notification
{
    use Queueable;
    private $usuario;
    private $produto;
    private $id_mercadolivre;
    private $oldPrice;
    private $newPrice;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $usuario,Products $produto,$id_mercadolivre = null, $oldPrice = null,$newPrice = null)
    {
        $this->usuario = $usuario;
        $this->produto = $produto;
        $this->id_mercadolivre = $id_mercadolivre;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'mensagem' => "produto ". $this->produto->getName() ." teve reajuste no preço! regularize seu cadastro nas plataformas!",
             $this->usuario,
             'imagem' => $this->produto->getImage(),
             'type' => 'produto',
             'id' => $this->produto->getId(),
             'ml_id' => $this->id_mercadolivre,
             'oldPrice' => $this->oldPrice,
             'newPrice' => $this->newPrice
        ];
    }
}
