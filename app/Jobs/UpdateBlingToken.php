<?php

namespace App\Jobs;

use App\Models\IntegracaoBling;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateBlingToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Busque todas as integrações que estão próximas de expirar
        $integracoes = IntegracaoBling::whereNotNull('expires_at')
            ->where('expires_at', '<=', now()->addMinutes(10)) // Atualiza se expirar em 10 minutos
            ->get();

        foreach ($integracoes as $integracao) {
            try {
                // Combine as credenciais em uma string formatada
                $clientCredentials = base64_encode("{$integracao->client_id}:{$integracao->client_secret}");

                // Faça a requisição POST para atualizar o token
                $response = Http::asForm()
                    ->withHeaders([
                        'Authorization' => "Basic $clientCredentials",
                        'Accept' => 'application/json',
                    ])
                    ->post('https://www.bling.com.br/Api/v3/oauth/token', [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $integracao->refresh_token,
                    ]);

                // Verificar a resposta
                if ($response->successful()) {
                    $data = $response->json();

                    // Atualizar o token e a nova data de expiração no banco de dados
                    $integracao->update([
                        'access_token' => $data['access_token'],
                        'refresh_token' => $data['refresh_token'] ?? $integracao->refresh_token,
                        'expires_at' => now()->addSeconds($data['expires_in']),
                    ]);

                    Log::info("Token atualizado com sucesso para o usuário {$integracao->user_id}.");
                } else {
                    Log::error("Erro ao atualizar o token para o usuário {$integracao->user_id}: " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("Exceção ao atualizar o token para o usuário {$integracao->user_id}: " . $e->getMessage());
            }
        }
    }
}
