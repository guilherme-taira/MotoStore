<?php

namespace App\Http\Controllers\MercadoPago\Bridge;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        Log::emergency(json_encode($this->getItem()));

        $token = token::where('user_id',Auth::user()->id)->first();
        ML::setAccessToken($token->access_token);
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
