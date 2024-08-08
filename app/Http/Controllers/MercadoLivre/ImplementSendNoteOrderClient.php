<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FileController;
use App\Models\ShippingUpdate;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImplementSendNoteOrderClient extends SendNoteOrderClient
{

    public function send(){

    // $dataAtual = new DateTime();
    // $token = token::where('user_id_mercadolivre', $this->getUserId())->first(); // CHAMANDO ANTIGO
    // $newToken = new RefreshTokenController($token->refresh_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $token->user_id);
    // $newToken->resource();

    // $token = token::where('user_id_mercadolivre', $this->getUserId())->first();

    // ENDPOINT PARA REQUISICAO |
    try {

        $endpoint = "https://api.mercadolibre.com/orders/".$this->getNumeroPedido()."/notes";
        $array = [
            "endpoint" => $endpoint,
            "data" => json_decode($this->toJson(),true),
        ];

        Log::critical(json_encode($array));
        // GRAVA NO ARQUIVO TXT
        // $salvar = new FileController;
        // $salvar->createFile(json_encode($array));

        // CONVERTE O ARRAY PARA JSON
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $endpoint);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $this->toJson());
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
    //     $response = curl_exec($ch);
    //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     curl_close($ch);
    //     $data = json_decode($response);

    // if ($httpCode == '200') {

    //         $data = [
    //           'observacaomeli' => "X",
    //           'msg' => $data->note->note,
    //       ];

    //       // Condições para encontrar o registro
    //       $conditions = [
    //           'id' => $this->getNumeroPedido(),
    //       ];
    //       // Crie ou atualize o registro
    //       ShippingUpdate::updateOrCreate($conditions, $data);

    //     Log::emergency($response);
    // }else{
    //     Log::error($response);
    // }

} catch (\Exception $e) {
    // return response()->json($e->getMessage());
}
    }


    public function toJson(){
        return json_encode(["note" => $this->getNote()]);
    }


}
