<?php

namespace App\Http\Controllers\Bling;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlingApiService extends Controller
{
    private $endpoint;
    private $authorization;

    public function __construct($authorization)
    {
        $this->endpoint = "https://api.bling.com.br/Api/v3/pedidos/vendas";
        $this->authorization = $authorization;
    }

    /**
     * Envia uma venda para a API do Bling.
     *
     * @param array $data Corpo da requisição
     * @return array Resposta da API
     * @throws Exception
     */
    public function sendSale(array $data)
    {
        $response = Http::withHeaders([
            'Authorization' =>'Bearer '.$this->authorization,
            'Content-Type' => 'application/json',
        ])->post($this->endpoint, $data);

        if ($response->failed()) {
            throw new Exception("Erro ao enviar venda para o Bling: " . $response->body());
        }

        return $response->json();
    }
}
