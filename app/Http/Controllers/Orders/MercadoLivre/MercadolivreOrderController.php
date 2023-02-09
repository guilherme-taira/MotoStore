<?php

namespace App\Http\Controllers\Orders\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Models\order_site;
use App\Models\product_site;
use DateTime;
use Illuminate\Http\Request;

class MercadolivreOrderController implements InterfaceMercadoLivre
{

    const URL_BASE_ML = "https://api.mercadolibre.com/";

    private String $sellerId;
    private String $token;

    public function __construct($sellerId,$token)
    {
        $this->sellerId = $sellerId;
        $this->token = $token;
    }

    public function getVenda($sellerId)
    {

    }

    public function saveOrder(){

    }

    public function get($resource){
         // ENDPOINT PARA REQUISICAO
         $endpoint = self::URL_BASE_ML.$resource;
         /**
         * CURL REQUISICAO -X GET
         * **/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.mercadolibre.com/orders/search/recent?seller=141075614&sort=date_desc");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$this->getToken()}"]);
        $reponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json = json_decode($reponse);
        echo "<pre>";
        foreach ($json->results as $result) {
            foreach ($result->payments as $pedido) {
                print_r($pedido);
                // PEDIDO NOVO
                $pedidos = new order_site();
                $pedidos->numeropedido = $pedido->order_id;
                $pedidos->local = 'Mercado Livre';
                $pedidos->valorVenda = $pedido->total_paid_amount;
                $pedidos->valorProdutos = 1;
                $pedidos->dataVenda = $pedido->date_approved;
                $pedidos->save();

                $produto = new product_site();
                $produto->nome = $pedido->reason;
                $produto->codigo = 10;
                $produto->valor = 0;
                $produto->quantidade = 1;
            }
        }
    }

    public function resource(){
       return $this->get("https://api.mercadolibre.com/orders/search?seller=".$this->getSellerId()."&order.status=paid");
    }


    /**
     * Get the value of sellerId
     */
    public function getSellerId(): String
    {
        return $this->sellerId;
    }

    /**
     * Set the value of sellerId
     */
    public function setSellerId(String $sellerId): self
    {
        $this->sellerId = $sellerId;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken(): String
    {
        return $this->token;
    }

    /**
     * Set the value of token
     */
    public function setToken(String $token): self
    {
        $this->token = $token;

        return $this;
    }

}
