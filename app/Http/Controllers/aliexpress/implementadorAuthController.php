<?php

namespace App\Http\Controllers\aliexpress;

use App\Http\Controllers\Controller;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;

class implementadorAuthController extends authController
{
    const URL_BASE = "https://api-sg.aliexpress.com/rest";

    public function resource()
    {
        $dataTocreateSing = [
            "app_key" => $this->getAppKey(),
            "code" => $this->getCode(),
            "sign_method" => "sha256",
            "timestamp" => $this->msectime()
        ];

        $this->get('/auth/token/create?app_key='.$this->getAppKey()."&code=".$this->getCode()."&sign_method=sha256&timestamp=".$this->msectime()."&sign=".$this->generateSign("/auth/token/create",$dataTocreateSing));
    }

    public function get($resource)
    {

        // ENDPOINT PARA REQUISICAO
        $endpoint = self::URL_BASE.$resource;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataTocreateSing);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo "<pre>";
        $this->saveAccessToken(json_decode($response));

    }

    function msectime() {
        list($msec, $sec) = explode(' ', microtime());
        return $sec . '000';
     }

    public function saveAccessToken($data)
    {
        $verifyExist = token::where('user_id', $data->havana_id)->first();
        if (!$verifyExist) {
            try {
                $token = new token();
                $token->access_token = $data->access_token;
                $token->type = "Aliexpress";
                $token->refresh_token = $data->refresh_token;
                $token->user_id_mercadolivre = $data->user_id;
                $token->user_id = $data->havana_id;
                $token->datamodify = $data->refresh_expires_in;
                $token->save();
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    protected function generateSign($apiName,$params)
    {
        ksort($params);

        $stringToBeSigned = '';
        if(strpos($apiName, '/')){//rest服务协议
            $stringToBeSigned .= $apiName;
        }
        foreach ($params as $k => $v)
        {
            $stringToBeSigned .= "$k$v";
        }

        unset($k, $v);
        return strtoupper($this->hmac_sha256($apiName.$stringToBeSigned,$this->getAppSecret()));
    }

    function hmac_sha256($data, $key){
	    return hash_hmac('sha256', $data, $key);
	}

}
