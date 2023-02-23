<?php

namespace App\Http\Controllers\Yapay\Pagamentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class AbstractVerificaPagamento
{
    public abstract function ColocarFila(String $token): PagamentoController;
}
