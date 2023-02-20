<?php

namespace App\Http\Controllers\Yapay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

interface CobrancaPixController
{
    public function criarPagamento(ProdutoCestaController $data): array;
    public function getDadosCliente($postal_code,$street,$number,$neighborhood,$city,$state,$name,$cpf,$email);
    public function getToken(String $tokenAccount);
}
