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
        // $endpoint = $this->getLoja() . $resource;
        //************** */ USANDO API REST **********
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $endpoint);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $this->toJson());
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "X-Shopify-Access-Token: {$this->getToken()}"]);
        // $response = curl_exec($ch);
        // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // $res = json_decode($response);
        // curl_close($ch);

        // Definir a mutation para criar um draft order
        $query = json_encode([
            "query" => "mutation draftOrderCreate(\$input: DraftOrderInput!) { draftOrderCreate(input: \$input) { draftOrder { id } } }",
            "variables" => [
                "input" => [
                    "note" => $this->getOrder()->note,
                    "email" => $this->getOrder()->email,
                    "shippingAddress" => [
                        "address1" => $this->getOrder()->shipping_address->address1,
                        "city" => $this->getOrder()->shipping_address->city,
                        "province" => $this->getOrder()->shipping_address->province,
                        "country" => $this->getOrder()->shipping_address->country,
                        "zip" => $this->getOrder()->shipping_address->zip,
                        "firstName" => $this->getOrder()->shipping_address->first_name,
                        "lastName" => $this->getOrder()->shipping_address->last_name,
                        "phone" => $this->getOrder()->shipping_address->phone,
                        "address2" => $this->getOrder()->shipping_address->address2
                    ],
                    "lineItems" => [
                        [
                            "title" => "PRODUTO NÃO INTEGRADO",
                            "originalUnitPrice" => 14.99,
                            "quantity" => 1
                        ]
                    ],
                    "localizationExtensions" => [
                        [
                            "key" => "TAX_CREDENTIAL_BR",
                            "value" => "77195411036"
                        ]
                    ]
                ]
            ]
        ], JSON_UNESCAPED_UNICODE);
        //*** USANDO GRAPHIC QL******/

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$this->getLoja()}graphql.json");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Shopify-Access-Token: ' . $this->getToken()
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = json_decode($response);
        // Log::critical( $query);
        Log::alert($response);
        return $res;
    }

    public function resource()
    {
        return $this->get("draft_orders.json");
    }
    public function toJson(){
        return json_encode(['draft_order' => $this->getOrder()]);
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
