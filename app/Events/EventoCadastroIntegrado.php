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
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($name,$image,$id_mercadolivre,$product_id)
    {
        $this->name = $name;
        $this->image = $image;
        $this->id_mercadolivre = $id_mercadolivre;
        $this->product_id = $product_id;
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
