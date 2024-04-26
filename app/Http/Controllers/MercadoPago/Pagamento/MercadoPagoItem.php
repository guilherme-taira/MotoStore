<?php

namespace App\Http\Controllers\Mercadopago\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MercadoPagoItem extends Controller
{
    public $id;
    public $title;
    public $quantity;
    public $currency_id = "BRL";
    public $unit_price;
    public array $itens;

    public function __construct($id,$title,$quantity,$currency_id = "BRL",$unit_price)
    {
        $this->id = $id;
        $this->title = $title;
        $this->quantity = $quantity;
        $this->currency_id = $currency_id;
        $this->unit_price = $unit_price;
    }



    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Get the value of quantity
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Get the value of currency_id
     */
    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    /**
     * Get the value of unit_price
     */
    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    /**
     * Get the value of itens
     */
    public function getItens(): array
    {
        return $this->itens;
    }

    /**
     * Set the value of itens
     */
    public function setItens(array $itens): self
    {
        $this->itens = $itens;

        return $this;
    }
}
