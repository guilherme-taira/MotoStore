<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Cliente\InterfaceClienteController;
use App\Http\Controllers\MercadoPago\Pagamento\MercadoPagoCesta;
use App\Http\Controllers\MercadoPago\Pagamento\MercadoPagoItem;
use App\Http\Controllers\MercadoPago\Pagamento\MercadoPagoPreference;
use App\Http\Controllers\Yapay\ProdutoMercadoLivre;
use App\Models\financeiro;
use App\Models\kit;
use App\Models\order_site;
use App\Models\Products;
use App\Models\produtos_integrados;
use App\Models\SellerAccount;
use App\Models\TikTokProduct;
use App\Models\token;
use App\Models\User;
use App\Notifications\notificaSellerOrder;
use App\Notifications\notificaUserOrder;
use Carbon\Carbon;
use Facade\FlareClient\Http\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TikTokProductController extends Controller {

public function updateOriginalPrice(string $productId, string $skuId, $config): array
{
    try {
        // Buscar os produtos integrados com o product_id igual ao ID fornecido
        $produtoIntegrado = TikTokProduct::where('tiktok_product_id', $productId)->first();

        // Se o produto não for encontrado, lança uma exceção.
        if (!$produtoIntegrado) {
            throw new \Exception('Produto integrado não encontrado.');
        }

        $newPrice = Products::where('id', '=', $produtoIntegrado->local_product_id)->first();
        if (!$newPrice) {
            throw new \Exception('Produto local não encontrado.');
        }

        $valor = 0; // Valor final do produto
        $basePrice = $newPrice->priceWithFee; // Preço base do produto

        if ($produtoIntegrado->precofixo) {
            $valor = $produtoIntegrado->precofixo;
        } else {
            if ($produtoIntegrado->isPorcem == true || $produtoIntegrado->isPorcem == 1) {
                if (!empty($produtoIntegrado->acrescimo_porcentagem)) {
                    $valor = $basePrice + ($basePrice * ($produtoIntegrado->acrescimo_porcentagem / 100));
                } elseif (!empty($produtoIntegrado->desconto_porcentagem)) {
                    $valor = $basePrice - ($basePrice * ($produtoIntegrado->desconto_porcentagem / 100));
                }
            } else {
                if (!empty($produtoIntegrado->acrescimo_reais)) {
                    $valor = $basePrice + $produtoIntegrado->acrescimo_reais;
                } elseif (!empty($produtoIntegrado->desconto_reais)) {
                    $valor = $basePrice - $produtoIntegrado->desconto_reais;
                } else {
                    $valor = $basePrice;
                }
            }
        }

        $valor = max($valor, 0);

        $accessToken = $config['access_token'] ?? env('TIKTOK_ACCESS_TOKEN');
        $shopCipher = $config['shop_cipher'] ?? env('TIKTOK_SHOP_CIPHER');
        $shopId = $config['shop_id'] ?? env('TIKTOK_SHOP_ID');
        $appKey = config('services.tiktok.client_id');
        $appSecret = config('services.tiktok.client_secret');
        $version = '202309';
        $timestamp = now()->timestamp;
        $path = "/product/{$version}/products/{$productId}/prices/update";
        $url = "https://open-api.tiktokglobalshop.com" . $path;

        $payload = [
            'skus' => [
                [
                    'id' => $skuId,
                    'price' => [
                        'currency' => 'BRL',
                        'amount' => (string)$valor,
                    ],
                ],
            ],
        ];

        $qs = [
            'access_token' => $accessToken,
            'app_key' => $appKey,
            'shop_cipher' => $shopCipher,
            'shop_id' => $shopId,
            'timestamp' => $timestamp,
            'version' => $version,
        ];

        $sign = $this->generateSign($path, $qs, $appSecret, $payload);
        $qs['sign'] = $sign;

        // Tenta fazer a requisição à API do TikTok
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-tts-access-token' => $accessToken,
        ])->post($url . '?' . http_build_query($qs), $payload);

        $response->throw(); // Lança exceção para status 4xx ou 5xx

        return ['success' => true, 'message' => 'Preço atualizado com sucesso!', 'response' => $response->json()];

    } catch (\Illuminate\Http\Client\RequestException $e) {
        // Captura exceções da requisição HTTP (erros 4xx, 5xx)
        Log::error('Erro ao atualizar preço na API do TikTok: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erro ao comunicar com a API do TikTok.',
            'error_details' => $e->response->json(),
        ];
    } catch (\Exception $e) {
        // Captura qualquer outra exceção (produto não encontrado, etc.)
        Log::error('Erro inesperado na chamada da API: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erro inesperado: ' . $e->getMessage(),
        ];
    }
}



public function listarWarehousesTikTok()
{
    $seller = SellerAccount::where('user_id', Auth::user()->id)->first();

    if (!$seller || !$seller->access_token) {
        return ['message' => 'Nenhum token de acesso encontrado.'];
    }

    // Credenciais e configuração
    $accessToken = $seller->access_token;
    $appKey      = config('services.tiktok.client_id');
    $appSecret   = config('services.tiktok.client_secret');
    $shopId      = $seller->seller_id;
    $shopCipher  = $seller->shop_cipher; // útil p/ seu banco, mas NÃO vai na query deste endpoint
    $timestamp   = Carbon::now()->timestamp;
    $version     = '202309';

    // Host e endpoint
    $host   = 'open-api.tiktokglobalshop.com';
    $path   = "/logistics/{$version}/global_warehouses";
    $url    = "https://{$host}{$path}";

    // Query (sem shop_cipher)
    $qs = [
        'access_token' => $accessToken,
        'app_key'      => $appKey,
        'shop_id'      => $shopId,
        'timestamp'    => $timestamp,
        'version'      => $version,
    ];

    // GET → body vazio na assinatura
    $sign = $this->generateSign($path, $qs, $appSecret);

    $qs['sign'] = $sign;

    // Requisição
    $response = Http::withHeaders([
        'x-tts-access-token' => $accessToken,
        'Accept'             => 'application/json',
    ])->get($url . '?' . http_build_query($qs));

    if (!$response->successful()) {
        $json = $response->json();
        // Dica específica se alguém voltar a colocar shop_cipher na query
        if (($json['code'] ?? null) == 36009004) {
            $json['hint'] = "Remova 'shop_cipher' da query para este endpoint.";
        }
        return $json;
    }

    $json = $response->json();

    Log::alert([$json]);
    // Aceita múltiplos formatos de payload
    $list = $json['data']['global_warehouses']
        ?? $json['data']['warehouses']
        ?? $json['warehouses']
        ?? (is_array($json) && array_key_exists(0, $json) ? $json : []);

    // Normaliza saída
    $warehouses = collect($list)->map(function ($wh) {
        $id   = $wh['id'] ?? $wh['warehouse_id'] ?? null;
        $name = $wh['name'] ?? $wh['warehouse_name'] ?? ($id ?? 'Sem nome');
        $own  = $wh['ownership'] ?? null;

        // Tenta inferir status habilitado
        $statusRaw = $wh['status'] ?? $wh['warehouse_status'] ?? $wh['enable_status'] ?? $wh['enabled'] ?? null;
        $statusStr = is_bool($statusRaw) ? ($statusRaw ? 'ENABLED' : 'DISABLED') : strtoupper((string)$statusRaw);
        $enabled   = in_array($statusStr, ['ENABLED','ACTIVE','1','TRUE',''], true);

        return [
            'id'        => $id,
            'name'      => $name,
            'ownership' => $own,
            'enabled'   => $enabled,
            'raw'       => $wh, // útil para depuração/log
        ];
    })->filter(fn ($i) => !is_null($i['id']))->values()->all();

    return $warehouses;
}

   public function criarProdutoTikTok(array $payload)
    {
        // CÓDIGO CORRIGIDO
        $seller = SellerAccount::where('user_id', Auth::user()->id)->first();

        if (!$seller || !$seller->access_token) {
            return ['message' => 'Nenhum token de acesso encontrado.'];
        }

        // Credenciais e configuração
        $accessToken = $seller->access_token;
        $appKey      = config('services.tiktok.client_id');
        $appSecret   = config('services.tiktok.client_secret');
        $shopId      = $seller->seller_id;
        $shopCipher  = $seller->shop_cipher; // <-- Adicionado shop_cipher
        $timestamp   = Carbon::now()->timestamp;
        $version     = '202309';

        // Host e endpoint
        $host   = 'open-api.tiktokglobalshop.com';
        $path   = '/product/' . $version . '/products';
        $url    = "https://{$host}{$path}";

        // Monta os parâmetros de query
        $qs = [
            'access_token' => $accessToken,
            'app_key'      => $appKey,
            'shop_cipher'  => $shopCipher, // <-- Adicionado aos parâmetros de query
            'shop_id'      => $shopId,
            'timestamp'    => $timestamp,
            'version'      => $version,
        ];

        // Gera o sign. Para requisições POST, passamos o payload para a função.
        $sign = $this->generateSign($path, $qs, $appSecret, $payload);

        // Adiciona a assinatura aos parâmetros de query
        $qs['sign'] = $sign;

        // Faz a requisição POST com os parâmetros na URL e o payload no corpo
        $response = Http::withHeaders([
            'x-tts-access-token' => $accessToken,
            'Content-Type' => 'application/json',
        ])->post($url . '?' . http_build_query($qs), $payload);

        // Código dentro do seu método que lida com a resposta da API
        if ($response->successful()) {
            $json = $response->json();
            $productId = $json['data']['product_id'] ?? null;


            if ($productId) {
                // Obtenha os dados necessários para o updateOrCreate
                // Supondo que você tenha esses dados disponíveis neste contexto
                $localProductId = $payload['skus'][0]['seller_sku'];
                $tiktokSkus = $json['data']['skus'][0]['id'] ?? [];

                // Crie o array com os dados para salvar
                $dataToSave = [
                    'user_id' => Auth::user()->id,
                    'local_product_id' => $localProductId,
                    'tiktok_product_id' => $productId,
                    'shop_id' => $shopId,
                    'shop_cipher' => $shopCipher,
                    'title' => $payload['title'],
                    'category' => $payload['category_id'],
                    'price' => (float) $payload['skus'][0]['price']['amount'],
                    'raw_response' => json_encode($json['data']),
                    'warnings' => json_encode($json['data']['warnings'] ?? null),
                    'tiktok_sku' => $tiktokSkus, // <-- Adicionado o campo tiktok_sku
                ];

                // Use updateOrCreate para salvar os dados
                try {
                     TikTokProduct::create($dataToSave);
                } catch (\Throwable $e) {
                    // Se falhar ao salvar no DB, retorne o erro
                    return ['exception' => $e->getMessage()];
                }

                return ['product_id' => $productId];

            } else {
                $errorCode = $json['code'] ?? null;
                $errorMessage = $json['message'] ?? 'Ocorreu um erro desconhecido na API do TikTok.';

                if ($errorCode == 12052223) {
                    $errorMessage = 'Esta categoria é restrita. Para vender nela, solicite a qualificação através do Centro de Qualificação no Seller Center.';
                }

                return ['message' => $errorMessage, 'code' => $errorCode];
            }
        } else {
            return $response->json();
        }
    }


   private function generateSign(string $path, array $qs, string $appSecret, array $body = []): string {
        // Excluir 'access_token' e 'sign' dos parâmetros de assinatura
        unset($qs['access_token'], $qs['sign']);

        // Ordenar os parâmetros por chave em ordem alfabética
        ksort($qs);

        // Concatenar os parâmetros no formato {key}{value}
        $paramString = '';
        foreach ($qs as $k => $v) {
            $paramString .= "{$k}{$v}";
        }

        // Montar a string de assinatura: path + parâmetros concatenados
        $signString = $path . $paramString;

        // Para requisições POST, o corpo da requisição (payload) também é parte da assinatura
        if (!empty($body)) {
            $signString .= json_encode($body);
        }

        // Envolver a string de assinatura com o app_secret
        $wrappedSignString = $appSecret . $signString . $appSecret;

        // Gerar o HMAC-SHA256 da string envolvida
        $signature = hash_hmac('sha256', $wrappedSignString, $appSecret);

        return $signature;
    }

    public function getCategoriesTikTok(Request $request)
    {
        $seller = SellerAccount::first();
        if (!$seller || !$seller->access_token) {
            return response()->json(['message' => 'Nenhum token de acesso encontrado.'], 401);
        }

        // Credenciais e configuração
        $accessToken = $seller->access_token;
        $appKey      = config('services.tiktok.client_id');
        $appSecret   = config('services.tiktok.client_secret');
        $shopId      = $seller->seller_id;
        $shopCipher  = $seller->shop_cipher;
        $timestamp   = Carbon::now()->timestamp;
        $version     = '202309';

        // Host e endpoint
        $host   = 'open-api.tiktokglobalshop.com';
        $path   = '/product/202309/categories';
        $url    = "https://{$host}{$path}";

        // Monta os parâmetros de query
        $qs = [
            'access_token' => $accessToken,
            'app_key'      => $appKey,
            'shop_cipher'  => $shopCipher,
            'shop_id'      => $shopId,
            'timestamp'    => $timestamp,
            'version'      => $version,
        ];

        // Gera o sign usando a função
        $sign = $this->generateSign($path, $qs, $appSecret);

        // Faz a request com o token no header e a assinatura na query string
        $response = Http::withHeaders([
            'x-tts-access-token' => $accessToken,
        ])->get($url, array_merge($qs, ['sign' => $sign]));

        if ($response->successful()) {
            $json       = $response->json();
            $categories = $json['data']['categories'] ?? [];
            return response()->json(['categories' => $categories]);
        }

        return response()->json([
            'message'       => 'Falha ao buscar categorias do TikTok.',
            'error_details' => $response->json()
        ], $response->status());
    }

    public function getAuthorizedShops() {
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

        // Log::debug('TikTok Params', ['url' => $path, 'params' => $params]);

        $response = Http::withHeaders([
            'x-tts-access-token' => $accessToken,
            'content-type'       => 'application/json',
        ])->get('https://open-api.tiktokglobalshop.com' . $path, $params);

        if ($response->successful()) {
            $body = $response->json();
            // Log::info('Resposta TikTok - Lojas autorizadas', ['body' => $body]);

            // Verifica e salva o cipher da loja
            $cipher = $body['data']['shops'][0]['cipher'] ?? null;
            $shopId = $body['data']['shops'][0]['id'] ?? null;

            if ($cipher) {
                SellerAccount::where('user_id', Auth::id())->update([
                    'shop_cipher' => $cipher,
                    'shop_id' => $shopId
                ]);
            }

            // Redirecionar para a home após sucesso
            return redirect()->to('/home?status=2')->with('success', 'Conta TikTok autorizada com sucesso!');
        }

        Log::error('Erro TikTok – authorization shops', ['body' => $response->body()]);
        return response()->json([
            'status'  => 'error',
            'message' => 'Erro ao consultar lojas autorizadas',
            'details' => $response->json(),
        ], $response->status());
    }


public function updateInventory(string $tiktokProductId,string $tiktokSkuId, int $newStock, SellerAccount $seller, string $warehouseId) {
    $appKey = config('services.tiktok.client_id'); // App Key oficial da sua aplicação TikTok
    $appSecret = config('services.tiktok.client_secret'); // App Secret oficial
    $shopCipher = $seller['shop_cipher'] ?? env('TIKTOK_SHOP_CIPHER');

    // Timestamp atual (em segundos)
    $timestamp = now()->timestamp;

    // Path exato do endpoint
    $path = "/product/202309/products/{$tiktokProductId}/inventory/update";
    $baseUri = "https://open-api.tiktokglobalshop.com" . $path;

    // Corpo da requisição
    $body = [
        'skus' => [
            [
                'id' => $tiktokSkuId,
                'inventory' => [
                    [
                        'warehouse_id' => $warehouseId,
                        'quantity' => $newStock
                    ]
                ]
            ]
        ]
    ];

    // Parâmetros que vão na query (sem sign ainda)
    $queryParams = [
        'app_key' => $appKey,
        'shop_cipher' => $shopCipher,
        'timestamp' => $timestamp,
    ];

    // Gerar assinatura (sua função precisa seguir a doc oficial)
    $sign = $this->generateSign($path, $queryParams, $appSecret, $body);

    // Adiciona o sign na query
    $queryParams['sign'] = $sign;

    // Monta a URL final com query string
    $finalUrl = $baseUri . '?' . http_build_query($queryParams);

    // Envia requisição
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'x-tts-access-token' => $seller->access_token,
    ])->post($finalUrl, $body);

    if ($response->successful()) {
        $result = $response->json();
        log::alert($response->json());
        if ($result['code'] === 0) {
            if($newStock == 0){

                 return ['success' => true, 'message' => 'Produto inativado com sucesso!', 'response' => $response->json()];
            }
                 return ['success' => true, 'message' => 'Estoque atualizado com sucesso!', 'response' => $response->json()];
            return true;
        } else {
            Log::error("Erro na API do TikTok ao atualizar estoque para #{$tiktokProductId}: " . $result['message'], $result);
            return false;
        }
    } else {
        Log::error("Falha na requisição HTTP para a API do TikTok: Status " . $response->status() . " - Body: " . $response->body());
        return false;
    }
}

public function getOrderDetails(string $orderId, SellerAccount $seller) {
    // Dados dinâmicos e de configuração
    $appKey = config('services.tiktok.client_id');
    $appSecret = config('services.tiktok.client_secret');
    $shopCipher = $seller['shop_cipher'] ?? env('TIKTOK_SHOP_CIPHER');

    // Timestamp da requisição
    $timestamp = now()->timestamp;

    // Constrói o URL e o path da API
    $path = "/order/202507/orders";
    $baseUri = "https://open-api.tiktokglobalshop.com" . $path;


    // Parâmetros da query para gerar a assinatura
    $queryParamsForSign = [
        'app_key' => $appKey,
        'shop_cipher' => $shopCipher,
        'timestamp' => $timestamp,
        'ids' => $orderId,
    ];

    // A função de assinatura deve ser chamada com um corpo de requisição vazio
    // para requisições GET.
    $sign = $this->generateSign($path, $queryParamsForSign, $appSecret, []);

    // Adiciona o 'sign' e o 'access_token' aos parâmetros da query que serão enviados
    $queryParams = $queryParamsForSign;
    $queryParams['sign'] = $sign;
    $queryParams['access_token'] = $seller->access_token;

    // Realiza a requisição HTTP com o Laravel Http
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'x-tts-access-token' => $seller->access_token,
    ])->get($baseUri, $queryParams); // Usamos o método 'get'

    if ($response->successful()) {
        $result = $response->json();
        if ($result['code'] === 0) {
        // IMPLEMENTACAO DO CARRINHO CESTA PARA PRODUTOS
        $carrinhoCesta = new MercadoPagoCesta();

       // Crie um array para consolidar os itens com a mesma SKU
        $consolidatedItems = [];
        $package_id = "";
        foreach ($result['data']['orders'] as $order) {
            foreach ($order['line_items'] as $item) {
                $sellerSku = $item['seller_sku'];
                $package_id = $item['package_id'];
                // Se o SKU já existe no array consolidado, apenas incremente a quantidade
                if (isset($consolidatedItems[$sellerSku])) {
                    $consolidatedItems[$sellerSku]['quantity']++;
                } else {
                    // Se for a primeira vez que vemos este SKU, adicione-o ao array
                    $consolidatedItems[$sellerSku] = [
                        'seller_sku' => $sellerSku,
                        'product_name' => $item['product_name'],
                        'quantity' => 1,
                        'sku_image' => $item['sku_image']
                    ];
                }
            }
        }

        // Agora, itere sobre os itens consolidados e adicione-os ao carrinhoCesta
        $carrinhoCesta = new MercadoPagoCesta();

        foreach ($consolidatedItems as $key => $consolidatedItem) {
            $produto = Products::where('id', $consolidatedItem['seller_sku'])->first();

            if (isset($produto)) {
                $unitPrice = $produto->isKit ? $produto->priceKit : $produto->priceWithFee;

                $item = new MercadoPagoItem(
                    $consolidatedItem['seller_sku'],
                    $consolidatedItem['product_name'],
                    $consolidatedItem['quantity'], // Quantidade consolidada
                    "BRL",
                    $unitPrice
                );

                $carrinhoCesta->addProdutos($item);
                $consolidatedItems[$produto->id]['price'] = $unitPrice * $consolidatedItem['quantity'];


            }
        }

        //     /***
        //      * IMPLEMENTAÇÃO DO SELLER ID PARA PEGAR OS DADOS PARA GERAR O PIX NA CONTA
        //      * DADOS ESSES COMO ENDEREÇO COMPLETO E DADOS PESSOAIS COMO NOME, CPF OU CNPJ
        //      */

            $fornecedor = User::GetDataUserFornecedor($produto['fornecedor_id']); // Certifique-se de que este ID é o do usuário correto
            $vendedor = User::GetDataUserFornecedor($seller->user_id); // Certifique-se de que este ID é o do usuário correto

            if (order_site::VerificarVenda($orderId) == false) {

            if(isset($produto)){

                $prefence = new MercadoPagoPreference($carrinhoCesta,'https://afilidrop.com.br/api/v1/notification',$fornecedor->user_id_mercadolivre,$orderId);
                $preference = $prefence->resource();
                $orders = $result['data']['orders'];
               // Verifique se a variável não está vazia e é um array
                if (!empty($orders) && is_array($orders)) {
                    // O primeiro item do array de pedidos é o pedido que você quer
                    $firstOrder = $orders[0];

                    // Verifique se a chave 'payment' existe nesse pedido
                    if (isset($firstOrder['payment'])) {
                        $paymentData = $firstOrder['payment'];
                        $cliente = new InterfaceClienteController($vendedor->user_id_mercadolivre,"-",$preference['external_reference'],$preference['init_point'],$preference['id'],$paymentData['original_shipping_fee'],$firstOrder['delivery_option_id']);
                        $id_order = $cliente->saveClientTikTok($firstOrder,$vendedor->user_id_mercadolivre,$consolidatedItems);
                    }
                }
            }
        }

        financeiro::SavePayment(3, $paymentData['original_total_product_price'], $id_order, $produto->fornecedor_id, $preference['init_point'], "S/N","aguardando pagamento",$preference['external_reference'],$package_id);
        // // NOTIFICA O FORNECEDOR
        // Notification::send($fornecedor, new notificaUserOrder($fornecedor, $json, $produto, $id_order, $json->id));
        // // // NOTIFICA O VENDEDOR
        // Notification::send($vendedor, new notificaSellerOrder($vendedor, $json, $produto, $id_order, $json->id,$preference['init_point'],$image));
        }

    }
    }
}
