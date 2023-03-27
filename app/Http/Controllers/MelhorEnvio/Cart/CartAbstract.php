<?php

namespace App\Http\Controllers\MelhorEnvio\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class CartAbstract
{
    private String $service;
    private String $agencia;
    private array $products;
    private array $volumes;

    public function __construct(String $service,String $agencia,array $products,array $volumes)
    {
        $this->service = $service;
        $this->agencia = $agencia;
        $this->products = $products;
        $this->volumes = $volumes;
    }

    abstract function criarVolumes();
    abstract function criarprodutos();
    abstract function Remetente();
    abstract function Destinatario();
    abstract function getDados();
    abstract function options();
    abstract function volumes();


    /**
     * Get the value of service
     */
    public function getService(): String
    {
        return $this->service;
    }

    /**
     * Get the value of agencia
     */
    public function getAgencia(): String
    {
        return $this->agencia;
    }

    /**
     * Get the value of products
     */
    public function getProducts(): array
    {
        return $this->products;
    }


    /**
     * Get the value of volumes
     */
    public function getVolumes(): array
    {
        return $this->volumes;
    }
}
