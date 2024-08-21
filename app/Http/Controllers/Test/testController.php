<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Generatecharts;
use App\Http\Controllers\MercadoLivre\GeneratechartsSneakers;
use App\Http\Controllers\MercadoLivre\MlbCallAttributes;
use App\Http\Controllers\MercadoLivre\MlbTipos;
use App\Http\Controllers\MercadoLivreHandler\ConcretoDomainController;
use App\Http\Controllers\MercadoLivreHandler\getDomainController;
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

        $object = json_decode('{"event":"event.created","data":{"id":7607565,"type":"event","current":{"type":null,"status":"Objeto entregue ao destinat\u00e1rio","unit_name":"Unidade de Distribui\u00e7\u00e3o\/MT","unit_address":{"street":null,"number":null,"district":null,"city":"CUIABA","state":"MT","zip_code":null},"description":"Objeto entregue ao destinat\u00e1rio","description_translated":"Objeto entregue ao destinat\u00e1rio","ignore_last_event":false,"is_address_not_found":false,"is_awaiting_withdrawal":false,"is_delivery_attempt_failed":false,"is_out_for_delivery":false,"is_waiting_tax_payment":false,"was_damaged":false,"was_delivered":true,"was_delivered_to_sender":false,"was_forwarded":false,"was_fulfilled":false,"was_misplaced":false,"was_refused":false,"was_returned":false,"was_scheduled":false,"was_taxed":false,"happened_at":"2024-08-19T20:28:32+00:00","created_at":"2024-08-20T16:28:54+00:00","package":{"id":1410162,"description":"CLIENTE !)","customer_name":"CLiente teste","customer_email":null,"customer_phone":null,"tracking_code":"NM557685964BR","tracking_url":"https:\/\/rastreiodepedido.com\/r\/bq2Gw4pN2z","shipping_company":"correios","fiscal_invoice_code":null,"fiscal_invoice_key":null,"fiscal_invoice_series":null,"created_at":"2024-08-09T17:28:42+00:00","deleted_at":null,"finished_at":"2024-08-19T20:28:32+00:00","address_not_found_at":null,"arrived_withdrawal_at":null,"damaged_at":null,"delivered_at":"2024-08-19T20:28:32+00:00","delivered_to_sender_at":null,"delivery_attempt_failed_at":null,"fulfilled_at":"2024-08-10T14:39:09+00:00","last_forwarding_at":"2024-08-16T15:21:41+00:00","misplaced_at":null,"refused_at":null,"returned_at":null,"scheduled_at":null,"taxed_at":null,"seized_at":null,"waiting_tax_payment_at":null,"last_track_at":"2024-08-20T16:28:54+00:00","last_track_attempt_at":null,"items":[],"destination_address":null}},"previous":null,"changes":[]}}');

        // $shipping = ShippingUpdate::where('id_mercadoLivre','2000008848830650')->first();
        // $notify = new SendNotificationPraEntregaController($shipping->traking,"Olá Querido Cliente seu Rastreio ".$shipping->rastreio,$shipping->id_mercadoLivre,$shipping->id_user,$shipping->id_vendedor);
        // $notify->save();
    }

}
