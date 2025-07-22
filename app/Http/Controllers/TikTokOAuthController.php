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

    public function callback(Request $request)
{
    $code = $request->get('code');
    $clientId = config('services.tiktok.client_id');
    $clientSecret = config('services.tiktok.client_secret');
    $redirectUri = config('services.tiktok.redirect_uri');

    // Verifica se o código foi recebido corretamente
    if (!$code) {
        \Log::error('Código de autorização ausente', ['request' => $request->all()]);
        return response()->json([
            'status' => 'error',
            'message' => 'Código de autorização não encontrado na URL.',
            'details' => $request->all()
        ], 400);
    }

    // Requisição para trocar o code por token (usando endpoint v2 correto)
    $response = Http::asForm()->post('https://auth.tiktok-shops.com/api/v2/token', [
        'app_key'       => $clientId,           // novo nome do parâmetro
        'app_secret'    => $clientSecret,       // novo nome do parâmetro
        'grant_type'    => 'authorized_code',   // NOTA: grant_type mudou de 'authorization_code' para 'authorized_code'
        'auth_code'     => $code,
        'redirect_uri'  => $redirectUri,
    ]);

    // Log de debug
    \Log::info('Resposta TikTok token', ['body' => $response->body()]);

    // Caso erro HTTP (ex: 400, 500)
    if ($response->failed()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Erro ao tentar obter o token',
            'details' => $response->json(),
        ], 400);
    }

    $tokenData = $response->json();

    // Validação do token e open_id
    if (isset($tokenData['data']['access_token'], $tokenData['data']['seller_id'])) {
        // Salva ou atualiza o vendedor na tabela local
        SellerAccount::updateOrCreate(
            ['seller_id' => $tokenData['data']['seller_id']],
            [
                'access_token'  => $tokenData['data']['access_token'],
                'refresh_token' => $tokenData['data']['refresh_token'] ?? null,
                'expires_in'    => now()->addSeconds($tokenData['data']['expire_in'] ?? 3600),
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Conta TikTok autorizada com sucesso!',
        ]);
    }

    // Caso o token não tenha sido recebido
    return response()->json([
        'status' => 'error',
        'message' => 'Token ou seller_id não encontrado na resposta.',
        'details' => $tokenData,
    ], 400);
}
}
