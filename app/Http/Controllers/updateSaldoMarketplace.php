<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\AbstractProdutoController;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Models\Products;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class updateSaldoMarketplace extends AbstractProdutoController
{

    public function send(){

        $produtos = Products::getAllUserProduct($this->getProduto()->id);

        foreach ($produtos as $key => $value) {

            $dataAtual = new DateTime();

            $newToken = new RefreshTokenController($value->refresh_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $value->user_id);
            $newToken->resource();

            $token = token::where('user_id_mercadolivre', $value->user_id_mercadolivre)->first();
            // ENDPOINT PARA REQUISICAO
            try {
                $endpoint = 'https://api.mercadolibre.com/items/' . $value->id_mercadolivre;
                // CONVERTE O ARRAY PARA JSON
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $endpoint);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->toJson());
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
                $reponse = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

            if ($httpCode == '200') {
                Log::emergency($reponse);
            }else{
                Log::error($reponse);
            }

        } catch (\Exception $e) {
            // return response()->json($e->getMessage());
        }

        }
    }


    public function toJson(){
        $data = [];
        $data['available_quantity'] = $this->getProduto()->available_quantity;
        return json_encode($data);
    }

    public function toArray(){

    }
}
