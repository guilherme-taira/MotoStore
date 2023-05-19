<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\financeiro;
use Illuminate\Http\Request;

class CreatePagamento implements InterfacePagamentos
{
    public function pagamento($status_id,$totalPago,$id_order,$forncedor,$urlPagamento,$qrcode,$nomeStatus,$token_transaction,$frete){
        financeiro::SavePayment($status_id, $totalPago, $id_order,$forncedor, $urlPagamento, $qrcode,$nomeStatus,$token_transaction,$frete);
    }

    public function retirarTarifa($valor){
        $total = $valor - ($valor * 0.05);
        return $total;
    }
}
