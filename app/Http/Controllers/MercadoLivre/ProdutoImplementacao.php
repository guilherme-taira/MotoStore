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
        // $token = token::where('user_id', $this->getId())->first(); // CHAMANDO ANTIGO

        // $dataAtual = new DateTime();
        $token = token::where('user_id', $this->getId())->first(); // CHAMANDO ATUALIZADO
        $produto = new ProdutoConcreto($products, $this->getIdCategoria(), $this->getPrice(), $token,$this->getName(),$this->getTipoAnuncio(),$this->getDados(),$this->getValorSemTaxa(),$this->getTotalInformado(),$this->getDadosIntegrado(),$products->atributos_json);
        $data = $produto->integrar($this->getDescricao(),$this->getIdProduct());
        Log::alert($this->getId());
        if ($data) {
            return ['error_data' => $data];
        } else {
            session('msg_success', 'Produto Cadastrado Com Sucesso!');
            EventoAfiliado::dispatch($products);
            return "";
        }
    }

    public function getProdutoByApi()
    {

        $products = Products::where('id', $this->getIdProduct())->first();
        $token = token::where('user_id', $this->getId())->first(); // CHAMANDO ANTIGO
        $produto = new ProdutoConcreto($products, $this->getIdCategoria(), $this->getPrice(), $token,$this->getName(),$this->getTipoAnuncio(),$this->getDados(),$this->getValorSemTaxa(),$this->getTotalInformado(),$this->getDadosIntegrado());
        $data = $produto->integrarViaApi($this->getDescricao(),$this->getIdProduct());

        if (!isset($data['id'])) {
            return ['message' => $data, 'statusCode' => 400];
        } else {
            EventoAfiliado::dispatch($products);
            return ['message' => $data, 'statusCode' => 200];
        }
    }

    public function getErrosFunction(array $data)
    {
        $this->setErros($data);
    }
}
