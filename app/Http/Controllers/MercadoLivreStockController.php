<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MercadoLivreStockController extends Controller
{
     /**
     * Atualiza o estoque de um produto no Mercado Livre.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStock(Request $request)
    {
        // Validação dos dados da requisição
        $validated = $request->validate([
            'product_id' => 'required|string',  // ID do produto no Mercado Livre
            'quantity' => 'required|integer|min:0',  // Quantidade disponível
            'access_token' => 'required|string',  // Token de acesso do Mercado Livre
        ]);

        // Definindo a URL da API do Mercado Livre
        $url = "https://api.mercadolibre.com/items/{$validated['product_id']}";

        // Payload para atualização do estoque
        $payload = [
            'available_quantity' => $validated['quantity']
        ];

        // Enviando a requisição para o Mercado Livre
        $response = Http::withToken($validated['access_token'])
            ->put($url, $payload);

        // Retornando a resposta
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'Estoque atualizado com sucesso.',
                'data' => $response->json(),
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erro ao atualizar o estoque.',
            'error' => $response->json(),
        ], $response->status());
    }
}
