<?php

namespace App\Http\Controllers\MercadoLivre\Cliente;

use App\Http\Controllers\Controller;
use App\Models\order_site;
use App\Models\pivot_site;
use App\Models\product_site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterfaceClienteController implements ClienteController
{

    const URL_BASE = "https://api.mercadolibre.com";

    private String $token;
    private String $Userid;

    public function __construct($Userid,$token)
    {
        $this->Userid = $Userid;
        $this->token = $token;
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

    function saveClient($result)
    {

        if (order_site::VerificarVenda($result->id) == false) {

            $pedidos = new order_site();
            $pedidos->numeropedido = $result->id;
            $pedidos->local = 'Mercado Livre';
            $pedidos->valorVenda = $result->paid_amount;
            $pedidos->valorProdutos = $result->total_amount;
            $pedidos->dataVenda = $result->date_closed;
            $pedidos->cliente = $result->buyer->nickname;
            $pedidos->save();

            foreach ($result->order_items as $pedido) {
                if (product_site::getVerifyProduct($pedido->item->seller_sku) == false) {
                    // PEDIDO NOVO
                    $produto = new product_site();
                    $produto->nome = $pedido->item->title;
                    $produto->codigo = isset($pedido->item->seller_sku) ? $pedido->item->seller_sku : 0;
                    $produto->valor = $pedido->unit_price;
                    $produto->quantidade = $pedido->quantity;
                    $produto->seller_sku = isset($pedido->item->seller_sku) ? $pedido->item->seller_sku : 0;
                    $produto->save();
                    // PIVOT
                    $venda_pivot = new pivot_site();
                    $venda_pivot->order_id = $pedidos->id;
                    $venda_pivot->product_id = $produto->id;
                    $venda_pivot->id_user = Auth::user()->id;
                    $venda_pivot->save();
                }
            }
            // RETORNA O ID DO PEDIDO PARA GRAVAR.
            return $pedidos->id;
        }
    }
}
