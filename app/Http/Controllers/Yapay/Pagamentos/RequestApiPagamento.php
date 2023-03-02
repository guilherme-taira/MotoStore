<?php

namespace App\Http\Controllers\Yapay\Pagamentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestApiPagamento extends Controller
{
    public const URL_BASE_YAPAY = "https://api.intermediador.yapay.com.br";

    private PagamentoController $token;
    private String $token_account;

    public function __construct(PagamentoController $token)
    {
        $this->token_account = "e1cb0277fbfd2fd";
        $this->token = $token;
    }

    public function get($resource)
    {
        // ENDPOINT PARA REQUISICAO
        $endpoint = self::URL_BASE_YAPAY . $resource;
        /**
         * CURL REQUISICAO -X GET
         * **/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json = json_decode($reponse,true);
        print_r($json);
        if ($httpCode == 200) {
            return $json;
        }
    }
    public function resource()
    {
        return $this->get("/api/v3/transactions/get_by_token_brief?token_account={$this->getTokenAccount()}&token_transaction={$this->getToken()->getToken()}");
    }

    /**
     * Get the value of token
     */
    public function getToken(): PagamentoController
    {
        return $this->token;
    }

    /**
     * Set the value of token
     */
    public function setToken(PagamentoController $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of token_account
     */
    public function getTokenAccount(): String
    {
        return $this->token_account;
    }

    /**
     * Set the value of token_account
     */
    public function setTokenAccount(String $token_account): self
    {
        $this->token_account = $token_account;

        return $this;
    }
}
