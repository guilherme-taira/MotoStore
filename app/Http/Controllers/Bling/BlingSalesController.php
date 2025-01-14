<?php

namespace App\Http\Controllers\Bling;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlingSalesController extends Controller
{
    protected $blingService;
    protected $data;

    public function __construct(BlingApiService $blingService,$data)
    {
        $this->blingService = $blingService;
        $this->data = $data;
    }

    public function sendSale(Request $request)
    {
        try {

            $response = $this->blingService->sendSale($this->data);
            Log::alert($response);
             // Verificar se a resposta da API é bem-sucedida
             if (isset($response['status']) && $response['status'] === 'success') {
                // Lógica para sucesso
                return response()->json([
                    'success' => true,
                    'message' => 'Venda enviada com sucesso!',
                    'data' => $response['data'], // Dados da resposta
                ]);
            } else {
                // Lógica para falha
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao enviar venda: ' . ($response['message'] ?? 'Mensagem não especificada'),
                ], 400);
            }

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
