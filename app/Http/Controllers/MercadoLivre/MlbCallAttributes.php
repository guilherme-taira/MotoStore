<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MlbCallAttributes implements InterfaceTipos
{
    private $MlbTipos;

    const URL_BASE_MERCADOLIVRE = "https://api.mercadolibre.com/";

    public function __construct(MlbTipos $MlbTipos)
    {
        $this->MlbTipos = $MlbTipos;
    }

    public function get($resource){
        // URL PARA REQUISICAO
        $endpoint = self::URL_BASE_MERCADOLIVRE . $resource;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = json_decode($response);
        curl_close($ch);
        return $res;
    }

    public function resource(){
       return $this->get("domains/{$this->MlbTipos->getTipo()}/technical_specs");
    }

}
