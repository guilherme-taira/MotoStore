<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SellerAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (!$code) {
            return response()->json([
                'status' => 'error',
                'message' => 'Código de autorização não encontrado.',
            ], 400);
        }

        $clientId     = config('services.tiktok.client_id');
        $clientSecret = config('services.tiktok.client_secret');

        $url = 'https://auth.tiktok-shops.com/api/v2/token/get';
        $params = [
            'app_key'    => $clientId,
            'app_secret' => $clientSecret,
            'grant_type' => 'authorized_code',
            'auth_code'  => $code,
        ];

        $response = Http::get($url, $params);
        $body = $response->json();

        Log::info('Resposta TikTok token', ['body' => $body]);

        if ($response->failed() || !isset($body['data']['access_token'], $body['data']['seller_name'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token ou seller_id não encontrado na resposta.',
                'details' => $body,
            ], 400);
        }

        $data = $body['data'];

        // Salvar na tabela SellerAccount
        SellerAccount::updateOrCreate(
            ['seller_id' => $data['seller_name']],
            [
                'user_id' => Auth::user()->id,
                'access_token'  => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? null,
                'expires_in'    => now()->addSeconds($data['access_token_expire_in'] - time()),
            ]
        );

        // Redirecionar para a home após sucesso
        return redirect()->to('/home?status=1')->with('success', 'Conta TikTok autorizada com sucesso!');
    }


}
