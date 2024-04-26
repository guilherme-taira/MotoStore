<?php

namespace App\Http\Controllers\MercadoPago\Pagamento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Models\financeiro;
use App\Models\order_site;
use App\Models\token;
use App\Models\User;
use App\Notifications\notificaUserOrder;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use MercadoPago\Payment as MercadoPayment;

class getPaymentController extends Controller
{
    const URL_BASE_MERCADOPAGO = "https://api.mercadopago.com";

    private string $order_id;
    private string $seller_id;

    public function __construct($order_id,$seller_id)
    {
        $this->order_id = $order_id;
        $this->seller_id = $seller_id;
    }

    public function get($resource)
    {

        $dataAtual = new DateTime();
        $userML = token::where('user_id_mercadolivre',$this->getSellerId())->first();


        $newToken = new RefreshTokenController($userML->refresh_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $userML->user_id);
        $newToken->resource();
        // URL PARA REQUISICAO
        $endpoint = self::URL_BASE_MERCADOPAGO . $resource;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer $userML->access_token"]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = json_decode($response);
        curl_close($ch);

        if($httpCode == '400'){
            order_site::where('external_reference',$this->getOrderId())->update(['status_id' => 5]);
        }else if($httpCode == '200'){
            if($res->status == "approved"){
               // INSERE A NOTIFICAÇÃO
               $user = User::find($userML->user_id);

               $user->notify(new notificaUserOrder($user,$this->getOrderId()));

                order_site::where('external_reference',$res->external_reference)->update(['status_id' => 4]);
                $dados = financeiro::where('token_transaction',$res->external_reference)->get();
                Log::critical(json_encode($dados));
                financeiro::where('token_transaction',$res->external_reference)->update(['status' => 4,'valor' => $res->transaction_details->net_received_amount]);
            }
        }
    }

    public function resource()
    {
        return $this->get("/v1/payments/" . $this->getOrderId());
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

    /**
     * Get the value of seller_id
     */
    public function getSellerId(): string
    {
        return $this->seller_id;
    }
}
