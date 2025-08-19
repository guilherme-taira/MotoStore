<?php

namespace App\Console\Commands;

use App\Models\SellerAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // Importe a classe Carbon para usá-la

class RefreshTikTokTokens extends Command
{
    protected $signature = 'tiktok:refresh-tokens';
    protected $description = 'Refresh TikTok seller access tokens';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Iniciando atualização dos tokens...');

        // Busca contas que expiram nas próximas 5h
        $accounts = SellerAccount::where('expires_in', '<', now()->addHours(5))->get();

        foreach ($accounts as $account) {
            $this->refreshToken($account);
        }

        $this->info('Processamento de refresh concluído.');
    }

    protected function refreshToken(SellerAccount $account)
    {
        // A documentação do TikTok Shop exige uma requisição GET com parâmetros na URL.
        $response = Http::get('https://auth.tiktok-shops.com/api/v2/token/refresh', [
            'app_key' => config('services.tiktok.client_id'),
            'app_secret' => config('services.tiktok.client_secret'),
            'refresh_token' => $account->refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        $status = $response->status();
        $body = $response->body();
        $tokenData = $response->json();


        // Verifica se a resposta é um JSON válido e se o token foi retornado
        if ($response->successful() && isset($tokenData['data']['access_token'])) {

            // Converte o timestamp Unix para um objeto Carbon
            $accessTokenExpiresAt = Carbon::createFromTimestamp($tokenData['data']['access_token_expire_in']);

            $account->update([
                'access_token' => $tokenData['data']['access_token'],
                'refresh_token' => $tokenData['data']['refresh_token'] ?? $account->refresh_token,
                'expires_in' => $accessTokenExpiresAt,
            ]);

            $this->info("Token atualizado para seller: {$account->seller_id}");
        } else {
            $this->error("Falha ao atualizar seller: {$account->seller_id}");

            Log::error('TikTok Token Refresh Error', [
                'seller_id' => $account->seller_id,
                'http_status' => $status,
                'response_body' => $body,
                'parsed_json' => $tokenData,
            ]);
        }
    }
}
