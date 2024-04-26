<?php

namespace App\Http\Controllers\Mercadopago\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoNotification extends Controller
{
    public function notification(Request $request){
        if(isset($request->data['id'])){
            \App\Jobs\MercadoPagoPagamentos::dispatch($request->data['id'],$request->user_id);
        }
    }
}
