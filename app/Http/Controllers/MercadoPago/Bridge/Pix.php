<?php

namespace App\Http\Controllers\MercadoPago\Bridge;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use MercadoPago\SDK as ML;
use MercadoPago\Payment as MercadoPreference;
use MercadoPago\Item as MercadoItem;
use MercadoPago\Payer as payer;

class Pix extends AbstractPagamento
{

    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function criarProdutos()
    {
        ML::setAccessToken("APP_USR-3029233524869952-033008-6f7fbb3eb9e6d2626a3b7eec9ac6b5d6-1272736385");
        $produto = [];
        foreach ($this->getItem() as $value) {
            // serealiza todo os produtos no array
            $data = Products::find($value['produto']);
            $item = new MercadoItem();
            $item->title = $data['title'];
            $item->quantity = $value['quantidade'];
            $item->unit_price = $data['pricePromotion'] > 0 ? $data['pricePromotion'] : $data['price'];
            $produto[] = $item;
        }
        return $produto;
    }

    protected function _gerar()
    {
        return $this->tipopagamento->GerarPagamentoMercadoPago($this->criarProdutos());
    }

    /**
     * Get the value of item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set the value of item
     */
    public function setItem(MercadoItem $item): self
    {
        $this->item = $item;

        return $this;
    }
}
