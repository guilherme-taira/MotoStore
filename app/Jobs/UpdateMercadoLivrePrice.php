<?php

namespace App\Jobs;

use App\Http\Controllers\MercadoLivre\MercadoLivrePriceUpdater;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateMercadoLivrePrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productId;
    protected $newPrice;
    protected $accessToken;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($productId, $newPrice, $accessToken)
    {
        $this->productId = $productId;
        $this->newPrice = $newPrice;
        $this->accessToken = $accessToken;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $endpoint = "https://api.mercadolibre.com/items/{$this->productId}";
        $payload = [
            'price' => $this->newPrice,
        ];

        try {
            // Enviar a requisição para a API do Mercado Livre
            $response = Http::withToken($this->accessToken)
                ->put($endpoint, $payload);

            // Verificar se a requisição foi bem-sucedida
            if ($response->successful()) {
                Log::info("Preço atualizado com sucesso para o produto {$this->productId}.", [
                    'response' => $response->json(),
                ]);
            } else {
                Log::error("Erro ao atualizar o preço para o produto {$this->productId}.", [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar preço no Mercado Livre para o produto {$this->productId}: " . $e->getMessage());
        }
    }
}
