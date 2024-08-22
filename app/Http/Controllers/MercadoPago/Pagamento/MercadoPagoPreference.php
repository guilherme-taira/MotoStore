<?php

namespace App\Http\Controllers\MercadoPago\Pagamento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Models\token;
use DateTime;
use Illuminate\Support\Facades\Log;

class MercadoPagoPreference extends Controller

{

    private $externalPreference;
    private $itens;
    private $notificationUrl;
    private $userid;


    public function __construct(MercadoPagoCesta $itens,$notificationUrl,$userid)
    {

        $this->externalPreference = uniqid('afilidrop');
        $this->itens = $itens;
        $this->notificationUrl = $notificationUrl;
        $this->userid = $userid;
    }

      /**
     * Get the value of externalPreference
     */
    public function getExternalPreference()
    {
        return $this->externalPreference;
    }

    /**
     * Set the value of externalPreference
     */
    public function setExternalPreference($externalPreference): self
    {
        $this->externalPreference = $externalPreference;

        return $this;
    }

    /**
     * Get the value of itens
     */
    public function getItens()
    {
        return $this->itens;
    }

    /**
     * Set the value of itens
     */
    public function setItens($itens): self
    {
        $this->itens = $itens;

        return $this;
    }


    /**
     * Get the value of notificationUrl
     */
    public function getNotificationUrl()
    {
        return $this->notificationUrl;
    }

    /**
     * Set the value of notificationUrl
     */
    public function setNotificationUrl($notificationUrl): self
    {
        $this->notificationUrl = $notificationUrl;

        return $this;
    }

    /**
     * Get the value of userid
     */
    public function getUserid()
    {
        return $this->userid;
    }

    const URL_BASE  = "https://api.mercadopago.com/";

    public function toJson(){
        $data = [];
        $data['external_reference'] = $this->getExternalPreference();
        $data['items'] = [ $this->getItens()->getAllProdutos()[0] ];
        $data['marketplace'] = $this->getItens()->getToken(); // ARRUMAR ISSO DENTRO DA CESTA
        $data['marketplace_fee'] = $this->getItens()->calculeTaxaMarketplace();
        $data['notification_url'] = $this->getNotificationUrl();
        return json_encode($data);
    }

    public  function resource(){
        return $this->get('checkout/preferences');

    }

    public function get($resource){

        $token = token::where('user_id',$this->getItens()->getFornecedorId())->first();

        if($token){
            $dataAtual = new DateTime();
            // GET NEW TOKEN
            $newToken = new RefreshTokenController($token->refresh_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $token->user_id_mercadolivre);
            $newToken->resource();
            $token = token::where('user_id',$this->getItens()->getFornecedorId())->first();
        }
        // ENDPOINT PARA REQUISICAO
        try {
            $endpoint = self::URL_BASE.$resource;
            // CONVERTE O ARRAY PARA JSON
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->toJson());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            Log::critical($reponse);
        if ($httpCode == '201') {
            return json_decode($reponse,true);
        }else{
            Log::error($reponse);
        }

    } catch (\Exception $e) {
        // return response()->json($e->getMessage());
    }
    }
}
