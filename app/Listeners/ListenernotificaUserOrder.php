<?php

namespace App\Listeners;

use App\Events\notificaUserOrder;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ListenernotificaUserOrder
{
    public $order;
    public $user;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(User $user, $order)
    {
        $this->user = $user;
        $this->order = $order;
    }

    /**
     * Handle the event.
     *1
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = User::find($this->user);
        $user->notify(new notificaUserOrder($user,$this->order));
    }
}
