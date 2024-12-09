<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Events\EventoAfiliado;
use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpParser\Parser\Tokens;

class ProdutoImplementacao extends criadorDeProduto
{
    public function getProduto()
    {
        $products = Products::where('id', $this->getIdProduct())->first();
        $token = token::where('user_id', $this->getId())->first(); // CHAMANDO ANTIGO

        $dataAtual = new DateTime();
        // $newToken = new RefreshTokenController($token->refresh_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $token->user_id_mercadolivre);
        // $newToken->resource();
        $token = token::where('user_id', $this->getId())->first(); // CHAMANDO ATUALIZADO
        $produto = new ProdutoConcreto($products, $this->getIdCategoria(), $this->getPrice(), $token,$this->getName(),$this->getTipoAnuncio(),$this->getDados(),$this->getValorSemTaxa(),$this->getTotalInformado());
        $data = $produto->integrar($this->getDescricao(),$this->getIdProduct());
        if ($data) {
            return ['error_data' => $data];
        } else {
            session('msg_success', 'Produto Cadastrado Com Sucesso!');
            EventoAfiliado::dispatch($products);
            return "";
        }
    }

    public function getErrosFunction(array $data)
    {
        $this->setErros($data);
    }
}
