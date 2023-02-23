<?php

namespace App\Http\Controllers\Yapay;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Cliente\ClienteController;
use App\Http\Controllers\MercadoLivre\Cliente\implementacaoCliente;
use Illuminate\Http\Request;

class GeradorPagamento implements ClienteController
{

    private implementacaoCliente $data;

    public function __construct(implementacaoCliente $data)
    {
        $this->data = $data;
    }

    const URL_BASE = "https://api.intermediador.yapay.com.br/";

    public function get($resource){
        // GET DATA JSON CONVERTE
        $data_json = json_encode($this->getData()->CriarPagamento());
        // ENDPOINT PARA REQUISICAO
        $endpoint = self::URL_BASE . $resource;
        // CURL POST EXEC
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'content-type:application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $dados = json_decode($response);
        return $dados->data_response->transaction;
    }

    public function resource()
    {
        return $this->get('/api/v3/transactions/payment');
    }

    /**
     * Get the value of data
     */
    public function getData(): implementacaoCliente
    {
        return $this->data;
    }

    /**
     * Set the value of data
     */
    public function setData(implementacaoCliente $data): self
    {
        $this->data = $data;

        return $this;
    }
}
