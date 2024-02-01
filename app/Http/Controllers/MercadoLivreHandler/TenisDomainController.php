<?php

namespace App\Http\Controllers\MercadoLivreHandler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenisDomainController implements InterfaceDomainController
{
    public function handlerAttributes($attributes){
        print_r($attributes);
    }

    public function handlerError($error){
        print_r($error);
    }
}
