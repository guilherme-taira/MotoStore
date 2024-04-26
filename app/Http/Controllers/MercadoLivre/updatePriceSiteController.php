<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Events\noficaUserPreco;
use App\Http\Controllers\Controller;
use App\Listeners\notificaUserCadastrado;
use App\Models\Products;
use App\Models\User;
use App\Notifications\notificaUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class updatePriceSiteController extends AbstractPriceProdutoController
{
    public function vericaPreco(){

        $produto = Products::where('id',$this->getProduto()->id)->first();

        if($this->getNewPrice() != $this->getOldPrice()){
            $produtos = Products::getAllUserProduct($this->getProduto()->id);
            foreach ($produtos as $key => $produto) {
                // INSERE A NOTIFICAÇÃO
                $user = User::find($produto->user_id);
                $user->notify(new notificaUser($user,$this->getProduto()));
            }
            // FILA PARA COLOCAR O PRODUTO PRA ATUALIZAR EM 48 HORAS.
            \App\Jobs\updatePriceSite::dispatch($this->getProduto()->id,$this->getNewPrice())->delay(now()->addMicrosecond(5));
        }

    }
}
