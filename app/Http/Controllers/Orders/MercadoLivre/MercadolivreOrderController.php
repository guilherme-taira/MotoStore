<?php

namespace App\Http\Controllers\Orders\MercadoLivre;

use App\Http\Controllers\Bling\BlingApiService;
use App\Http\Controllers\Bling\BlingContatos;
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
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\Shopify\LineItem;
use App\Jobs\putDraftShopifyOrder;
use App\Http\Controllers\Shopify\Order;
use App\Http\Controllers\Shopify\SendOrder;
use App\Http\Controllers\Shopify\ShippingAddress;
use App\Http\Controllers\Shopify\ShopifyProduct;
use App\Http\Controllers\Yapay\GeradorPagamento;
use App\Http\Controllers\Yapay\ProdutoMercadoLivre;
use App\Models\BlingCreateUserByFornecedor;
use App\Models\Contato;
use App\Models\financeiro;
use App\Models\IntegracaoBling;
use App\Models\kit;
use App\Models\order_site;
use App\Models\pivot_site;
use App\Models\product_site;
use App\Models\Products;
use App\Models\ShippingUpdate;
use App\Models\Shopify;
use App\Models\token;
use App\Notifications\notificaUserOrder;
use AWS\CRT\Log;
use Aws\Token\Token as TokenToken;
use Carbon\Carbon;
use DateTime;
use App\Models\User;
use App\Notifications\notificaSellerOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;


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
        FacadesLog::critical($reponse);

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

                    if($getLink != null){
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
                   }

                    $shippingClient = new getShippingData($shipping,$this->getToken(),$json);
                    $dados = $shippingClient->resource();

                    // FacadesLog::debug(json_encode($dados));

                    // PEGA OS DADOS DA INTEGRACAO SHOPIFY
                    try {

                        if($getLink != null){

                        if ($getLink->comunicando == 1 && $dados['transportadora'] == NULL) {

                            if (ShippingUpdate::ifExist($json->id)) {
                                $redisKey = 'shipping_update' . $json->id;

                                // Usar SETNX para garantir que não haja concorrência
                                $isLocked = Redis::setnx($redisKey, false);

                                if ($isLocked) {
                                    // Define o tempo de expiração de 5 horas (18.000 segundos)
                                    Redis::expire($redisKey, 18000);

                                    FacadesLog::debug("Processando pedido: " . $json->id);

                                    // Processar o pedido
                                    $this->storeShipping("D", $json->id, $json->buyer->id, $json->seller->id);

                                    $shipping_address = new ShippingAddress(
                                        $dados['first_name'], $dados['address1'],
                                        $dados['phone'] == "XXXXXXX" ? $getLink->telefone : $dados['phone'],
                                        $dados['city'], $dados['zip'], $dados['province'], $dados['country'],
                                        $dados['last_name'], $dados['address2'], $dados['company'], $dados['name'],
                                        $dados['country_code'], $dados['province_code'], $dados['cpf']
                                    );

                                    $nota = $json->id . " - " . $json->buyer->nickname;
                                    $order = new Order($line_item, "paid", "BRL", $shipping_address, $nota,
                                                       isset($getLink->email) ? $getLink->email : uniqid("cliente") . "@gmail.com");

                                    // Enviar o pedido
                                    $data = new SendOrder($order, $getLink->name_loja, $getLink->token);
                                    $id_shopifyOrder = $data->resource();

                                    // Coloca na fila a conversão de rascunho para pedido
                                    \App\Jobs\putDraftShopifyOrder::dispatch($getLink,
                                        $id_shopifyOrder->data->draftOrderCreate->draftOrder->id,
                                        $json->id, $json->buyer->id, $json->seller->id
                                    )->delay(Carbon::now()->addSeconds(10));

                                } else {
                                    // FacadesLog::debug("Chave já existente: " . $json->id);
                                    return; // Registro já processado recentemente, não processar novamente
                                }
                            }
                        }
                     }
                    } catch (\Exception $th) {
                        // Em caso de erro, remover a chave Redis para permitir nova tentativa no futuro
                        Redis::del($redisKey);
                        // Deletar o registro do banco de dados e registrar o erro
                        FacadesLog::emergency("VENDA CANCELADA: " .  $json->id);
                        ShippingUpdate::where('id_mercadoLivre', '=', $json->id)->delete();
                        FacadesLog::emergency($th->getMessage());
                    }
                    // FIM -*************

                    foreach ($json->payments as $payments) {
                        foreach ($json->order_items as $items) {
                        // PEGA O VALOR DO PRODUTO
                        $produto = Products::where('id',$items->item->seller_sku)->first();

                        // COLOCA O PRODUTO EM CESTA
                        if(isset($produto)){
                            if($produto->isKit){
                                $item = new MercadoPagoItem($items->item->seller_sku,$items->item->title,intVal($items->quantity),"BRL",$produto->priceKit);
                            }else{
                                $item = new MercadoPagoItem($items->item->seller_sku,$items->item->title,intVal($items->quantity),"BRL",$produto->priceWithFee);
                            }

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
                                $image = "";
                                $url = "https://api.mercadolibre.com/items/{$items->item->id}";
                                // Envia a requisição GET
                                $response = Http::get($url);
                                // Verifica se a requisição foi bem-sucedida
                                if ($response->successful()) {
                                    $image = isset($response->json()['thumbnail']) ? $response->json()['thumbnail'] : "-";
                                }

                                $prefence = new MercadoPagoPreference($carrinhoCesta,'https://afilidrop.com.br/api/v1/notification',$json->seller->id,$json->id);
                                $preference = $prefence->resource();

                                $cliente = new InterfaceClienteController($json->buyer->id, $this->getToken(),$preference['external_reference'],$preference['init_point'],$preference['id'],$json->payments[0]->marketplace_fee,$shipping);
                                $cliente->resource();
                                $id_order = $cliente->saveClient($json,$this->getSellerId());

                                $fornecedor = User::GetDataUserAndToken($produto['fornecedor_id']); // Certifique-se de que este ID é o do usuário correto
                                // FacadesLog::alert($fornecedor->name);
                                $vendedor = User::GetDataUserAndToken($user->user_id); // Certifique-se de que este ID é o do usuário correto


                                if ($fornecedor) {
                                // Envia Notificação de Venda
                                // if($id_order){
                                //         try {
                                //             if(isset($fornecedor->token)){
                                //                 pushNotificationApp("Olá Fornecedor {$fornecedor->name}","Você teve +1 Venda {$items->item->title}",$fornecedor->token,$fornecedor->id);
                                //             }

                                //         } catch (\Exception $th) {
                                //             FacadesLog::alert($th->getMessage());
                                //         }

                                //     try {
                                //         if(isset($fornecedor->token)){
                                //         pushNotificationApp("Olá {$vendedor->name}","Você vendeu {$items->item->title}",$vendedor->token,$vendedor->id);
                                //         }
                                //     } catch (\Exception $th) {
                                //         FacadesLog::alert($th->getMessage());
                                //     }

                                    $fornecedor = User::find($produto['fornecedor_id']); // Certifique-se de que este ID é o do usuário correto
                                    $vendedor = User::find($user->user_id); // Certifique-se de que este ID é o do usuário correto
                                // }

                                try {

                                    $isKit = kit::where('product_id',$produto['id'])->first();

                                    if($isKit){
                                        FacadesLog::alert("È KIT");
                                        foreach ($json->order_items as $items) {
                                           $data = [
                                            "order_site_id" => $id_order,
                                            "product_id" => $produto['id'],
                                            "integrated_product_id" => $items->item->id,
                                            "quantity_sold" => intVal($isKit->available_quantity),
                                             ];

                                            $retirarEstoque = new SalesReportController();
                                            $retirarEstoque->processSale($data);
                                        }
                                        // Dados para enviar no corpo da requisição

                                    }else{
                                        FacadesLog::alert("NÃO KIT");
                                        $data = [
                                            "order_site_id" => $id_order,
                                            "product_id" => $produto['id'],
                                            "integrated_product_id" => $items->item->id,
                                            "quantity_sold" => intVal($items->quantity),
                                        ];

                                        $retirarEstoque = new SalesReportController();
                                        $retirarEstoque->processSale($data);
                                    }

                                } catch (\Throwable $th) {
                                    FacadesLog::alert($th->getMessage());
                                }

                                    // ENVIAR VENDA BLING
                                    try {
                                        $contato = Contato::where('integracao_bling_id',$user->user_id)->first();
                                        $auth = IntegracaoBling::where('user_id',$fornecedor->id)->first();
                                        $contatoEfornecedor = BlingCreateUserByFornecedor::ifExistFornecedor($fornecedor->id,$contato->id);

                                        if($contatoEfornecedor){
                                            $data = [
                                                "data" => date('Y-m-d'),
                                                "numeroPedidoCompra" => $json->id,
                                                "contato" => [
                                                    "id" => $contatoEfornecedor->bling_id
                                                ],
                                                "itens" => [
                                                    [
                                                        "quantidade" => intVal($items->quantity),
                                                        "valor" => $produto->priceWithFee,
                                                        "produto" => [
                                                            "id" => $produto->id_bling
                                                        ]
                                                    ]
                                                ],
                                            ];

                                            FacadesLog::critical(json_encode($data));
                                            // PEGA O SERVIÇO BLING
                                            $BlingApiService = new BlingApiService($auth->access_token);
                                            $BlingApiService->sendSale($data);

                                        }else{

                                            $blingData = [
                                                'nome' => $contato['nome'],
                                                'tipo' => $contato['tipo'],
                                                'numeroDocumento' => $contato['numeroDocumento'],
                                                'situacao' => $contato['situacao'],
                                                'celular' => $contato['celular'],
                                                'email' => $contato['email'],
                                                'rg' => $contato['rg'] ?? null,
                                                'endereco' => [
                                                    'geral' => [
                                                        'endereco' => $contato['endereco'],
                                                        'cep' => $contato['cep'],
                                                        'bairro' => $contato['bairro'],
                                                        'municipio' => $contato['municipio'],
                                                        'uf' => $contato['uf'],
                                                        'numero' => $contato['numero'],
                                                        'complemento' => $contato['complemento'] ?? null,
                                                    ],
                                                ],
                                            ];

                                            $BlingContatos = new BlingContatos($auth->access_token,$contato->id,$fornecedor->id);
                                            $id = $BlingContatos->enviarContato($blingData);

                                            $data = [
                                                "data" => date('Y-m-d'),
                                                "numeroPedidoCompra" => $json->id,
                                                "contato" => [
                                                    "id" => $id
                                                ],
                                                "itens" => [
                                                    [
                                                        "quantidade" => intVal($items->quantity),
                                                        "valor" => $produto->priceWithFee,
                                                        "produto" => [
                                                            "id" => $produto->id_bling
                                                        ]
                                                    ]
                                                ],
                                            ];

                                            // PEGA O SERVIÇO BLING
                                            $BlingApiService = new BlingApiService($auth->access_token);
                                            $BlingApiService->sendSale($data);
                                        }

                                    } catch (\Throwable $th) {
                                        FacadesLog::alert($th->getMessage());
                                    }

                                    financeiro::SavePayment(3, $payments->total_paid_amount, $id_order, $produto->fornecedor_id, $preference['init_point'], "S/N","aguardando pagamento",$preference['external_reference'],$shipping);
                                    // NOTIFICA O FORNECEDOR
                                    Notification::send($fornecedor, new notificaUserOrder($fornecedor, $json, $produto, $id_order, $json->id));
                                    // // NOTIFICA O VENDEDOR
                                    Notification::send($vendedor, new notificaSellerOrder($vendedor, $json, $produto, $id_order, $json->id,$preference['init_point'],$image));

                                }

                                    // financeiro::SavePayment(3, $payments->total_paid_amount, $id_order, $token->user_id, $preference['init_point'], "S/N","aguardando pagamento",$preference['external_reference'],$shipping);
                            }else{

                                    FacadesLog::debug("Gravando no Banco pedido: " . $json->id);
                                    try {
                                        $cliente = new InterfaceClienteController($json->buyer->id, $this->getToken(),"N/D","N/D","1",$json->payments[0]->marketplace_fee,$json->shipping->id);
                                        $cliente->resource();
                                        $id_order = $cliente->saveClient($json,$this->getSellerId());
                                    } catch (\Exception $th) {
                                        $th->getMessage();
                                    }

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

    public function pushNotificationApp($msgHeader,$msgBody,$token){

        $factory = (new Factory)
        ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

        $messaging = $factory->createMessaging();
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(FirebaseNotification::create($msgHeader,$msgBody))
            ->withData([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ]);
        $messaging->send($message);
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
