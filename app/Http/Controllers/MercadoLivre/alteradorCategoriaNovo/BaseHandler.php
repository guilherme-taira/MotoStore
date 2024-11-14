<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

abstract class BaseHandler implements Handler{

    private ?Handler $nextHandler = null;

    public final function manipular($solicitacao){
        if($this->PodeManipular($solicitacao)){
           return $this->processar($solicitacao);
        }else{
            if($this->nextHandler != null){
               return $this->nextHandler->manipular($solicitacao);
            }
        }
    }

    public function getProximoManipulador(): ?Handler {
        return $this->nextHandler;
    }

    public function setNext(Handler $manipulador): Handler
    {
        $this->nextHandler = $manipulador;
        return $manipulador;
    }

    protected abstract function processar($request);
}
