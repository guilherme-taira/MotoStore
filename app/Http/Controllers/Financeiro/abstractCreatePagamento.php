<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

abstract class abstractCreatePagamento
{
    abstract public function criarPagamento($status_id,$totalPago,$id_order,$fornecedor,$urlPagamento,$qrcode,$nomeStatus,$token,$frete): InterfacePagamentos;

    private array $produtos;

    public function __construct($produtos)
    {
        $this->produtos = $produtos;
    }

    public function VerificaProdutos(){
        $array = [];
        foreach ($this->getProdutos() as $key => $produto) {
            $produtos = Products::findOrFail($produto['produto']);
            array_push($array,[
                'title' => $produtos['title'],
                'price' => $produtos['price'],
                'quantidade' => $produto['quantidade'],
                'fornecedor' => $produtos['fornecedor_id'],
                'precoPromocional' => $produtos['pricePromotion']
            ]);
        }
        return $array;
    }

    /**
     * Get the value of produtos
     */
    public function getProdutos(): array
    {
        return $this->produtos;
    }

    /**
     * Set the value of produtos
     */
    public function setProdutos(array $produtos): self
    {
        $this->produtos = $produtos;

        return $this;
    }
}
