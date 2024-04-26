<?php

namespace App\Http\Controllers\MercadoPago\Bridge;

use App\Http\Controllers\Controller;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use MercadoPago\SDK as ML;
use MercadoPago\Preference as MercadoPreference;
use MercadoPago\Item as MercadoItem;
use MercadoPago\Payer as payer;

class ServicoTodosPagamento implements InterfacePagamento
{
    public function GerarPagamentoMercadoPago($item){

        // $token = token::where('user_id',Auth::user()->id)->first();
        ML::setAccessToken('APP_USR-1040870078984189-112414-53f0b1b08d3103e224c9276ae6e21808-1562628572');
        $preference = new MercadoPreference();
        // Cria um item na preferência

        $preference->application_fee = 3;
        $preference->items = $item;
        $preference->external_reference = uniqid('afilidrop');

        // Log::critical(json_encode($preference));

        $preference->back_urls = array(
            "success" => 'https://melimaximo.com.br',
            "failure" => 'https://melimaximo.com.br',
            "pending" => 'https://melimaximo.com.br',
        );

        // $preference->payment_methods = array(
        //     "excluded_payment_methods" => array(
        //       array("id" => "visa")
        //     ),
        //     "installments" => 6
        //   );

        $preference->notification_url = "https://www.hub.embaleme.com.br/webhook/webhooktest.php";
        $preference->save();


        $data = [];
        $data['id'] = $preference->id;
        $data['external_reference'] = $preference->external_reference;
        $data['init_point'] = $preference->init_point;


        return $data;
    }

    public function getValor($data){
        $total = 0;
        foreach ($data as $key => $value) {
            $total += $value->unit_price;
        }
        return $total;
    }
}
