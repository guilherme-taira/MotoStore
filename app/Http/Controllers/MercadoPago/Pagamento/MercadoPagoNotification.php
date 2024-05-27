<?php

namespace App\Http\Controllers\Mercadopago\Pagamento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoNotification extends Controller
{
    public function notification(Request $request){
        // GET TOKEN
        $userML = token::where('user_id_mercadolivre', $request->user_id)->first();

        if(isset($request->_id)){

            $dataAtual = new DateTime();
            // GET NEW TOKEN
            $newToken = new RefreshTokenController($userML->refresh_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $request->user_id);
            $newToken->resource();

            switch ($request->topic){
                case 'payment':
                    \App\Jobs\MercadoPagoPagamentos::dispatch($request->id);
                    break;
                    case 'orders_v2':
                        \App\Jobs\getOrderMercadoLivre::dispatch($request->resource,$request->topic,$request->user_id, $userML->access_token);
                        break;
                default:
                    # code...
                    break;
            }

        }
    }
}
