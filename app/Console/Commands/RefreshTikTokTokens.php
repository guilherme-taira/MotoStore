<?php

namespace App\Console\Commands;

use App\Models\SellerAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshTikTokTokens extends Command
{
    protected $signature = 'tiktok:refresh-tokens';
    protected $description = 'Refresh TikTok seller access tokens';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

 public function handle()
    {
        $this->info('Iniciando atualização dos tokens...');

        // Buscando contas que expiram nas próximas 24h
        $accounts = SellerAccount::where('expires_in', '<', now()->addDay())->get();

        foreach ($accounts as $account) {
            $this->refreshToken($account);
        }

        $this->info('Tokens atualizados com sucesso!');
    }

    protected function refreshToken(SellerAccount $account)
    {
        $response = Http::asForm()->post('https://auth.tiktok-shops.com/oauth/token', [
            'grant_type' => 'refresh_token',
            'client_id' => config('services.tiktok.client_id'),
            'client_secret' => config('services.tiktok.client_secret'),
            'refresh_token' => $account->refresh_token,
        ]);

        $tokenData = $response->json();

        if (isset($tokenData['access_token'])) {
            $account->update([
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'],
                'expires_in' => now()->addSeconds($tokenData['expires_in']),
            ]);

            $this->info("Token atualizado para seller: {$account->seller_id}");
        } else {
            $this->error("Falha ao atualizar seller: {$account->seller_id}");
            Log::error('TikTok Token Refresh Error', $tokenData);
        }
    }
}
