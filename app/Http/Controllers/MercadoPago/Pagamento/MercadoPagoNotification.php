<?php

namespace App\Http\Controllers\Mercadopago\Pagamento;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\ImplementSendNoteOrderClient;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Http\Controllers\SaiuPraEntrega\SaiuPraEntregaService;
use App\Http\Controllers\SaiuPraEntrega\SendNotificationPraEntregaController;
use App\Http\Controllers\SaiuPraEntrega\TypeMessageController;
use App\Http\Controllers\Shopify\ShippingController;
use App\Models\IntegracaoBling;
use App\Models\ShippingUpdate;
use App\Models\Shopify;
use App\Models\token;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoNotification extends Controller
{

    public function notificationTraking(Request $request){
        \App\Jobs\aliexpressTraking::dispatch($request->all());
    }

    public function notificationTrakingMelhorEnvio(Request $request){
        Log::critical(json_encode($request->all()));
        $this->GetType($request);
        // $shipping = ShippingUpdate::where('id_mercadoLivre','2000008848830650')->first();
        // $notify = new SendNotificationPraEntregaController($shipping->traking,"Olá Querido Cliente seu Rastreio ".$shipping->rastreio,$shipping->id_mercadoLivre,$shipping->id_user,$shipping->id_vendedor);
        // $notify->save();
    }

    function containsTransferencia($data) {
        // Verifica se o status contém a palavra "transferência"
        if (isset($data) && strpos($data, 'transferência') !== false) {
            return true;
        }
        return false;
    }

    public function GetType(Request $request){
        $object = json_decode(json_encode($request->data));
        switch ($request->event) {
            case 'event.created':
                    $msg = "Informamos que seu produto já chegou na/em ".$request->all()['data']['current']['unit_name']." e em breve continuará o trajeto até você. Obrigado pela confiança!";
                    $transferencia = $this->containsTransferencia($request->all()['data']['current']['status']);
                    if($request['data']['current']['was_delivered'] == true){
                        $msg = "Informamos que seu produto esta em trajeto até você. Obrigado pela confiança!";
                    }else if($transferencia){
                        $msg = "Informamos que seu produto esta em tranferência da ".$request->all()['data']['current']['unit_name']." e em breve continuará o trajeto até você. Obrigado pela confiança!";
                    }

                    $shipping = ShippingUpdate::where('rastreio','=',$object->current->package->tracking_code)->first();
                    $message = new TypeMessageController($object->current,$shipping);
                    $message->setFields();

                    // NOTIFY CLIENT NOT IMPLEMENTED
                    $notify = new SendNotificationPraEntregaController($shipping->id,$shipping->traking,
                    $msg,$shipping->id_mercadoLivre,$shipping->id_vendedor,$shipping->id_user);
                    $notify->save();
                break;
                case 'event.updated':
                        // NÂO IMPLEMENTAR
                    break;
            default:
                # code...
                break;
        }
    }

    public function notificationShopify(Request $request){

        Log::critical(json_encode($request->all()));
        if(isset($request->id) && isset($request->fulfillments)){

            // PEGA OS DADOS DO PEDIDO
            $shopifyData = ShippingUpdate::where('id_shopify','=',$request->id)->first();
            // Log::alert(json_encode($shopifyData));
            if(count($request->fulfillments) > 0){
                $setShipping = new ShippingController($shopifyData,$request->fulfillments);
                $setShipping->setShipping();
            }

            $nota = "";
            // NOTIFICA CAMPO DE OBSERVACAO NO MELI >
            if(isset($request->note_attributes) && count($request->note_attributes) > 0){
                foreach ($request->note_attributes as $notes) {
                    // Log::critical($this->getFirstNumber($notes['name']));
                    $nota = $this->getFirstNumber($notes['name']);
                }
                if($nota != "" && $shopifyData->rastreio == NULL && $shopifyData->observacaomeli != "X"){
                    $noteSend = new ImplementSendNoteOrderClient($shopifyData->id_mercadoLivre, "Pedido - ".  $nota . " - " . $shopifyData->rastreio, $shopifyData->id_vendedor,$shopifyData->id);
                    $noteSend->send();
                }else if($shopifyData->rastreio != NULL && $shopifyData->observacaomeli == "X"){
                    Log::alert("ATUALIZOU O RASTREIO NO ML " . $shopifyData->rastreio);
                    $noteSend = new ImplementSendNoteOrderClient($shopifyData->id_mercadoLivre, "Pedido - ".  $nota . " - " . $shopifyData->rastreio, $shopifyData->id_vendedor,$shopifyData->id,$shopifyData->id_meli);
                    $noteSend->send();

                }
            }
        }

        return response()->json("ok",200);
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

        // if(isset($request->_id)){

            // Verifica se é 'topic' ou 'type' e atribui à variável $eventType
            $eventType = $request->topic ?? $request->type;

            if($userML){
                $dataAtual = new DateTime();
                // GET NEW TOKEN
                $newToken = new RefreshTokenController($userML->refresh_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $request->user_id);
                $newToken->resource();

                $userML = token::where('user_id_mercadolivre', $request->user_id)->first();

                switch ($eventType){
                    case 'payment':
                        Log::alert($request->all());
                        \App\Jobs\MercadoPagoPagamentos::dispatch($request->data['id']);
                        break;
                        case 'orders_v2':
                            \App\Jobs\getOrderMercadoLivre::dispatch($request->resource,$request->topic,$request->user_id, $userML->access_token)->delay(Carbon::now()->addSeconds(20));
                            break;
                            case 'items':
                            //    \App\Jobs\productMercadoLivreController::dispatch($request->resource,$request->topic,$request->user_id, $userML->access_token);
                                break;
                    default:
                        break;
                }
            }


        }

        public function notificationBling(Request $request){

            // GET TOKEN
            Log::critical($request->all());
        }
    // }
}
