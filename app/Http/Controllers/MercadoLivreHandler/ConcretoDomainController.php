<?php

namespace App\Http\Controllers\MercadoLivreHandler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConcretoDomainController extends AbstractDomainController
{
    public function CallAttributes($attributes){
        return parent::CallAttributes($attributes);
    }

    public function CallErrorAttributes($error,$produto,$categoria = false,$category_id = null,$newtitle){
       return parent::CallErrorAttributes($error,$produto,$categoria,$category_id,$newtitle);
   }
}
