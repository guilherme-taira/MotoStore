<?php

namespace App\Http\Controllers\Mercadopago\Pagamento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Models\ShippingUpdate;
use App\Models\Shopify;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoNotification extends Controller
{

    public function notificationTraking(Request $request){
        \App\Jobs\aliexpressTraking::dispatch($request->all());
    }

    public function notificationShopify(Request $request){

        $data = json_decode(json_encode($request->all()));
        if(isset($data->id)){
            Log::critical(" DEFAULT GUI  ----------". $data->id);
            $shopifyData = ShippingUpdate::getDataById($data->id);
            Log::debug(json_encode($shopifyData));
            Log::emergency(json_encode($data));
        }else{
            Log::critical(json_encode($request->all()));
        }

        // switch ($request->order) {
        //     case 'order':
        //         Log::critical($request->fulfillments);
        //         break;
        //         case 'fulfillment_order':
        //             Log::alert($request->fulfillment_order);
        //             break;
        //     default:
        //         Log::critical(" DEFAULT GUI  ----------". json_encode($request->id));
        //         Log::critical(json_encode($request->fulfillments));
        //         break;
        // }
    }


    public function notification(Request $request){

        // GET TOKEN
        $userML = token::where('user_id_mercadolivre', $request->user_id)->first();

        if(isset($request->_id)){

            if($userML){
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
                            case 'items':
                            //    \App\Jobs\productMercadoLivreController::dispatch($request->resource,$request->topic,$request->user_id, $userML->access_token);
                                break;
                    default:
                        break;
                }
            }


        }
    }
}
