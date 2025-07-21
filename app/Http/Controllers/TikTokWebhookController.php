<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TikTokOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TikTokWebhookController extends Controller
{
   /**
     * Recebe e trata os webhooks do TikTok Shop.
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        // Log básico do payload recebido
        Log::info('Webhook TikTok recebido:', $payload);

        // Verifica se veio um evento
        if (isset($payload['event'])) {
            $event = $payload['event'];
            $data = $payload['data'] ?? [];

            switch ($event) {
                case 'ORDER_CREATED':
                    $this->handleOrderCreated($data);
                    break;

                case 'ORDER_CANCELLED':
                    $this->handleOrderCancelled($data);
                    break;

                case 'PACKAGE_UPDATED':
                    $this->handlePackageUpdated($data);
                    break;

                // Adicione mais casos conforme necessário
                default:
                    Log::warning("Evento TikTok não tratado: {$event}");
                    break;
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Trata o evento de novo pedido.
     */
    protected function handleOrderCreated(array $data)
    {
        Log::info('Novo pedido criado:', $data);

        // Exemplo de salvamento do pedido
        TikTokOrder::updateOrCreate(
            ['order_id' => $data['order_id']],
            [
                'status'       => $data['order_status'] ?? 'UNKNOWN',
                'buyer_name'   => $data['buyer_name'] ?? '',
                'total_amount' => $data['total_amount'] ?? 0,
                'items'        => json_encode($data['items'] ?? []),
            ]
        );
    }

    /**
     * Trata o cancelamento de pedido.
     */
    protected function handleOrderCancelled(array $data)
    {
        Log::info('Pedido cancelado:', $data);

        TikTokOrder::where('order_id', $data['order_id'])->update([
            'status' => 'CANCELLED'
        ]);
    }

    /**
     * Trata atualizações do pacote (entrega).
     */
    protected function handlePackageUpdated(array $data)
    {
        Log::info('Atualização de pacote:', $data);

        // Atualize campos no pedido conforme o conteúdo do webhook
        TikTokOrder::where('order_id', $data['order_id'])->update([
            'shipping_status' => $data['shipping_status'] ?? 'UNKNOWN',
        ]);
    }
}
