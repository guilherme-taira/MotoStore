<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

interface InterfacePagamentos
{
    public function pagamento($status_id,$totalPago,$id_order,$forncedor,$urlPagamento,$qrcode,$nomeStatus,$token_transaction,$frete);
    public function retirarTarifa(float $valor);
}
