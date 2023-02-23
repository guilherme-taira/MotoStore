<?php

namespace App\Http\Controllers\Yapay\Pagamentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AtualizaPagamento extends AbstractVerificaPagamento
{
    public function ColocarFila(string $token): PagamentoController
    {
        $AtualizaPedido = new PagamentoController($token);
        $Request = (new RequestApiPagamento($AtualizaPedido))->resource();
        $AtualizaPedido->setStatus($Request);
        return $AtualizaPedido;
    }
}
