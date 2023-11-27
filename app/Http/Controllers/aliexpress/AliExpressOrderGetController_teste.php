<?php

namespace App\Http\Controllers\aliexpress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AliExpressOrderGetController extends Controller
{

    private String $url;
    private String $access_token;
    private String $app_key;
    private String $app_secret;


    const URL_BASE_ALIEXPRESS = "https://api-sg.aliexpress.com/rest";

    public function __construct($url, $access_token, $app_key, $app_secret)
    {
        $this->url = $url;
        $this->access_token = $access_token;
        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
    }

    public function resource()
    {
        $dataForGenerateSign = [
            "app_key" => $this->getAppKey(),
            "refresh_token" => $this->getAccessToken(),
            "timestamp" => $this->msectime(),
            "sign_method" => "sha256"
        ];

        $this->get("/auth/token/refresh"."?app_key=".$this->getAppKey()."&refresh_token=".$this->getAccessToken()."&timestamp=".$this->msectime()."&sign_method=sha256&sign=".$this->generateSign($this->getUrl(), $dataForGenerateSign));
    }

    public function get($resource)
    {

        $endpoint = self::URL_BASE_ALIEXPRESS . $resource;
        echo $endpoint. "<br>";

        // $dataForGenerateSign = [
        //     "app_key" => $this->getAppKey(),
        //     "refresh_token" => $this->getAccessToken(),
        //     "timestamp" => $this->msectime(),
        //     "sign_method" => "sha256"
        // ];
        // $data = [
        //     "refresh_token" => $this->getAccessToken(),
        //     "app_key" => $this->getAppKey(),
        //     "timestamp" => $this->msectime(),
        //     "sign_method" => "sha256",
        //     "sign" => $this->generateSign($this->getUrl(), $dataForGenerateSign)
        // ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo "<hr>";
        print_r(json_decode($response));
    }

    function msectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        return $sec . '000';
    }

    protected function generateSign($apiName, $params)
    {
        ksort($params);

        $stringToBeSigned = '';
        if (strpos($apiName, '/')) { //rest服务协议
            $stringToBeSigned .= $apiName;
        }
        foreach ($params as $k => $v) {
            $stringToBeSigned .= "$k$v";
        }

        return strtoupper($this->hmac_sha256($apiName.$stringToBeSigned, $this->getAppSecret()));
    }

    function hmac_sha256($data, $key)
    {
        return hash_hmac('sha256', $data, $key);
    }


    /**
     * Get the value of url
     */
    public function getUrl(): String
    {
        return $this->url;
    }

    /**
     * Get the value of access_token
     */
    public function getAccessToken(): String
    {
        return $this->access_token;
    }

    /**
     * Get the value of app_key
     */
    public function getAppKey(): String
    {
        return $this->app_key;
    }

    /**
     * Get the value of app_secret
     */
    public function getAppSecret(): String
    {
        return $this->app_secret;
    }
}
