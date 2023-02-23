<?php

namespace App\Http\Controllers\Yapay\Pagamentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

interface interfacePagamento
{
    public function getToken();
    public function setStatus(array $status);
}
