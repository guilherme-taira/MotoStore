<?php

namespace App\Http\Controllers\MercadoLivre\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

interface ClienteController
{
    public function get($resource);
    public function resource();
}
