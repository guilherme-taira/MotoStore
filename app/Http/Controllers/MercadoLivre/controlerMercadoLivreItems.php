<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class controlerMercadoLivreItems extends Controller
{
    private String $resource;
    private String $token;

    const URL_BASE_ML = "https://api.mercadolibre.com/";


    public function __construct($resource, $token)
    {
        $this->resource = $resource;
        $this->token = $token;
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

        try {
            if ($httpCode == 200) {
                return $json;
         }

        } catch (\Exception $th) {
            // FacadesLog::critical($th->getMessage());
        }

    }

    public function resource()
    {
        return $this->get($this->getResource());
    }

    /**
     * Get the value of resource
     */
    public function getResource(): String
    {
        return $this->resource;
    }

    /**
     * Get the value of token
     */
    public function getToken(): String
    {
        return $this->token;
    }
}
