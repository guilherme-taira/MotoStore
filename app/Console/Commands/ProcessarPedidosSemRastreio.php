<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShippingUpdate;

class ProcessarPedidosSemRastreio extends Command
{
    // Nome e descrição do comando
    protected $signature = 'processar:pedidos-sem-rastreio';
    protected $description = 'Processa os pedidos que estão com o id_rastreio igual a null a cada 5 minutos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Busca todos os pedidos onde o id_rastreio é null
        $pedidosSemRastreio = ShippingUpdate::whereNull('id_rastreio')
        ->whereNotNull('rastreio') // Campo rastreio já preenchido
        ->where('observacaoMeli', 'X') // Campo observacaoMeli é "X"
        ->get();


        foreach ($pedidosSemRastreio as $pedido) {
            // Aqui você pode processar cada pedido
            // Por exemplo: gerar o código de rastreio, fazer chamadas para APIs externas, etc.
            $this->info("RASTREIO " . $pedido->rastreio);
            \App\Jobs\sendRastreioSaiuPraEnrega::dispatch($pedido->rastreio);
        }

        $this->info('Todos os pedidos sem rastreio foram processados.');
    }
}
