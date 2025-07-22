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


public function callback(Request $request)
{
    $code = $request->get('code');

    if (!$code) {
        return response()->json([
            'status' => 'error',
            'message' => 'Código de autorização não encontrado.',
        ], 400);
    }

    $clientId     = config('services.tiktok.client_id');
    $clientSecret = config('services.tiktok.client_secret');
    $redirectUri  = config('services.tiktok.redirect_uri');

    $response = Http::asForm()->post('https://auth.tiktok-shops.com/api/v2/token', [
        'app_key'      => $clientId,
        'app_secret'   => $clientSecret,
        'grant_type'   => 'authorized_code',
        'auth_code'    => $code,
        'redirect_uri' => $redirectUri,
    ]);

    $body = $response->json();

    Log::info('Resposta TikTok token', ['body' => $body]);

    if ($response->failed() || !isset($body['data']['access_token'], $body['data']['seller_id'])) {
        return response()->json([
            'status' => 'error',
            'message' => 'Token ou seller_id não encontrado na resposta.',
            'details' => $body,
        ], 400);
    }

    $data = $body['data'];

    // Aqui você salva o token em sua tabela SellerAccount
    SellerAccount::updateOrCreate(
        ['seller_id' => $data['seller_id']],
        [
            'access_token'  => $data['access_token'],
            'refresh_token' => $data['refresh_token'] ?? null,
            'expires_in'    => now()->addSeconds($data['expires_in'] ?? 0),
        ]
    );

    return response()->json([
        'status' => 'success',
        'message' => 'Conta autorizada com sucesso!',
        'seller_id' => $data['seller_id'],
    ]);
}


}
