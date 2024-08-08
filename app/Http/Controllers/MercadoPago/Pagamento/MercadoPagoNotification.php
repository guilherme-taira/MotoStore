<?php

namespace App\Http\Controllers\Mercadopago\Pagamento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\ImplementSendNoteOrderClient;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Http\Controllers\Shopify\ShippingController;
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

    public function notificationTrakingMelhorEnvio(Request $request){
        Log::critical(json_encode($request->all()));
    }

    public function notificationShopify(Request $request){

        Log::critical(json_encode($request->all()));
        if(isset($request->id) && isset($request->fulfillments)){

            // PEGA OS DADOS DO PEDIDO
            $shopifyData = ShippingUpdate::where('id_shopify','=',$request->id)->first();

            if(count($request->fulfillments) > 0){
                $setShipping = new ShippingController($shopifyData,$request->fulfillments);
                $setShipping->setShipping();
            }

            $nota = "";
            // NOTIFICA CAMPO DE OBSERVACAO NO MELI >
            if(isset($request->note_attributes)){
                foreach ($request->note_attributes as $notes) {
                    // Log::critical($this->getFirstNumber($notes['name']));
                    $nota = $this->getFirstNumber($notes['name']);
                }
                $noteSend = new ImplementSendNoteOrderClient($shopifyData->id_mercadoLivre,  $nota . " - " . $shopifyData->rastreio, $shopifyData->id_vendedor);
                $noteSend->send();
            }
        }

        return response()->json("ok",200);
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

    function getFirstNumber($string) {
        // Usa expressão regular para encontrar o primeiro número na string
        preg_match('/\d+/', $string, $matches);
        // Retorna o primeiro número encontrado ou null se não encontrar nenhum número
        return $matches[0] ?? null;
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
