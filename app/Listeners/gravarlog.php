<?php

namespace App\Listeners;

use App\Events\logAlteracao;
use App\Models\log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class gravarlog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\logAlteracao  $event
     * @return void
     */
    public function handle(logAlteracao $event)
    {
        $log = new log();
        $log->acao = $event->acao;
        $log->user_id = $event->user;
        $log->message = $event->message;
        $log->sucesso = $event->sucesso;
        $log->save();
    }
}
