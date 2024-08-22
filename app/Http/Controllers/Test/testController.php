<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Generatecharts;
use App\Http\Controllers\MercadoLivre\GeneratechartsSneakers;
use App\Http\Controllers\MercadoLivre\MlbCallAttributes;
use App\Http\Controllers\MercadoLivre\MlbTipos;
use App\Http\Controllers\MercadoLivreHandler\ConcretoDomainController;
use App\Http\Controllers\MercadoLivreHandler\getDomainController;
use App\Http\Controllers\SaiuPraEntrega\SaiuPraEntregaService;
use App\Http\Controllers\SaiuPraEntrega\SendNotificationPraEntregaController;
use App\Http\Controllers\SaiuPraEntrega\TypeMessageController;
use App\Http\Controllers\Shopify\LineItem;
use App\Http\Controllers\Shopify\Order;
use App\Http\Controllers\Shopify\SendOrder;
use App\Http\Controllers\Shopify\ShippingAddress;
use App\Http\Controllers\Shopify\ShopifyProduct;
use App\Models\order_site;
use App\Models\ShippingUpdate;
use App\Models\Shopify;
use App\Models\token;
use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class testController extends Controller
{

    function getFormattedDate($date) {
        // Converte a data para o formato 'Y-m-d' se necessário
        $formattedDate = date('Y-m-d', strtotime($date));

        // Obtém o mês abreviado
        $monthAbbreviated = date('M', strtotime($formattedDate));

        $meses = [
            "January" => "Janeiro",
            "February" => "Fevereiro",
            "March" => "Março",
            "April" => "Abril",
            "May" => "Maio",
            "June" => "Junho",
            "July" => "Julho",
            "August" => "Agosto",
            "September" => "Setembro",
            "October" => "Outubro",
            "November" => "Novembro",
            "December" => "Dezembro"
        ];

        if(isset($meses[$monthAbbreviated])){
            $mesTraduzido = $meses[$monthAbbreviated];
        }
        // Obtém o dia do mês
        $dayOfMonth = date('j', strtotime($formattedDate));

        // Formata a data como "M DIA"
        $formattedDate = $mesTraduzido . ',' . $dayOfMonth;

        return $formattedDate;
    }


    public function teste(Request $request){

        $object = json_decode('{
        "description": "Aliexpress pedido",
        "customer_name": "Guilherme Taiar",
        "customer_email": "manual@manual.com",
        "customer_phone": "19999999999",
        "tracking_code": "NM573274327BR",
        "shipping_company": "correios",
        "items": [
            {
                "name" : "produto drop",
                "quantity": 1,
                "value": 0
            }
        ]
        }',true);

        // $data = (new SaiuPraEntregaService())->createPackage($object);
        \App\Jobs\sendRastreioSaiuPraEnrega::dispatch("NM573812471BR");
        // $shipping = ShippingUpdate::where('id_mercadoLivre','2000008848830650')->first();
        // $notify = new SendNotificationPraEntregaController($shipping->traking,"Olá Querido Cliente seu Rastreio ".$shipping->rastreio,$shipping->id_mercadoLivre,$shipping->id_user,$shipping->id_vendedor);
        // $notify->save();
    }

}
