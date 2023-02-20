<?php

namespace App\Http\Controllers\Yapay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProdutoCestaController extends Controller
{
    public $produtos;
    public $n_pedido;

    public function __construct(String $n_pedido, array $produtos)
    {
        $this->produtos = $produtos;
        $this->n_pedido = $n_pedido;
    }


    public function getQuantidade(){
        return count($this->produtos);
    }
}
