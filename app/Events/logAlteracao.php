<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class logAlteracao
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $acao;
    public $user;
    public $message;
    public $sucesso;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($acao,$user, $message,$sucesso)
    {
        $this->acao = $acao;
        $this->user = $user;
        $this->message = $message;
        $this->sucesso = $sucesso;
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
