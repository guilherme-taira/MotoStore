<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LancarContaReceber extends abstractCreatePagamento
{
    public function criarPagamento($status_id, $totalPago, $id_order, $fornecedor, $urlPagamento, $qrcode, $nomeStatus, $token, $frete): CreatePagamento
    {
        foreach ($this->VerificaProdutos() as $key => $value) {

            $pagamento = new CreatePagamento();
            $pagamento->pagamento($status_id, $pagamento->retirarTarifa($value['price'] * $value['quantidade']), $id_order, $value['fornecedor'], $urlPagamento, $qrcode, $nomeStatus, $token, $frete);
        }
        return $pagamento;
    }
}
