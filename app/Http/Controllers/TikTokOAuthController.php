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
        $scope = 'order product';
        $ttsState = csrf_token(); // Ou qualquer outro token de sessão

        $url = "https://auth.tiktok-shops.com/oauth/authorize/seller?" . http_build_query([
            'app_key' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $scope,
            'tts_state' => $ttsState, // OBRIGATÓRIO!
        ]);

        return redirect()->away($url);
    }

   public function callback(Request $request) {
    $code = $request->get('code');
    $clientId = config('services.tiktok.client_id');
    $clientSecret = config('services.tiktok.client_secret');
    $redirectUri = config('services.tiktok.redirect_uri');

    // Verifica se o código foi recebido
    if (!$code) {
        return response()->json([
            'status' => 'error',
            'message' => 'Código de autorização não encontrado na requisição.',
            'details' => $request->all(),
        ], 400);
    }

    // Realiza a requisição para trocar o código por um token
    $response = Http::asForm()->post('https://sandbox-apis.tiktok-shops.com/oauth/token', [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'grant_type' => 'authorization_code',
        'redirect_uri' => $redirectUri,
        'code' => $code,
    ]);

    // Para fins de depuração: registra a resposta no log
    Log::info('TikTok token response', ['body' => $response->body()]);

    // Verifica se houve falha na requisição
    if ($response->failed()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Falha na autorização (resposta HTTP inválida)',
            'details' => $response->json(),
        ], 400);
    }

    $tokenData = $response->json();

    // Verifica se a resposta contém access_token
    if (isset($tokenData['access_token'])) {
        SellerAccount::updateOrCreate(
            ['seller_id' => $tokenData['open_id']],
            [
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'],
                'expires_in' => now()->addSeconds($tokenData['expires_in']),
            ]
        );

        return response()->json(['status' => 'success', 'message' => 'Conta autorizada com sucesso!']);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Token não encontrado na resposta.',
        'details' => $tokenData,
    ], 400);
}
}
