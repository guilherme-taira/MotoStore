<?php

namespace App\Http\Controllers\GenerateCode;

use App\Http\Controllers\Controller;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetCodeController extends RequestCodeMercadoLivre
{

    const URL_BASE_ML = "https://api.mercadolibre.com";

    public function resource()
    {
        return $this->get('/oauth/token');
    }

    public function get($resource)
    {
        // ENDPOINT PARA REQUISICAO
        // ENDPOINT PARA AUTENTICAR
        $endpoint = self::URL_BASE_ML . $resource;

        $dataToPost = array(
            "grant_type" => $this->getGrantType(),
            "client_id" => $this->getClientId(),
            "client_secret" => $this->getClientSecret(),
            "code" => $this->getCode(),
            "redirect_uri" => $this->getRedirectUri(),
        );

        $data_json = json_encode($dataToPost);

        Log::alert($data_json);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'content-type:application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        Log::alert($response);
        curl_close($ch);
        $dados = json_decode($response);
        // if (!isset($dados->status)) {
            $this->saveCode($dados);
        //}
        return $dados;
    }

    public function saveCode($codeReturn)
    {

        $verifyExist = token::where('user_id', $this->getUserId())->first();
        if (!$verifyExist) {
            try {
                $date = new DateTime();
                $date->modify('+6 hours');
                $DataSistema = $date->format('Y-m-d H:i:s');

                $token = new token();
                $token->access_token = $codeReturn->access_token;
                $token->type = "MercadoLivre";
                $token->refresh_token = $codeReturn->refresh_token;
                $token->user_id_mercadolivre = $codeReturn->user_id;
                $token->user_id = $this->getUserId();
                $token->datamodify = $DataSistema;
                $token->save();
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }
}
