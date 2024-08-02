<?php

namespace App\Http\Controllers\Orders\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Cliente\getDataShippingController;
use App\Http\Controllers\MercadoLivre\Cliente\implementacaoCliente;
use App\Http\Controllers\MercadoLivre\Cliente\InterfaceClienteController;
use App\Http\Controllers\MercadoLivre\getShippingData;
use App\Http\Controllers\MercadoLivre\LineItemNull;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Http\Controllers\MercadoPago\Pagamento\MercadoPagoCesta;
use App\Http\Controllers\MercadoPago\Pagamento\MercadoPagoItem;
use App\Http\Controllers\MercadoPago\Pagamento\MercadoPagoPreference;
use App\Http\Controllers\Shopify\LineItem;
use App\Jobs\putDraftShopifyOrder;
use App\Http\Controllers\Shopify\Order;
use App\Http\Controllers\Shopify\SendOrder;
use App\Http\Controllers\Shopify\ShippingAddress;
use App\Http\Controllers\Shopify\ShopifyProduct;
use App\Http\Controllers\Yapay\GeradorPagamento;
use App\Http\Controllers\Yapay\ProdutoMercadoLivre;
use App\Models\financeiro;
use App\Models\order_site;
use App\Models\pivot_site;
use App\Models\product_site;
use App\Models\Products;
use App\Models\ShippingUpdate;
use App\Models\Shopify;
use App\Models\token;
use Aws\Token\Token as TokenToken;
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$this->getToken()}", "x-format-new: true"]);
        $reponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json = json_decode($reponse);
        // echo "<pre>";

        // IMPLEMENTA MARKETPLACE FEE
        // FacadesLog::critical($reponse);

        try {
            if ($httpCode == 200) {

                // ARRAY DE PRODUTOS
                    $produtos = [];
                    // IMPLEMENTACAO DO CARRINHO CESTA PARA PRODUTOS
                    $carrinhoCesta = new MercadoPagoCesta();
                    $shipping = isset($json->shipping->id) ? $json->shipping->id : 0;

                    $user = token::where('user_id_mercadolivre','=',$this->getSellerId())->first();
                    $getLink = Shopify::getLink($user->user_id);

                    $line_item = [];
                    // IMPLEMENTAR DADOS DA SHOPIFY ********
                    foreach ($json->order_items as $items) {
                        // PRODUTOS DO ARRAY DA SHOPIFY
                        $produto = new ShopifyProduct($items->item->seller_sku,$getLink);
                        $verificar = $produto->resource();
                        if(isset($verificar['variant'])){
                            $line_item[] = new LineItem($items->item->seller_sku, $items->quantity);
                        }else{
                            $line_item[] = new LineItem("ND", $items->quantity);
                        }

                    }
                    $shippingClient = new getShippingData($shipping,$this->getToken());
                    $dados = $shippingClient->resource();

                    // PEGA OS DADOS DA INTEGRACAO SHOPIFY
                    try {

                        if($getLink->comunicando == 1 && $dados['transportadora'] == NULL){

                            if(ShippingUpdate::ifExist($json->id)){

                            $this->storeShipping("D",$json->id,$json->buyer->id,$json->seller->id);

                            $shipping_address = new ShippingAddress($dados['first_name'],$dados['address1']
                            ,$this->isNumericString($dados['phone'],$getLink->telefone) ? $dados['phone'] : $getLink->telefone,$dados['city'],$dados['zip'],$dados['province'],$dados['country'],
                            $dados['last_name'],$dados['address2'],$dados['company'],$dados['name'],$dados['country_code'],
                            $dados['province_code']);
                            $nota = $json->id . " - " .$json->buyer->nickname;
                            $order = new Order($line_item, "paid", "BRL", $shipping_address,$nota,isset($json->buyer->email) ? $json->buyer->email :$getLink->email);
                                // Print the order object to verify its structure
                                $data = new SendOrder($order,$getLink->name_loja,$getLink->token);
                                $id_shopifyOrder = $data->resource();
                                // COLOCA NA FILA A CONVERSAO DE RASCUNHO PARA PEDIDO
                                \App\Jobs\putDraftShopifyOrder::dispatch($getLink,$id_shopifyOrder->draft_order->id,$json->id,$json->buyer->id,$json->seller->id);

                            }

                        }

                    } catch (\Throwable $th) {
                        FacadesLog::emergency($th->getMessage());
                    }
                    // FIM -*************

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

                                $prefence = new MercadoPagoPreference($carrinhoCesta,'https://www.hub.embaleme.com.br/webhook/mpago/webhooktest.php',$json->seller->id);
                                $preference = $prefence->resource();

                                $cliente = new InterfaceClienteController($json->buyer->id, $this->getToken(),$preference['external_reference'],$preference['init_point'],$preference['id'],$json->payments[0]->marketplace_fee,$shipping);
                                $cliente->resource();
                                $id_order = $cliente->saveClient($json,$this->getSellerId());

                                financeiro::SavePayment(3, $payments->total_paid_amount, $id_order, Auth::user()->id, $preference['init_point'], "S/N","aguardando pagamento",$preference['external_reference'],$shipping);
                                financeiro::SavePayment(3, $payments->total_paid_amount, $id_order, $produto->fornecedor_id, $preference['init_point'], "S/N","aguardando pagamento",$preference['external_reference'],$shipping);
                            }else{

                                $cliente = new InterfaceClienteController($json->buyer->id, $this->getToken(),"N/D","N/D","1",$json->payments[0]->marketplace_fee,$json->shipping->id);
                                $cliente->resource();
                                $id_order = $cliente->saveClient($json,$this->getSellerId());
                            }

                        }

                    }
                }
        } catch (\Exception $th) {
            FacadesLog::critical($th->getMessage());
        }

        return response()->json(["msg" => "cadastrado"],200);
    }

    public function storeShipping($id_shopify,$id_mercadoLivre,$id_user,$id_vendedor){
        // Dados para criar ou atualizar
        $data = [
            'id_shopify' => $id_shopify,
            'isBrazil' => false,
            'id_mercadoLivre' => $id_mercadoLivre,
            'id_user' => $id_user,
            'id_vendedor' => $id_vendedor,
        ];

        // Condições para encontrar o registro
        $conditions = [
            'id_shopify' => $data['id_shopify'],
            'id_mercadoLivre' => $data['id_mercadoLivre'],
        ];

        // Crie ou atualize o registro
        ShippingUpdate::updateOrCreate($conditions, $data);
    }


    function isNumericString($var,$telefone) {
         if(ctype_digit($var)){
            return $var;
         }else{
            return $telefone;
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
