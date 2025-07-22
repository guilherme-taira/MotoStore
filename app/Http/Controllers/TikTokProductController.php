<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SellerAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TikTokProductController extends Controller
{

public function uploadProduct()
{
    $accessToken = 'ROW_P1LRlgAAAAB05cqzgiz6_CX_TzaV9SCbjveZpTHTTFylDeqT3wDkgeIGmVBsqnLhfihOszdk0HCWphyZeCHKM6_7l036JDREvqUjlSQfs06F9WFlanP1djatU09gD0GgZspgniMpi7w'; // Salve isso no banco ao autorizar, pegue da tabela SellerAccount
    $shopId = 'SANDBOX7525656383736268545'; // Ou seller_id conforme retorno da autorização

    $productData = [
        'product_name' => 'Tênis Esportivo Teste',
        'description' => 'Tênis confortável para esportes e uso diário.',
        'category_id' => 100000, // ID de categoria válida
        'brand' => [
            'brand_id' => 0,
            'brand_name' => 'Sem Marca'
        ],
        'images' => [
            'https://example.com/imagem1.jpg',
            'https://example.com/imagem2.jpg'
        ],
        'skus' => [
            [
                'sku_spec' => [
                    [
                        'spec_name' => 'Cor',
                        'spec_value' => 'Azul'
                    ],
                    [
                        'spec_name' => 'Tamanho',
                        'spec_value' => '42'
                    ]
                ],
                'price' => 15000, // R$150,00 em centavos
                'quantity' => 10,
                'seller_sku' => 'TENIS-AZUL-42',
                'package_length' => 30,
                'package_width' => 20,
                'package_height' => 10,
                'package_weight' => 1000 // em gramas
            ]
        ],
        'delivery_service_rule' => [
            'delivery_option' => 0
        ]
    ];

    $response = Http::withHeaders([
        'Access-Token' => $accessToken,
        'Content-Type' => 'application/json'
    ])->post('https://sandbox-apis.tiktokglobalshop.com/api/products/202309/upload', [
        'shop_id' => $shopId,
        'product' => $productData
    ]);

    Log::alert($response->json());

    if ($response->successful()) {
        return response()->json(['status' => 'success', 'data' => $response->json()]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Erro ao subir produto',
        'details' => $response->json()
    ], $response->status());
}

private function generateSign(array $qs, string $path, string $appSecret): string
{
    unset($qs['sign'], $qs['access_token']);
    ksort($qs);

    $buffer = '';
    foreach ($qs as $key => $val) {
        $buffer .= "$key$val";
    }

    $signString = $appSecret . $path . $buffer . $appSecret;

    Log::debug("TikTok Signing String", ['signString' => $signString]);

    return hash_hmac('sha256', $signString, $appSecret);
}

public function getOrderDetails()
{
    $seller = SellerAccount::first();
    $appKey = config('services.tiktok.client_id');
    $appSecret = config('services.tiktok.client_secret');
    $accessToken = $seller->access_token;
    $shopCipher = "ROW_4NlB_AAAAAAkLHOThcURO5Q8CAbZrLpo"; // fallback
    $orderIds = '579713172606583968';
    $path = '/order/202309/orders';

    $timestamp = time(); // Salve em variável única!

    $qs = [
        'app_key'     => $appKey,
        'ids'         => $orderIds,
        'shop_cipher' => $shopCipher,
        'timestamp'   => $timestamp,
    ];

    // Importante: gerar `sign` antes de alterar `qs`
    $sign = $this->generateSign($qs, $path, $appSecret);
    $qs['sign'] = $sign;

    $response = Http::withHeaders([
        'x-tts-access-token' => $accessToken,
        'Content-Type'       => 'application/json',
    ])->get('https://open-api.tiktokglobalshop.com' . $path, $qs);

    if ($response->successful()) {
        Log::info('Resposta TikTok', [
            'body' => $response->json(),
        ]);
        return response()->json($response->json());
    }

    Log::error('Erro TikTok – get orders', [
        'url' => $path,
        'params' => $qs,
        'body' => $response->body()
    ]);

    return response()->json([
        'status'  => 'error',
        'message' => 'Erro ao buscar pedidos',
        'details' => $response->json(),
    ], $response->status());
}

public function getAuthorizedShops()
{
    $appKey = config('services.tiktok.client_id');
    $appSecret = config('services.tiktok.client_secret');
    $accessToken = SellerAccount::where('user_id', Auth::id())->first()?->access_token ?? null;

    if (!$accessToken) {
        return response()->json(['error' => 'Token de acesso não encontrado.']);
    }

    $path = '/authorization/202309/shops';
    $timestamp = time();

    $params = [
        'app_key'   => $appKey,
        'timestamp' => $timestamp,
    ];

    // Gerar assinatura
    $sign = $this->generateSign($params, $path, $appSecret);
    $params['sign'] = $sign;

    Log::debug('TikTok Params', ['url' => $path, 'params' => $params]);

    $response = Http::withHeaders([
        'x-tts-access-token' => $accessToken,
        'content-type'       => 'application/json',
    ])->get('https://open-api.tiktokglobalshop.com' . $path, $params);

    if ($response->successful()) {
        $body = $response->json();
        Log::info('Resposta TikTok - Lojas autorizadas', ['body' => $body]);

        // Verifica e salva o cipher da loja
        $cipher = $body['data']['shops'][0]['cipher'] ?? null;

        if ($cipher) {
            SellerAccount::where('user_id', Auth::id())->update([
                'shop_cipher' => $cipher
            ]);
        }

        return response()->json($body);
    }

    Log::error('Erro TikTok – authorization shops', ['body' => $response->body()]);
    return response()->json([
        'status'  => 'error',
        'message' => 'Erro ao consultar lojas autorizadas',
        'details' => $response->json(),
    ], $response->status());
}


}
