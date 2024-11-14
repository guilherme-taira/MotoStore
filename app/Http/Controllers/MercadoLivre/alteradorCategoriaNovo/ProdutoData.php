<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProdutoData extends Controller
{
    const URL_BASE = "https://api.mercadolibre.com/";

    private $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function get($resource){

        $endpoint = self::URL_BASE.$resource;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $data = json_decode($response,true);
        return $data;
    }

    public function resource(){
        return $this->get("items/{$this->product}");
    }
}
