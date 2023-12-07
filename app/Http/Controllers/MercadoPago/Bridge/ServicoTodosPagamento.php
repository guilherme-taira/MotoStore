<?php

namespace App\Http\Controllers\MercadoPago\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MercadoPago\SDK as ML;
use MercadoPago\Preference as MercadoPreference;
use MercadoPago\Item as MercadoItem;
use MercadoPago\Payer as payer;

class ServicoTodosPagamento implements InterfacePagamento
{
    public function GerarPagamentoMercadoPago($item){

        ML::setAccessToken("APP_USR-3029233524869952-112215-0011d4d10155cda8e855a3a6a593f1cc-1272736385");
        $preference = new MercadoPreference();
        // Cria um item na preferência

        $preference->items = $item;
        $preference->external_reference = uniqid('afilidrop');

        $preference->back_urls = array(
            "success" => 'http://afilidrop.herokuapp.com/success',
            "failure" => 'http://afilidrop.herokuapp.com/failure',
            "pending" => 'http://afilidrop.herokuapp.com/pending',
        );

        $preference->notification_url = "http://afilidrop.herokuapp.com";
        $preference->save();

        print_r($preference);

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
