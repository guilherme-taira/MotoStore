<?php

namespace App\Http\Controllers;

use App\Models\ShippingNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class ShippingNotificationController
{
    const URL_BASE_ML = "https://api.mercadolibre.com/";

    private string $resource;
    private string $topic;
    private string $sellerId;
    private string $token;

    /**
     * Construtor para inicializar os dados necessários.
     */
    public function __construct(string $resource, string $topic, string $sellerId, string $token)
    {
        $this->resource = $resource;
        $this->topic = $topic;
        $this->sellerId = $sellerId;
        $this->token = $token;
    }

    /**
     * Método para criar um registro na tabela shipping_notification.
     */
    public function createShippingNotification()
    {
        try {

            $url = "https://api.mercadolibre.com/".$this->resource;

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer $this->token",
                    "x-format-new: true"
                ]
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpCode >= 200 && $httpCode < 300) {
                $data = json_decode($response, false);
                // Criando o registro no banco de dados
                ShippingNotification::create([
                    'external_reference' => $data->external_reference,
                    'shipping_id' => $data->id,
                    'substatus' => $this->traduzirStatus($data->status),
                    'tracking_number' => $data->tracking_number,
                    'tracking_method' => $data->tracking_method,
                    'estimated_delivery_extended' => Carbon::parse($data->lead_time->estimated_delivery_time->date)->format('Y-m-d H:i:s'),
                ]);
        }

        } catch (\Exception $e) {
            // Tratamento de erro
            Log::alert($e->getMessage());
        }
    }

    function traduzirStatus($status)
{
    switch ($status) {
        case 'handling':
            return 'Em preparação';

        case 'ready_to_ship':
        case 'picked_up':
        case 'authorized_by_carrier':
        case 'in_hub':
            return 'A caminho';

        case 'shipped':
            return 'A caminho';

        case 'delivered':
            return 'Entregue';

        default:
            return 'Sem atualização.';
    }
}

}
