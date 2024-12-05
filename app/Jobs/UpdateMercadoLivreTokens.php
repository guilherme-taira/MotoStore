<?php

namespace App\Jobs;

use App\Models\token;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateMercadoLivreTokens implements ShouldQueue
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
       // Obter todos os registros relacionados ao Mercado Livre
       $tokens = token::whereNotNull('user_id_mercadolivre')->get();

       foreach ($tokens as $acesso) {
        // Verificar se faltam menos de 10 minutos para expirar o token
        $expirationTime = Carbon::parse($acesso->datamodify);
        $currentTime = now();

        // Calcular diferença em minutos (positivo apenas se a data de expiração for futura)
        $timeDifference = $currentTime->diffInMinutes($expirationTime,false);

           $endpoint = "https://api.mercadolibre.com/oauth/token"
            . '?grant_type=refresh_token'
            . '&client_id=3029233524869952'
            . '&client_secret=y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV'
            . '&refresh_token=' . $acesso->refresh_token;

            if ($timeDifference <= 10) { // Atualizar se faltar 10 minutos ou se já estiver expirado
                // Log para depuração
                Log::alert("User ID: {$acesso->user_id_mercadolivre} -- Tempo restante (em minutos): {$timeDifference}");
               try {

                //Requisição para atualizar o token
                $response = Http::asForm()->post($endpoint);

                if ($response->successful()) {
                    $dados = $response->json();

                    if (isset($dados['access_token'])) {
                        $acesso->update([
                            'access_token' => $dados['access_token'],
                            'datamodify' => now()->addHours(6)->format('Y-m-d H:i:s'),
                        ]);

                        Log::info("Token atualizado com sucesso para User ID: {$acesso->user_id_mercadolivre}");
                    } else {
                        Log::error("Erro ao atualizar token para User ID: {$acesso->user_id_mercadolivre}: " . json_encode($dados));
                    }
                }
               } catch (\Exception $e) {
                   Log::error("Erro ao atualizar token para user_id {$acesso->user_id_mercadolivre}: " . $e->getMessage());
               }
           }
       }
   }
    }
