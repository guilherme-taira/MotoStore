<?php

namespace App\Notifications;

use App\Models\order_site;
use App\Models\Products;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class notificaUserOrder extends Notification
{
    use Queueable;
    private $usuario;
    private $order;
    private $produto;
    private $Orderid;
    private $numeroPedido;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $usuario, $order,Products $produto,$Orderid = null, $numeroPedido = null)
    {
        $this->usuario = $usuario;
        $this->order = $order;
        $this->produto = $produto;
        $this->Orderid = $Orderid;
        $this->numeroPedido = $numeroPedido;
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
            'mensagem' => "Olá ".$this->usuario->name ." Aguarde o pagamento do afiliado e envie o mais rápido possivel! para evitar atrasos.",
             $this->usuario,
            'id' => $this->produto->getId(),
            'image' => $this->produto->getImage(),
            'orderid' => $this->Orderid,
            'ml_id' => $this->numeroPedido
        ];
    }
}
