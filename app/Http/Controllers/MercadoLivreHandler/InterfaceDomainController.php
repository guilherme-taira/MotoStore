<?php

namespace App\Http\Controllers\MercadoLivreHandler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

Interface InterfaceDomainController extends Controller
{
    public function handlerAttributes($attributes);
}
