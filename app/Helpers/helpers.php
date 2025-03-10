<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

if (!function_exists('traduzirSubstatusML')) {
    function traduzirSubstatusML($substatus) {
        $substatuses = [
            'in_warehouse' => 'No Armazém',
            'ready_to_pack' => 'Pronto para Embalar',
            'packed' => 'Embalado',
            'in_packing_list' => 'Na Lista de Embalagem',
        ];

        return $substatuses[strtolower($substatus)] ?? $substatus;
    }
}

if (!function_exists('pushNotificationApp')) {
    function pushNotificationApp($msgHeader, $msgBody, $token,$user)
    {
       // 🔥 Criar apenas UMA instância do Factory para Firebase
       $factory = (new Factory)
       ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
       ->withDatabaseUri("https://afilidrop-33bcf-default-rtdb.firebaseio.com");

   // 🔥 Criar instâncias do Firebase Database e Messaging
   $database = $factory->createDatabase();
   $messaging = $factory->createMessaging();

   // 🔥 Salvar a notificação no Realtime Database
   $database->getReference("notificacoes/{$user}")->push([
       'mensagem' => $msgHeader,
       'body' => $msgBody,
       'timestamp' => now()->format('Y-m-d H:i:s'),
       'lida' => false
   ]);

   // 🔥 Enviar notificação via FCM
   $message = CloudMessage::withTarget('token', $token)
       ->withNotification(FirebaseNotification::create(
        $msgHeader,
          $msgBody
       ))
       ->withData([
           'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
       ]);

   $messaging->send($message);


    }
}

// if (!function_exists('pushNotificationApp')) {
//     function pushNotificationApp($msgHeader, $msgBody, $token)
//     {
//         $factory = (new Factory)
//             ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

//         $messaging = $factory->createMessaging();
//         $message = CloudMessage::withTarget('token', $token)
//             ->withNotification(FirebaseNotification::create($msgHeader, $msgBody))
//             ->withData([
//                 'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
//             ]);

//         $messaging->send($message);
//     }
// }

if (!function_exists('traduzirStatusPagamento')) {
    function traduzirStatusPagamento($status) {
        $statuses = [
            'approved' => 'Aprovado',
            'pending' => 'Pendente',
            'in_process' => 'Em processamento',
            'rejected' => 'Rejeitado',
            'refunded' => 'Reembolsado',
            'charged_back' => 'Estornado',
            'cancelled' => 'Cancelado',
            'authorized' => 'Autorizado',
            'paid' => 'Aprovado',
            // Adicione outros status de pagamento conforme necessário
        ];

        return $statuses[$status] ?? $status; // Se não houver tradução, retorna o valor original
    }
}
