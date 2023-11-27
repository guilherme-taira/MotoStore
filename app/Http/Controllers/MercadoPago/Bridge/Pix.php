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

    private array $item;
    private float $frete;
    public function __construct($item, $frete)
    {
        $this->item = $item;
        $this->frete = $frete;
    }

    public function criarProdutos()
    {
        ML::setAccessToken("APP_USR-1040870078984189-112414-53f0b1b08d3103e224c9276ae6e21808-1562628572");
        $produto = [];

        foreach ($this->getItem() as $value) {
            // serealiza todo os produtos no array
            $data = Products::find($value['produto']);
            $item = new MercadoItem();
            $item->title = $data['title'];
            $item->quantity = $value['quantidade'];
            $item->unit_price = floatval($data['price']);
            //$item->unit_price = $data['pricePromotion'] > 0 ? $data['pricePromotion'] : $data['price'];
            $produto[] = $item;
        }
        print_r($produto);
       // array_push($produto, $this->gerarFrete($this->frete));
        return $produto;
    }

    protected function _gerar()
    {
        return $this->tipopagamento->GerarPagamentoMercadoPago($this->criarProdutos());
    }

    protected function gerarFrete($valor)
    {
        $item = new MercadoItem();
        $item->title = 'Frete';
        $item->quantity = 1;
        $item->unit_price = $valor;
        return $item;
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

    /**
     * Get the value of frete
     */
    public function getFrete(): float
    {
        return $this->frete;
    }

    /**
     * Set the value of frete
     */
    public function setFrete(float $frete): self
    {
        $this->frete = $frete;

        return $this;
    }
}
