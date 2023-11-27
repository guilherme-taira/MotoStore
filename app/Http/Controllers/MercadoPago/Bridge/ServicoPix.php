<?php

namespace App\Http\Controllers\MercadoPago\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MercadoPago\SDK as ML;
use MercadoPago\Payment as MercadoPreference;
use MercadoPago\Item as MercadoItem;
use MercadoPago\Payer as payer;

class ServicoPix implements InterfacePagamento
{
    public function GerarPagamentoMercadoPago($item){

        $payment = new MercadoPreference();
        // Cria um item na preferência
        $payment->transaction_amount = 200; // Valor do pagamento
        $payment->payment_method_id = "pix"; // Método de pagamento
        $payment->description = 'Meu produto'; //

        $payment->payer = array(
            'email' => 'gui_ssx@hotmaisl.com',
        );

        $payment->save();
        $data = [];
        $data['link'] = $payment->point_of_interaction->transaction_data->ticket_url;
        $data['qrcode'] = $payment->point_of_interaction->transaction_data->qr_code_base64;
        return $data;
    }

    public function getValor($data){
        $total = 0;
        foreach ($data as $key => $value) {
            $total += $value->unit_price * $value->quantity;
        }
        return $total;
    }
}
