<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

interface Handler{
    public function setNext(Handler $manipulador): Handler;
    public function PodeManipular($request):bool;
}
