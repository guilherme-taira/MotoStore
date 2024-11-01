<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class AbstractPriceProdutoController
{
    private $produto;
    private $oldPrice;
    private $newPrice;
    private $commonPrice;

    public function __construct($oldPrice,$newPrice,$produto,$commonPrice)
    {
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
        $this->produto = $produto;
        $this->commonPrice = $commonPrice;
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

    /**
     * Get the value of commonPrice
     */
    public function getCommonPrice()
    {
        return $this->commonPrice;
    }
}
