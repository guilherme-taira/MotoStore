<?php

namespace App\Events;

use App\Models\produtos_integrados;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventoCadastroIntegrado
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $name;
    public $image;
    public $id_mercadolivre;
    public $product_id;
    public $priceNotFee;
    public $dados;
    public $user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id = null,$name,$image,$id_mercadolivre,$product_id,$priceNotFee,$dados = [])
    {
        $this->user_id = $user_id;
        $this->name = $name;
        $this->image = $image;
        $this->id_mercadolivre = $id_mercadolivre;
        $this->product_id = $product_id;
        $this->priceNotFee = $priceNotFee;
        $this->dados = $dados;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}
