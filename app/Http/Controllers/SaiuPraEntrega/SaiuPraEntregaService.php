<?php

namespace App\Http\Controllers\SaiuPraEntrega;

use App\Http\Controllers\Controller;
use App\Models\ShippingUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SaiuPraEntregaService extends Controller
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = 'https://api.saiupraentrega.com.br';
        $this->token = '413|PzwC3PSXTRVAdr49HRu2kTtYoCcJ9cdZQ7CV7mmK81ee4d71';
    }

    public function createPackage($data)
    {
        $response = Http::withToken($this->token)
                        ->acceptJson()
                        ->post("{$this->baseUrl}/packages", $data);
        if(isset($response['data']['id'])){
            Log::alert("ENTROU NO DATA ID NA CRIACAO DO RASTREIO: > ");
            $this->setFields($response['data']['id'],$data['tracking_code']);
        }
        Log::alert($response->json());
        return $response->json();
    }

    public function setFields($id,$traking){

        $data = [
            'id_rastreio' => $id,
        ];

        // Condições para encontrar o registro
        $conditions = [
            'rastreio' => $traking,
        ];
        // Crie ou atualize o registro
        ShippingUpdate::updateOrCreate($conditions, $data);
    }
}
