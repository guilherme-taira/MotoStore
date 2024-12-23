<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoLivreStockController extends Controller
{

    private $product_id;
    private $quantity;
    private $active;
    private $estoque_minimo;
    private $onwer;

    public function __construct($product_id,$quantity,$active = 0,$estoque_minimo = 0,$onwer)
    {
        $this->product_id = $product_id;
        $this->quantity = $quantity;
        $this->active = $active;
        $this->estoque_minimo = $estoque_minimo;
        $this->onwer = $onwer;
    }
     /**
     * Atualiza o estoque de um produto no Mercado Livre.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStock()
    {
        // Definindo a URL da API do Mercado Livre
        $url = "https://api.mercadolibre.com/items/{$this->product_id}";

        $token = token::where('user_id','=',$this->onwer)->first();

        if (!$this->active) {
            // Se o produto estiver ativo, pausa o produto e zera o estoque
            $payload = [
                'available_quantity' => $this->quantity,
                'status' => 'paused'
            ];
        } else {
            // Verifica se o estoque mínimo é maior ou igual à quantidade atual
            if ($this->quantity <= $this->estoque_minimo) {
                // Se o estoque mínimo for atingido ou ultrapassado, pausa o produto e zera o estoque
                $payload = [
                    'status' => 'paused',
                    'available_quantity' => $this->quantity,
                ];
            } else {
                // Caso contrário, atualiza o estoque com a quantidade validada
                $payload = [
                    'available_quantity' => $this->quantity,
                    'status' => 'active'
                ];
            }
        }


        // Enviando a requisição para o Mercado Livre
        $response = Http::withToken($token->access_token)
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
