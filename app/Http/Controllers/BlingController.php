<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IntegracaoBling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlingController extends Controller
{
    public function authenticate(Request $request)
    {
        // Verifique se o código foi recebido na query string
        $code = $request->query('code');

        if (!$code) {
            return redirect()->route('bling.index')->with('error', 'Código de autorização não encontrado.');
        }

        // Buscar as credenciais no banco de dados
        $integracao = IntegracaoBling::where('user_id', Auth::id())->first();

        if (!$integracao) {
            return redirect()->route('bling.index')->with('error', 'Configuração de integração não encontrada para o usuário.');
        }

        $clientId = $integracao->client_id;
        $clientSecret = $integracao->client_secret;
        $redirectUri = $integracao->link; // Supondo que o campo `link` seja a URL de redirecionamento

        // Credenciais em Base64 para o header Authorization
        $credentials = base64_encode("$clientId:$clientSecret");

        // Fazer a requisição POST ao Bling
        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => "Basic $credentials",
                'Accept' => 'application/json',
            ])
            ->post('https://www.bling.com.br/Api/v3/oauth/token', [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirectUri,
            ]);

        // Verificar a resposta
        if ($response->successful()) {
            $data = $response->json();

            // Calcular a data de expiração
            $expiresAt = now()->addSeconds($data['expires_in']);

            // Atualizar os campos no banco de dados
            $integracao->update([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? null,
                'expires_at' => $expiresAt,
                'isIntegrado' => true
            ]);

            return redirect()->route('bling.index')->with('success', 'Autenticação realizada com sucesso!');
        } else {
            return redirect()->route('bling.index')->with('error', 'Erro ao autenticar com o Bling: ' . $response->body());
        }
    }
}
