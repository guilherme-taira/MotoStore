<?php

namespace App\Http\Controllers\MercadoPago\Pagamento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Models\financeiro;
use App\Models\order_site;
use App\Models\Products;
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

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
        // $this->seller_id = $seller_id;
    }

    public function get($resource)
    {

        $dataAtual = new DateTime();
        $userML = token::where('user_id_mercadolivre','1272736385')->first();

        $newToken = new RefreshTokenController($userML->refresh_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $userML->user_id_mercadolivre);
        $newToken->resource();
        $userML = token::where('user_id_mercadolivre','1272736385')->first();
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
        Log::critical($response);
       try {
        if($httpCode == '400'){
            order_site::where('numeropedido',$res->external_reference)->update(['status_id' => 5]);
        }else if($httpCode == '200'){
            if($res->status == "approved"){
                Log::critical($res->external_reference);
                $userML = token::where('user_id_mercadolivre',$res->payer->id)->first();
                // INSERE A NOTIFICAÇÃO
                $user = User::find($userML->user_id);
                $orderId = order_site::where('external_reference',$res->external_reference)->first();

                foreach ($res->additional_info->items as $item) {
                    $produto = Products::find($item->id);
                    $user->notify(new notificaUserOrder($user,$this->getOrderId(),$produto,$orderId->id,$orderId->numeropedido));
                }

                order_site::where('external_reference',$res->external_reference)->update(['status_id' => 4]);
                // financeiro::where('token_transaction',$res->external_reference)->get();
                financeiro::where('token_transaction',$res->external_reference)->update(['status' => 4,'valor' => $res->transaction_details->net_received_amount,'detalhes_transacao' => $response]);
            }else if($res->status == "cancelled" || $res->status == "refunded"){
                order_site::where('external_reference',$res->external_reference)->update(['status_id' => 5]);
                financeiro::where('token_transaction',$res->external_reference)->update(['status' => 5,'valor' => $res->transaction_details->net_received_amount,'detalhes_transacao' => $response]);
            }
        }
       } catch (\Throwable $th) {
         Log::critical($th->getMessage());
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
