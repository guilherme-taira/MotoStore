<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class ProdutoImplementacao extends criadorDeProduto
{
    public function getProduto(): ProdutoConcreto
    {
        $products = Products::where('id', 43)->first();
        $produto = new ProdutoConcreto($products);
        $produto->integrar();
        return $produto;
    }
}
