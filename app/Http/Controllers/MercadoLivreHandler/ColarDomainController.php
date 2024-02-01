<?php

namespace App\Http\Controllers\MercadoLivreHandler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ColarDomainController implements InterfaceDomainController
{
    public function handlerAttributes($attributes){
        print_r($attributes);
    }
}
