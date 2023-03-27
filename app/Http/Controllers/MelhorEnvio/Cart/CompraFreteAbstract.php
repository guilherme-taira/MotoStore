<?php

namespace App\Http\Controllers\MelhorEnvio\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class CompraFreteAbstract
{
    private String $orderid;

    public function __construct($orderid)
    {
        $this->orderid = $orderid;
    }

    abstract function enviarOrdem();

    /**
     * Get the value of orderid
     */
    public function getOrderid(): String
    {
        return $this->orderid;
    }

    /**
     * Set the value of orderid
     */
    public function setOrderid(String $orderid): self
    {
        $this->orderid = $orderid;

        return $this;
    }
}
