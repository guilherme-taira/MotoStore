<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Bling\BlingContatos;
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
use App\Models\BlingCreateUserByFornecedor;
use App\Models\Contato;
use App\Models\IntegracaoBling;
use App\Models\order_site;
use App\Models\ShippingUpdate;
use App\Models\Shopify;
use App\Models\token;
use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

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


        public function teste(Request $request) {
            // Pegue o token do dispositivo para enviar a notificação
            $token = "epf7FGyeQBiX8cpZO3TuQU:APA91bG8CzIPLNvd27JwpKxAtB7eSSDSmx6V57t_GUPeUW5qdFLjr6bcWsxz_iEfMGfjX0hART_BKp_lfkI-k-XzMA-9NYByF8chOyy6bM23vaE2muhGJOQ"; // Pegue do banco de dados ou passe no request

            $factory = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

            $messaging = $factory->createMessaging();

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(Notification::create("Olá Você Vendeu!!", "Grelha 40x50 para churrasco inox"))
                ->withData([
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'title' => "Olá Você Vendeu!!", // Adicionando título nos dados
                    'body' => "Grelha 40x50 para churrasco inox" // Adicionando corpo nos dados
                ]);

            $messaging->send($message);

            print_r($messaging);
            return response()->json(['success' => true, 'message' => 'Notificação enviada!']);

    }
}
