<?php

namespace App\Http\Controllers\MercadoLivre\Cliente;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Yapay\AbstractTipoDeCobranca;
use App\Http\Controllers\Yapay\GeradorPagamento;
use App\Http\Controllers\Yapay\Pix;
use App\Http\Controllers\Yapay\ProdutoMercadoLivre;
use App\Models\order_site;
use App\Models\pivot_site;
use App\Models\product_site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class implementacaoCliente extends AbstractTipoDeCobranca
{

    function VerifyIfExist($id): bool
    {
        return true;
    }

    function CriarPagamento()
    {
        $newPix = new Pix("e1cb0277fbfd2fd", "B", $this->getPostalCode(), $this->getStreet(),$this->getNumber(), "A", $this->getNeighborhood(), $this->getCity(), $this->getState(),$this->getName(), $this->getCpf(), $this->getEmail(), $this->getTransactionProduct(), 27, 1);
        $data = $newPix->CriarPagamento();
        return $data;
    }

    public function EnviarOrdem(implementacaoCliente $data)
    {
        $gerador = new GeradorPagamento($data);
        $gerador->resource();
    }
}
