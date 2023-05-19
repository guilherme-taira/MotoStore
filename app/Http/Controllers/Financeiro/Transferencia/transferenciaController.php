<?php

namespace App\Http\Controllers\Financeiro\Transferencia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MercadoPago\SDK as ML;
use MercadoPago\Payment as MercadoPreference;

class transferenciaController extends abstractCriartransferencia
{
    public function chamarTransferencia()
    {
        $transfer = new ML();
        $transfer->setAccessToken("SEU_TOKEN_DE_ACESSO");
        $transfer->setIntegratorId("SEU_ID_DE_INTEGRADOR");

        $transfer_data = array(
            "payer_id" => "ID_DO_PAGADOR",
            "payment_id" => "1272736385-fe0052dd-943a-423a-b2c8-ed802a6d08cb",
            "amount" => 1
        );

        $transfer_result = $transfer->payment()->create_transfer($transfer_data);

        // exibindo o resultado
        var_dump($transfer_result);
    }
}
