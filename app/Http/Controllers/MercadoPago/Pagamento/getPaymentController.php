<?php

namespace App\Http\Controllers\MercadoPago\Pagamento;

use App\Http\Controllers\Controller;
use App\Models\order_site;
use App\Models\token;
use Illuminate\Http\Request;
use MercadoPago\Payment as MercadoPayment;

class getPaymentController extends Controller
{
    const URL_BASE_MERCADOPAGO = "https://api.mercadopago.com";

    private string $order_id;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    public function get($resource)
    {
        // URL PARA REQUISICAO
        $endpoint = self::URL_BASE_MERCADOPAGO . $resource;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$this->getToken()}"]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = json_decode($response);
        curl_close($ch);

        if($httpCode == '400'){
            order_site::where('external_reference',$this->getOrderId())->update(['status_id' => 5]);
        }else if($httpCode == '200'){
            if($res->results[0]->status == "approved"){
                order_site::where('external_reference',$this->getOrderId())->update(['status_id' => 4]);
            }
        }
    }

    public function resource()
    {
        return $this->get("/v1/payments/search?sort=date_created&criteria=desc&external_reference=" . $this->getOrderId());
    }

    public function getToken(){
        $data = token::where('id',1)->first();
        return $data->access_token;
    }
    /**
     * Get the value of order_id
     */
    public function getOrderId(): string
    {
        return $this->order_id;
    }

    /**
     * Set the value of order_id
     */
    public function setOrderId(string $order_id): self
    {
        $this->order_id = $order_id;

        return $this;
    }
}
