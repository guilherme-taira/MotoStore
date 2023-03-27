<?php

namespace App\Http\Controllers\MelhorEnvio;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class MelhorEnvioGetDataController extends MelhorEnvioAbstract
{
    public function pegarFrete()
    {
        $data = [];
        $data['from'] = ["postal_code" => $this->getPostalCodeRemetente()];
        $data['to'] = ["postal_code" => $this->getPostalCodeDestino()];
        $data['package'] = $this->criarProdutos($this->getProducts());
        return $data;
    }

    public function criarProdutos()
    {
        $data = [];
        $produto = [];
        $peso['dimensoes'] = 0;
        $peso['peso'] = 0;
        foreach ($this->getProducts() as $key => $productInCart) {
            foreach ($productInCart as $product) {
                $productInCart = Products::findMany($product['produto'])->first();
                $peso['dimensoes'] += ($productInCart->width *  $productInCart->height * $productInCart->length) * $product['quantidade'];
                $peso['peso'] += $productInCart->weight * $product['quantidade'];
            }
        }

            //$produto['id'] = $productInCart->id;
            $produto['width'] = round(pow($peso['dimensoes'], 1/3), 2);
            $produto['height'] = round(pow($peso['dimensoes'], 1/3), 2);
            $produto['length'] = round(pow($peso['dimensoes'], 1/3), 2);
            $produto['weight'] = $peso['peso'];
            //$produto['insurance_value'] = 2;
            //$produto['quantity'] = $product['quantidade'];
        return $produto;
    }
}
