<?php

namespace App\Http\Controllers\MercadoPago\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MercadoPago\Item as MercadoItem;

interface InterfacePagamento
{
    public function GerarPagamentoMercadoPago($item);
}
