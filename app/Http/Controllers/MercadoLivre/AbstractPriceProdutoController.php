<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class AbstractPriceProdutoController
{
    private $produto;
    private $oldPrice;
    private $newPrice;

    public function __construct($oldPrice,$newPrice,$produto)
    {
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
        $this->produto = $produto;
    }

    // abstract function toJson();
    abstract function vericaPreco();
    /**
     * Get the value of produto
     */
    public function getProduto()
    {
        return $this->produto;
    }

    /**
     * Get the value of oldPrice
     */
    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    /**
     * Get the value of newPrice
     */
    public function getNewPrice()
    {
        return $this->newPrice;
    }
}
