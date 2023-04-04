<?php

namespace App\Http\Controllers\MercadoPago\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MercadoPago\SDK as ML;
use MercadoPago\Payment as MercadoPreference;
use MercadoPago\Item as MercadoItem;
use MercadoPago\Payer as payer;

class AllPayments extends AbstractPagamento
{
    public array $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function criarProdutos()
    {
        foreach ($this->getItem() as $value) {
            // serealiza todo os produtos no array
            print_r($value);
            //$produto[] = new produto($value['descricao'], $value['quantidade'], $value['EAN'], $value['img'], $value['cod_prod']);
        }
    }

    protected function _gerar()
    {
       $this->tipopagamento->GerarPagamentoMercadoPago($this->getItem());
    }

    /**
     * Get the value of item
     */
    public function getItem(): array
    {
        return $this->item;
    }

    /**
     * Set the value of item
     */
    public function setItem(array $item): self
    {
        $this->item = $item;

        return $this;
    }
}
