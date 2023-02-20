<?php

namespace App\Http\Controllers\Orders\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Models\order_site;
use App\Models\pivot_site;
use App\Models\product_site;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MercadolivreOrderController implements InterfaceMercadoLivre
{

    const URL_BASE_ML = "https://api.mercadolibre.com/";

    private String $sellerId;
    private String $token;

    public function __construct($sellerId, $token)
    {
        $this->sellerId = $sellerId;
        $this->token = $token;
    }

    public function getVenda($sellerId)
    {
    }

    public function saveOrder()
    {
    }

    public function get($resource)
    {
        // ENDPOINT PARA REQUISICAO
        $endpoint = self::URL_BASE_ML . $resource;
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
        echo "<pre>";
        if ($httpCode == 200) {
            foreach ($json->results as $result) {
                /***
                 * IMPLEMENTAÇÃO DO SELLER ID PARA PEGAR OS DADOS PARA GERAR O PIX NA CONTA
                 * DADOS ESSES COMO ENDEREÇO COMPLETO E DADOS PESSOAIS COMO NOME, CPF OU CNPJ
                 */

                print_r($result->buyer->id . "<br>");
                // $newPix = new Pix("e1cb0277fbfd2fd","B","13616-450","Siqueira Campos","70","A","São Manoel","Leme","SP","Guilherme Taira","46857167877","gui_ssx@hotmail.com",[new ProdutoMercadoLivre("cotonete",2,7.99),new ProdutoMercadoLivre("cotonete",2,7.99)],27,1);
                // $data = $newPix->CriarPagamento();
                // echo "<pre>";
                // print_r($data);
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
                }
            }
        }
    }

    public function resource()
    {
        return $this->get("orders/search?seller=" . $this->getSellerId() . "&order.status=paid&sort=date_desc");
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
