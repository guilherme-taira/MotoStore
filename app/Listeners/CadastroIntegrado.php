<?php

namespace App\Listeners;

use App\Events\EventoCadastroIntegrado;
use App\Models\produtos_integrados;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $prod->priceNotFee = $event->priceNotFee;

        if (isset($event->dados['valor_tipo']) && $event->dados['valor_tipo']) {
            // Atualiza os campos conforme a regra de negócio
            if ($event->dados['valor_tipo'] == 'acrescimo_reais') {
                $prod->acrescimo_reais = $event->dados['valor'];
            } elseif ($event->dados['valor_tipo'] == 'acrescimo_porcentagem') {
                $prod->acrescimo_porcentagem = $event->dados['valor'];
            } elseif ($event->dados['valor_tipo'] == 'desconto_reais') {
                $prod->desconto_reais = $event->dados['valor'];
            } elseif ($event->dados['valor_tipo'] == 'desconto_porcentagem') {
                $prod->desconto_porcentagem = $event->dados['valor'];
            }
        }

        if (isset($event->dados['precofixo']) && $event->dados['precofixo']) {
            $prod->precofixo = $event->dados['precofixo'];
        }

        if (isset($event->dados['isPorcem']) && $event->dados['isPorcem']) {
            $prod->isPorcem = $event->dados['isPorcem'];
        }

        $prod->save();
    }
}
