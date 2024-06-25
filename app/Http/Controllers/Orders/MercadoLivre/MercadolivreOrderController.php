<?php

namespace App\Http\Controllers\Orders\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Cliente\getDataShippingController;
use App\Http\Controllers\MercadoLivre\Cliente\implementacaoCliente;
use App\Http\Controllers\MercadoLivre\Cliente\InterfaceClienteController;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Http\Controllers\MercadoPago\Pagamento\MercadoPagoCesta;
use App\Http\Controllers\MercadoPago\Pagamento\MercadoPagoItem;
use App\Http\Controllers\MercadoPago\Pagamento\MercadoPagoPreference;
use App\Http\Controllers\Yapay\GeradorPagamento;
use App\Http\Controllers\Yapay\ProdutoMercadoLivre;
use App\Models\financeiro;
use App\Models\order_site;
use App\Models\pivot_site;
use App\Models\product_site;
use App\Models\Products;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as FacadesLog;

class MercadolivreOrderController implements InterfaceMercadoLivre
{

    const URL_BASE_ML = "https://api.mercadolibre.com/";

    private String $resource;
    private String $topic;
    private String $sellerId;
    private String $token;

    public function __construct($resource,$topic, $sellerId, $token)
    {
        $this->resource = $resource;
        $this->topic = $topic;
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
        // echo "<pre>";

        // IMPLEMENTA MARKETPLACE FEE

        // FacadesLog::critical($json->payments[0]->marketplace_fee);

        try {
            if ($httpCode == 200) {

                // ARRAY DE PRODUTOS
                    $produtos = [];
                    // IMPLEMENTACAO DO CARRINHO CESTA PARA PRODUTOS
                    $carrinhoCesta = new MercadoPagoCesta();
                    foreach ($json->payments as $payments) {

                        foreach ($json->order_items as $items) {

                        // PEGA O VALOR DO PRODUTO
                        $produto = Products::where('id',$items->item->seller_sku)->first();
                        // COLOCA O PRODUTO EM CESTA
                        if(isset($produto)){
                            $item = new MercadoPagoItem($items->item->seller_sku,$items->item->title,intVal($items->quantity),"BRL",$produto->price);
                            $carrinhoCesta->addProdutos($item);
                        }
                            array_push($produtos, new ProdutoMercadoLivre($items->item->title, $items->quantity, $items->unit_price));
                        }

                        /***
                         * IMPLEMENTAÇÃO DO SELLER ID PARA PEGAR OS DADOS PARA GERAR O PIX NA CONTA
                         * DADOS ESSES COMO ENDEREÇO COMPLETO E DADOS PESSOAIS COMO NOME, CPF OU CNPJ
                         */
                            if (order_site::VerificarVenda($json->id) == false) {

                                  // * FORMA DE PAGAMENTO NOVA *//
                            /**
                             * IMPLANTACAO DO SISTEMA DE PAGAMENTO SPLI MERCADO PAGO
                             *  SAIDA DO SDK DO MERCADO PAGO PARA IMPLEMENTAR DE FORMA MANUAL
                             *  16/04/2024 11:20
                             */


                            if(isset($produto)){
                                $prefence = new MercadoPagoPreference($carrinhoCesta,'https://www.hub.embaleme.com.br/webhook/mpago/webhooktest.php');
                                $preference = $prefence->resource();

                                $cliente = new InterfaceClienteController($json->buyer->id, $this->getToken(),$preference['external_reference'],$preference['init_point'],$preference['id'],$json->payments[0]->marketplace_fee);
                                $cliente->resource();
                                $id_order = $cliente->saveClient($json,$this->getSellerId());

                                $shipping = isset($json->shipping->id) ? $json->shipping->id : 0;
                                financeiro::SavePayment(3, $payments->total_paid_amount, $id_order, Auth::user()->id, $preference['init_point'], "S/N","aguardando pagamento",$preference['external_reference'],$shipping);
                                financeiro::SavePayment(3, $payments->total_paid_amount, $id_order, $produto->fornecedor_id, $preference['init_point'], "S/N","aguardando pagamento",$preference['external_reference'],$shipping);
                            }else{
                                $cliente = new InterfaceClienteController($json->buyer->id, $this->getToken(),"N/D","N/D","1",$json->payments[0]->marketplace_fee);
                                $cliente->resource();
                                $id_order = $cliente->saveClient($json,$this->getSellerId());
                            }
                        }
                    }
                }
        } catch (\Exception $th) {
            FacadesLog::critical($th->getMessage());
        }

    }

    public function resource()
    {
        return $this->get($this->getResource());
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

    /**
     * Get the value of topic
     */
    public function getTopic(): String
    {
        return $this->topic;
    }

    /**
     * Get the value of resource
     */
    public function getResource(): String
    {
        return $this->resource;
    }
}
