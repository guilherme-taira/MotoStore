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
use App\Models\FcmToken;
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

            $este = User::GetDataUserAndToken(16);
            print_r($este);
            // $users = FcmToken::leftJoin('users', 'fcm_tokens.user_id', 'users.id')->get();

            // foreach ($users as $value) {
            //     Log::alert(json_encode($value));

            //     if ($value && $value->token) { // 🔥 Verifica se o usuário tem token FCM

            //         // 🔥 Criar apenas UMA instância do Factory para Firebase
            //         $factory = (new Factory)
            //             ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            //             ->withDatabaseUri("https://afilidrop-33bcf-default-rtdb.firebaseio.com");

            //         // 🔥 Criar instâncias do Firebase Database e Messaging
            //         $database = $factory->createDatabase();
            //         $messaging = $factory->createMessaging();

            //         // 🔥 Salvar a notificação no Realtime Database
            //         $database->getReference("notificacoes/{$value->id}")->push([
            //             'mensagem' => "Olá Davi Santos",
            //             'body' => "Você Vendeu Bucha de lavar Pratos..",
            //             'timestamp' => now()->format('Y-m-d H:i:s'),
            //             'lida' => false
            //         ]);

            //         // 🔥 Enviar notificação via FCM
            //         $message = CloudMessage::withTarget('token', $value->token)
            //             ->withNotification(Notification::create(
            //                 "Olá Davi Santos",
            //                 "Você Vendeu Bucha de lavar Pratos.."
            //             ))
            //             ->withData([
            //                 'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            //             ]);

            //         $messaging->send($message);

            //         // 🔥 Retorna apenas uma resposta final
            //         return response()->json(["success" => true, "message" => "Notificação enviada!"]);
            //     }
            // }
        }
}
