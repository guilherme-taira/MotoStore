<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\financeiro;
use App\Models\order_site;
use App\Models\SellerAccount;
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
        if (isset($payload['type'])) {
            $event = $payload['type'];
            $data = $payload['data'] ?? [];
            switch ($event) {
                case '1':
                    $this->handleOrderCreated($payload);
                    break;

                case '2':
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
    protected function handleOrderCreated($data)
    {
        Log::info('Novo pedido criado:', $data);
        // PEGA A VENDA
        $venda = new TikTokProductController();
        $seller = SellerAccount::where('shop_id',$data['shop_id'])->first();
        $venda->getOrderDetails($data['data']['order_id'],$seller);
    }

    /**
     * Trata o cancelamento de pedido.
     */
    protected function handleOrderCancelled(array $data)
    {
        Log::info('Pedido cancelado:', $data);

        // 1. Atualizar a tabela 'order_site'
        // O campo para o status é 'status_id'
        order_site::where('numeropedido', $data['order_id'])->update([
            'status_id' => 5
        ]);

        // 2. Atualizar a tabela 'financeiro'
        // O campo para o status é 'status'
        financeiro::where('token_transaction', $data['order_id'])->update([
            'status' => 5
        ]);

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
