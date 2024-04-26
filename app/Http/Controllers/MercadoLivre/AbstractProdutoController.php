<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class AbstractProdutoController extends Controller
{
    private $produto;

    public function __construct($produto)
    {
        $this->produto = $produto;
    }

    abstract function toJson();
    abstract function toArray();

    /**
     * Get the value of produto
     */
    public function getProduto()
    {
        return $this->produto;
    }
}
