<?php

namespace App\Listeners;

use App\Models\Products;
use App\Models\User;
use App\Notifications\notificaUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class notificaUserCadastrado
{
    public $produto;
    public $user;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(User $user, $produto)
    {
        $this->user = $user;
        $this->produto = $produto;
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
        $user->notify(new notificaUser($user,$this->produto->name));
    }
}
