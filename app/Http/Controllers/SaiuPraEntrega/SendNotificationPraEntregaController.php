<?php

namespace App\Http\Controllers\SaiuPraEntrega;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Models\ShippingUpdateMessage;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SendNotificationPraEntregaController extends SaiuPraEntregaController
{

    const URL_BASE_ML = "https://api.mercadolibre.com/";

    public function save(){
        if($this->saveMessage() == TRUE){
            // $this->notifyClient();
        }
    }

    function saveMessage(){

       $data = ShippingUpdateMessage::create([
            'shipping_update_id' => $this->getShippingID(),
            'mensagem' => $this->getMessage(),
        ]);

        return $data;
    }



    function notifyClient(){

                // GET TOKEN
                $userML = token::where('user_id_mercadolivre', $this->getSeller())->first();
                $dataAtual = new DateTime();
                // GET NEW TOKEN
                $newToken = new RefreshTokenController($userML->refresh_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $this->getSeller());
                $newToken->resource();

                // GET TOKEN
                $userML = token::where('user_id_mercadolivre', $this->getSeller())->first();
                // ENDPOINT PARA REQUISICAO
                $endpoint = self::URL_BASE_ML ."messages/packs/".$this->getIdMercadoLivre()."/sellers/".$this->getSeller()."?tag=post_sale";

                $data = [
                    "from" => [
                          "user_id" => $this->getSeller()
                       ],
                    "to" => [
                             "user_id" => $this->getClient()
                          ],
                    "text" => $this->getMessage()
                 ];

                 Log::alert($endpoint);
                /**
                 * CURL REQUISICAO -X GET
                 * **/
                // $ch = curl_init();
                // curl_setopt($ch, CURLOPT_URL, $endpoint);
                // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$userML->access_token}", "x-format-new: true"]);
                // $reponse = curl_exec($ch);
                // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                // curl_close($ch);
                // $json = json_decode($reponse);
                // Log::emergency($reponse);

    }
}
