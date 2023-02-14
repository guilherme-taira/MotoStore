<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;
use PhpParser\Parser\Tokens;

class ProdutoImplementacao extends criadorDeProduto
{
    public function getProduto()
    {
        $products = Products::where('id', $this->getIdProduct())->first();

        $token = token::where('user_id', $this->getId())->first(); // CHAMANDO ANTIGO
        $dataAtual = new DateTime();
        $newToken = new RefreshTokenController($token->refresh_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $this->getId());
        $newToken->resource();
        $token = token::where('user_id', $this->getId())->first(); // CHAMANDO ATUALIZADO
        $produto = new ProdutoConcreto($products, $this->getIdCategoria(), $this->getPrice(), $token,$this->getName(),$this->getTipoAnuncio());
        $data = $produto->integrar();
        if ($data) {
            return ['error_data' => $data];
        } else {
            session('msg_success', 'Produto Cadastrado Com Sucesso!');
            return "";
        }
    }

    public function getErrosFunction(array $data)
    {
        $this->setErros($data);
    }
}
