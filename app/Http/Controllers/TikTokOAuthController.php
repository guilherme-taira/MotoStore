<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SellerAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TikTokOAuthController extends Controller
{
public function redirect()
    {
        $clientId = config('services.tiktok.client_id');
        $redirectUri = config('services.tiktok.redirect_uri');
        $scope = 'product.order'; // novo formato
        $ttsState = csrf_token();

        $url = 'https://auth.tiktok-shops.com/oauth/authorize/seller?' . http_build_query([
            'app_key' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $scope,
            'tts_state' => $ttsState
        ]);

        return redirect()->away($url);
    }

    public function callback(Request $request) {
    $code = $request->get('code');
    $clientId = config('services.tiktok.client_id');
    $clientSecret = config('services.tiktok.client_secret');
    $redirectUri = config('services.tiktok.redirect_uri');

    if (!$code) {
        return response()->json([
            'status' => 'error',
            'message' => 'Código ausente na URL',
            'details' => $request->all()
        ], 400);
    }

    $response = Http::asForm()->post('https://auth.tiktok-shops.com/api/v2/token', [
        'app_key'      => $clientId,
        'app_secret'   => $clientSecret,
        'grant_type'   => 'authorized_code', // correto
        'auth_code'    => $code,
        'redirect_uri' => $redirectUri,
    ]);

    $json = $response->json();

    if ($response->failed() || !isset($json['data']['access_token'], $json['data']['seller_id'])) {
        Log::error('Falha ao obter token da TikTok', [
            'response_body' => $response->body(),
            'code' => $code
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Token ou seller_id não encontrado na resposta.',
            'details' => $json,
        ], 400);
    }

    // ✅ Salva na base de dados
    SellerAccount::updateOrCreate(
        ['seller_id' => $json['data']['seller_id']],
        [
            'access_token'  => $json['data']['access_token'],
            'refresh_token' => $json['data']['refresh_token'] ?? null,
            'expires_in'    => now()->addSeconds($json['data']['expire_in'] ?? 3600),
        ]
    );

    return response()->json([
        'status' => 'success',
        'message' => 'Conta autorizada com sucesso!',
        'seller_id' => $json['data']['seller_id']
    ]);
}

}
