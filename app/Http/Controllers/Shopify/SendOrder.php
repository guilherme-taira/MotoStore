<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SendOrder extends Controller
{

    // const URL_BASE_SHOPIFY = "";

    private $order;
    private $loja;
    private $token;

    public function __construct(Order $order,$loja,$token)
    {
        $this->order = $order;
        $this->loja = $loja;
        $this->token = $token;
    }


    public function get($resource)
    {

       // URL PARA REQUISICAO
        $endpoint = $this->getLoja() . $resource;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->toJson());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "X-Shopify-Access-Token: {$this->getToken()}"]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = json_decode($response);
        curl_close($ch);
        Log::debug($response);
    }

    public function resource()
    {
        return $this->get("orders.json");
    }
    public function toJson(){
        return json_encode(['order' => $this->getOrder()]);
    }
    /**
     * Get the value of order
     */
    public function getOrder()
    {
        return $this->order;
    }



    /**
     * Set the value of order
     */
    public function setOrder($order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get the value of loja
     */
    public function getLoja()
    {
        return $this->loja;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {
        return $this->token;
    }
}
