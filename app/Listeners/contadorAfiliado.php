<?php

namespace App\Listeners;

use App\Events\EventoAfiliado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class contadorAfiliado
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
     * @param  \App\Events\EventoAfiliado  $event
     * @return void
     */
    public function handle(EventoAfiliado $event)
    {
        $event->produto->afiliados++;
        $event->produto->save();
    }
}
