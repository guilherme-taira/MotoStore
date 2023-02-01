<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\token;
use Illuminate\Http\Request;
use PhpParser\Parser\Tokens;

class ProdutoImplementacao extends criadorDeProduto
{
    public function getProduto()
    {
        $products = Products::where('id', $this->getIdProduct())->first();
        $token = token::where('user_id',$this->getId())->first();
        $newToken = new RefreshTokenController($token->refresh_token,$token->datamodify,$token->user_id_mercadolivre,"y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV",$this->getId());
        $produto = new ProdutoConcreto($products,$this->getIdCategoria(),$this->getPrice());
        $data = $produto->integrar();
        if($data){
            return ['error_data' => $data];
        }else{
            return ['msg_success' => "Produto Integrado com Sucesso!"];
        }
    }

    public function getErrosFunction(array $data){
         $this->setErros($data);
    }

}
