<?php

namespace App\Http\Controllers\MercadoLivre\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class getDataShippingController implements ClienteController
{
    const URL_BASE = "https://api.mercadolibre.com";

    private String $ShippingId;
    private String $token;

    public function __construct($ShippingId, $token)
    {
        $this->ShippingId = $ShippingId;
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
        $data = [];
        $data['address_line'] = $json->receiver_address->address_line;
        $data['city'] = $json->receiver_address->city->name;
        $data['street_number'] = $json->receiver_address->street_number;
        $data['state'] = $json->receiver_address->state->name;
        $data['zip_code'] = $json->receiver_address->zip_code;
        $data['neighborhood'] = $json->receiver_address->neighborhood->name;
        return json_decode(json_encode($data));
    }

    public function resource()
    {
        return $this->get("/shipments/{$this->getShippingId()}");
    }

    /**
     * Get the value of ShippingId
     */
    public function getShippingId(): String
    {
        return $this->ShippingId;
    }

    /**
     * Set the value of ShippingId
     */
    public function setShippingId(String $ShippingId): self
    {
        $this->ShippingId = $ShippingId;

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
