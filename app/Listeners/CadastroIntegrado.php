<?php

namespace App\Listeners;

use App\Events\EventoCadastroIntegrado;
use App\Models\produtos_integrados;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class CadastroIntegrado
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\EventoCadastroIntegrado  $event
     * @return void
     */
    public function handle(EventoCadastroIntegrado $event)
    {
        $prod = new produtos_integrados();
        $prod->name = $event->name;
        $prod->image = $event->image;
        $prod->id_mercadolivre = $event->id_mercadolivre;
        $prod->product_id = $event->product_id;
        $prod->user_id = Auth::user()->id;
        $prod->save();
    }
}
