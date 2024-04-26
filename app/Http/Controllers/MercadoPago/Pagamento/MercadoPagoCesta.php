<?php

namespace App\Http\Controllers\Mercadopago\Pagamento;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoCesta extends Controller
{
    private $produtos = [];

    public function addProdutos(MercadoPagoItem $item){
        $this->produtos[] = $item;
    }

    public function getAllProdutos(): array{
        return $this->produtos;
    }

    public function calculeTaxaMarketplace(): float{
        $valor = 0;
        foreach ($this->getAllProdutos() as $produto) {
            $txProd = Products::where('id',$produto->getId())->first();
            $valor += $produto->getQuantity() * $txProd->fee;
        }
        return $valor;
    }

    public function calculeValorFornecedor(): float{
        $valor = 0;
        foreach ($this->getAllProdutos() as $produto) {
            $txProd = Products::where('id',$produto->getId())->first();
            $valor += $produto->getQuantity() * $txProd->valorProdFornecedor;
        }
        return $valor;
    }

    public function getToken(){
        $token = token::where('user_id',2)->first();
        return $token->access_token;
    }

    public function getFornecedorId(){
        foreach ($this->getAllProdutos() as $produto) {
            $txProd = Products::where('id',$produto->getId())->first();
        }
        return $txProd->fornecedor_id;
    }
}
