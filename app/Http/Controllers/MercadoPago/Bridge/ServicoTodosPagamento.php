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

        ML::setAccessToken("APP_USR-3029233524869952-033008-6f7fbb3eb9e6d2626a3b7eec9ac6b5d6-1272736385");
        $preference = new MercadoPreference();
        // Cria um item na preferência
        echo "<pre>";
        print_r($item);
        $preference->items = $item;
        $preference->external_reference = uniqid('afilidrop');

        $preference->back_urls = array(
            "success" => 'http://afilidrop.herokuapp.com/success',
            "failure" => 'http://afilidrop.herokuapp.com/failure',
            "pending" => 'http://afilidrop.herokuapp.com/pending',
        );

        $preference->notification_url = "http://afilidrop.herokuapp.com";
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
