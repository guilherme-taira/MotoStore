<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class getShippingData extends Controller
{

    // URL BASE MERCADO LIVRE
    const URL_BASE_MERCADOLIVRE = "https://api.mercadolibre.com/";

    private $shippingId;
    private $token;

    public function __construct($shippingId,$token)
    {
        $this->shippingId = $shippingId;
        $this->token = $token;
    }


    public function get($resource){
        // URL PARA REQUISICAO
        $endpoint = self::URL_BASE_MERCADOLIVRE . $resource;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$this->getToken()}"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = json_decode($response,true);
        curl_close($ch);
        Log::emergency($response);
        return $this->trataDados($res);
    }

    public function resource(){
       return $this->get("shipments/{$this->getShippingId()}");
    }

    public function trataDados($data){
    // Decode JSON to PHP array

    // Extract required fields
    $receiver_name = $data['receiver_address']['receiver_name'];
    $first_name = explode(' ', $receiver_name)[0];
    $street_name = $data['receiver_address']['street_name']. " ". $data['receiver_address']['street_number'];
    $receiver_phone = $data['receiver_address']['receiver_phone'];
    $city = $data['receiver_address']['city']['name'];
    $zip_code = $data['receiver_address']['zip_code'];
    $state = $data['receiver_address']['state']['name'];
    $country = $data['receiver_address']['country']['name'];
    $comment = $data['receiver_address']['comment'];
    $country_id = $data['receiver_address']['country']['id'];
    $state_id_full  = $data['receiver_address']['state']['id'];
    $transportadora = $data['tracking_method'];

    // Extract surname from receiver_name
    $surname = explode(' ', $receiver_name);
    $surname = end($surname);
    // Extract state_id without the "BR-" prefix
    $state_id = str_replace("BR-", "", $state_id_full);
    // Format zip code
    $zip_code = substr($zip_code, 0, 5) . '-' . substr($zip_code, 5, 3);
    // Create array with required fields
    $result = [
        'first_name' => $first_name,
        'address1' => $street_name,
        'phone' => $receiver_phone,
        'city' => $city,
        'zip' => $zip_code,
        'province' => $state,
        'country' => "Brazil",
        'last_name' => $surname,
        'address2' => $comment,
        'company' => "",
        'name' => $receiver_name,
        'country_code' => $country_id,
        'province_code' => $state_id,
        'transportadora' => $transportadora
    ];

    Log::emergency(json_encode($result));
    // Return the array
    return $result;
    }

    /**
     * Get the value of shippingId
     */
    public function getShippingId()
    {
        return $this->shippingId;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {
        return $this->token;
    }

}
