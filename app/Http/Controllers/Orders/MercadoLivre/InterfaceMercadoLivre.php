<?php

namespace App\Http\Controllers\Orders\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

interface InterfaceMercadoLivre
{
    public function getVenda($sellerId);
    public function saveOrder();
    public function get($resource);
    public function resource();
}
