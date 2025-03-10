<?php

namespace App\Http\Controllers\MercadoLivre\Cliente;

use App\Http\Controllers\Controller;
use App\Models\order_site;
use App\Models\pivot_site;
use App\Models\product_site;
use App\Models\produtos_integrados;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InterfaceClienteController implements ClienteController
{

    const URL_BASE = "https://api.mercadolibre.com";

    private String $token;
    private String $Userid;
    private String $exeternal_reference;
    private String $link_pagamento;
    private String $preference_id;
    private String $fee;
    private String $comprador;


    public function __construct($Userid,$token,$exeternal_reference,$link_pagamento,$preference_id, $fee,$comprador)
    {
        $this->Userid = $Userid;
        $this->token = $token;
        $this->exeternal_reference = $exeternal_reference;
        $this->link_pagamento = $link_pagamento;
        $this->preference_id = $preference_id;
        $this->fee = $fee;
        $this->comprador = $comprador;
    }

    public function get($resource)
    {
        // ENDPOINT PARA REQUISICAO
        $endpoint = self::URL_BASE . $resource;
        /**
         * CURL REQUISICAO -X GET
         * **/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$this->getToken()}"]);
        $reponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json = json_decode($reponse);
        //print_r($json);
        Log::alert($reponse);
    }

    public function resource()
    {
        return $this->get("/users/{$this->getUserid()}");
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

    /**
     * Get the value of Userid
     */
    public function getUserid(): String
    {
        return $this->Userid;
    }

    /**
     * Set the value of Userid
     */
    public function setUserid(String $Userid): self
    {
        $this->Userid = $Userid;

        return $this;
    }

    function saveClient($result,$sellerid)
    {

        try {
            if (order_site::VerificarVenda($result->id) == false) {
                // Tenta encontrar ou criar o pedido com base no campo numeropedido
                $pedidos = order_site::firstOrCreate(
                    ['numeropedido' => $result->id],
                    [
                        'local'               => 'Mercado Livre',
                        'valorVenda'          => $result->paid_amount,
                        'valorProdutos'       => $result->total_amount,
                        'dataVenda'           => date('Y-m-d', strtotime($result->date_closed)),
                        'cliente'             => $result->buyer->nickname,
                        'status_id'           => 3,
                        'preferenceId'        => $this->getPreferenceId(),
                        'external_reference'  => $this->getExeternalReference(),
                        'status_mercado_livre'=> "0",
                        'id_pagamento'        => 0,
                        'link_pagamento'      => $this->getLinkPagamento(),
                        'fee'                 => $this->getFee(),
                        'buyer'               => $this->getComprador(),
                    ]
                );

           // Se o pedido foi recém-criado, insere os produtos e relaciona via pivot_site
            if ($pedidos->wasRecentlyCreated) {
                Log::alert($pedidos->id);
                foreach ($result->order_items as $pedido) {
                    if (product_site::getVerifyProduct($pedido->item->seller_sku) == true) {
                        // Cria o produto
                        $produto = new product_site();
                        $produto->nome       = $pedido->item->title;
                        $produto->codigo     = $pedido->item->seller_sku ?? 0;
                        $produto->valor      = $pedido->unit_price;
                        $produto->quantidade = $pedido->quantity;
                        $produto->seller_sku = $pedido->item->seller_sku ?? 0;
                        $produto->image      = $this->getPicture($pedido->item->id);
                        $produto->save();

                        // Cria o registro na tabela pivot
                        $userid = token::getId($sellerid);
                        $venda_pivot = new pivot_site();
                        $venda_pivot->order_id   = $pedidos->id;
                        $venda_pivot->product_id = $produto->id;
                        $venda_pivot->id_user    = $userid;
                        $venda_pivot->save();
                    } else {
                        // Cria o produto (para o caso em que getVerifyProduct retorna false)
                        $sku = $pedido->item->seller_sku ?? 0;
                        $produto = new product_site();
                        $produto->nome       = $pedido->item->title;
                        $produto->codigo     = $pedido->item->seller_sku ?? 0;
                        $produto->valor      = $pedido->unit_price;
                        $produto->quantidade = $pedido->quantity;
                        $produto->seller_sku = $pedido->item->seller_sku ?? 0;
                        $produto->image      = $this->getPicture($pedido->item->id);
                        $produto->save();

                        // Cria o registro na tabela pivot
                        $userid = token::getId($sellerid);
                        $venda_pivot = new pivot_site();
                        $venda_pivot->order_id   = $pedidos->id;
                        $venda_pivot->product_id = $produto->id;
                        $venda_pivot->id_user    = $userid;
                        $venda_pivot->save();

                        // Remove o estoque do produto
                        produtos_integrados::removeStockProduct($sku, $pedido->quantity);
                    }
                }
            }

            // Retorna o ID do pedido (novo ou existente)
            return $pedidos->id;

            }

        } catch (\Exception $th) {
           Log::critical($th->getMessage());
        }


    }

    public function getPicture($id){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/items/$id");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$this->getToken()}"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reponse = curl_exec($ch);
        Log::alert($reponse);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json = json_decode($reponse);
        return $json->thumbnail;
    }

    /**
     * Get the value of exeternal_reference
     */
    public function getExeternalReference(): String
    {
        return $this->exeternal_reference;
    }

    /**
     * Get the value of link_pagamento
     */
    public function getLinkPagamento(): String
    {
        return $this->link_pagamento;
    }

    /**
     * Get the value of preference_id
     */
    public function getPreferenceId(): String
    {
        return $this->preference_id;
    }

    /**
     * Get the value of fee
     */
    public function getFee(): String
    {
        return $this->fee;
    }

    /**
     * Get the value of comprador
     */
    public function getComprador(): String
    {
        return $this->comprador;
    }
}
