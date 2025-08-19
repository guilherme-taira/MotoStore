<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Events\EventoAfiliado;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TikTokProductController;
use App\Models\images;
use App\Models\Products;
use App\Models\SellerAccount;
use App\Models\token;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpParser\Parser\Tokens;

class ProdutoImplementacao extends criadorDeProduto
{
    public function getProduto()
    {
        $products = Products::where('id', $this->getIdProduct())->first();

        $token = token::where('user_id', $this->getId())->first(); // CHAMANDO ATUALIZADO
        $produto = new ProdutoConcreto($products, $this->getIdCategoria(), $this->getPrice(), $token,$this->getName(),$this->getTipoAnuncio(),$this->getDados(),$this->getValorSemTaxa(),$this->getTotalInformado(),$this->getDadosIntegrado(),$products->atributos_json,$this->getVariation());
        $data = $produto->integrar($this->getDescricao(),$this->getIdProduct());
        Log::alert($this->getId());
        if ($data) {
            return ['error_data' => $data];
        } else {
            session('msg_success', 'Produto Cadastrado Com Sucesso!');
            EventoAfiliado::dispatch($products);
            return "";
        }
    }


public function integrarProdutoComStream(): ?array
{
    // 1. Início
    $this->enviarProgresso(0, 'Iniciando a integração do produto...', 'info');

    // 1.5. VERIFICAÇÃO: Verifica se a conta de vendedor está integrada
    $seller = SellerAccount::where('user_id', Auth::user()->id)->first();
    if (!$seller) {
        $this->enviarProgresso(100, 'Erro: Conta de vendedor não integrada.', 'error', ['details' => 'Por favor, integre sua conta de vendedor do TikTok primeiro.']);
        return ["Conta de vendedor não encontrada."];
    }

    // 2. Busca produto local
    $products = Products::where('id', $this->getIdProduct())->first();
    if (!$products) {
        $this->enviarProgresso(100, 'Erro: Produto não encontrado.', 'error', ['details' => 'O produto com o ID especificado não foi encontrado no banco de dados.']);
        return ["Produto não encontrado para integração."];
    }

    // 3. Processa e faz o upload das imagens para o TikTok
    $fotos = Images::where('product_id', $products->id)
        ->orderBy('position', 'asc')
        ->get();
    $totalFotos = count($fotos);
    $mainImages = [];
    $contador = 0;


    foreach ($fotos as $foto) {
        $contador++;
        $progresso = round(($contador / max(1, $totalFotos)) * 50);
        $this->enviarProgresso($progresso, "Fazendo upload da imagem {$contador} de {$totalFotos} para o TikTok...", 'info');

        // Normaliza e monta URL
        $encodedFilename = rawurlencode(basename($foto->url));
        if (filter_var($foto->url, FILTER_VALIDATE_URL)) {
            $s3Url = $foto->url;
        } else {
            $baseUrl = "https://afilidrop2.s3.us-east-1.amazonaws.com/produtos/{$foto->product_id}/";
            $s3Url = $baseUrl . $encodedFilename;
        }

        // Chama a nova função para fazer o upload da imagem e obter o URI
        $tiktokUri = $this->uploadImageToTikTok($s3Url);

        if ($tiktokUri) {
            $mainImages[] = ['uri' => $tiktokUri];
        } else {
            $this->enviarProgresso(100, "Erro ao fazer upload da imagem {$contador}.", 'error', ['details' => 'Não foi possível obter o URI da imagem.']);
            return ['Erro ao processar imagem.'];
        }
    }

    // 4. Prepara payload para TikTok
    $this->enviarProgresso(55, 'Preparando os dados para a API do TikTok...', 'info');

    $payload = [
        'save_mode' => 'LISTING',
        'description' => $products->description,
        'category_id' => $this->getIdCategoria(),
        'brand_id' => '7531007243749033745',
        'title' => $products->title,
        'minimum_order_quantity' => 1,
        'package_weight' => ['value' => $products->weight == 0 ? '200' : (string) $products->weight, 'unit' => 'GRAM'],
        // CORREÇÃO AQUI: usar a chave 'main_images' com o array de URIs
        'main_images' => $mainImages,
        'skus' => [[
            'sales_attributes' => [],
            'inventory' => [['warehouse_id' => $this->getWarehouse(), 'quantity' => $products->estoque_afiliado]],
            'seller_sku' => (string) $products->id,
            'price' => ['amount' => (string) $this->getPrice(), 'currency' => 'BRL'],
            'combined_skus' => [],
            'external_urls' => [],
            'extra_identifier_codes' => [],
            'external_list_prices' => []
        ]],
        'package_dimensions' => [
            'length' => $products->length,
            'width' => $products->width,
            'height' => $products->height,
            'unit' => 'CENTIMETER'
        ],
    ];

    Log::alert(json_encode($payload));

    // 5. Envia para TikTok
    $this->enviarProgresso(60, 'Enviando produto para o TikTok...', 'info');


    try {
        // Renomeado para $apiResponse para ser mais claro
        $apiResponse = (new TikTokProductController('1', '2'))->criarProdutoTikTok($payload);
    } catch (\Throwable $e) {
        $this->enviarProgresso(100, 'Falha na integração com o TikTok (exceção).', 'error', ['details' => $e->getMessage()]);
        return ['exception' => $e->getMessage()];
    }

    // 6. Tratamento da resposta da API
    // CORREÇÃO AQUI: Verifica se o array contém a chave 'product_id' para determinar o sucesso.
    if (isset($apiResponse['product_id'])) {
        $this->enviarProgresso(100, 'Produto integrado com sucesso!', 'success');
        return null;
    } else {
        $innerError = $apiResponse[0] ?? $apiResponse;
        if (isset($innerError['raw']) && is_array($innerError['raw'])) {
            $innerError = $innerError['raw'];
        }

        if (isset($innerError['code']) && $innerError['code'] == 12052223) {
            $this->enviarProgresso(100, 'Falha na integração: Categoria restrita. Veja os detalhes.', 'error', ['details' => $innerError]);
        } else {
            $detailsPayload = isset($apiResponse['message']) ? $apiResponse['message'] : $innerError;
            $this->enviarProgresso(100, 'Falha na integração com o TikTok.', 'error', ['details' => $detailsPayload]);
        }
        return $apiResponse;
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

// Adicione esta função na sua classe TikTokProductController
public function uploadImageToTikTok(string $imageUrl): ?string
{
    // CÓDIGO CORRIGIDO
    $seller = SellerAccount::where('user_id', Auth::user()->id)->first();
    if (!$seller || !$seller->access_token) {
        return null;
    }

    // Credenciais e configuração
    $accessToken = $seller->access_token;
    $appKey      = config('services.tiktok.client_id');
    $appSecret   = config('services.tiktok.client_secret');
    $shopId      = $seller->seller_id;
    $timestamp   = Carbon::now()->timestamp;
    $version     = '202309';

    // Host e endpoint
    $host = 'open-api.tiktokglobalshop.com';
    $path = '/product/' . $version . '/images/upload';
    $url  = "https://{$host}{$path}";

    // Parâmetros para a query string
    $qs = [
        'access_token' => $accessToken,
        'app_key'      => $appKey,
        'shop_id'      => $shopId,
        'timestamp'    => $timestamp,
        'version'      => $version,
    ];

    // Gera o sign (POST request sem corpo JSON)
    $sign = $this->generateSign($path, $qs, $appSecret);
    $qs['sign'] = $sign;

    // Constrói o corpo da requisição com o arquivo
    $fileContents = Http::get($imageUrl)->body();
    $imageName = basename($imageUrl);

    $response = Http::asMultipart()
        ->withHeaders([
            'x-tts-access-token' => $accessToken,
        ])
        ->attach('data', $fileContents, $imageName)
        ->post($url . '?' . http_build_query($qs));

    if ($response->successful()) {
        $json = $response->json();
        return $json['data']['uri'] ?? null;
    }

    return null;
}
    // Na sua classe ProdutoImplementacao.php
   private function enviarProgresso(int $progresso, string $mensagem, string $status = 'info', array $extra = [])
    {
        $payload = [
            'status' => $status,           // ex: info, success, error
            'message' => $mensagem,
            'progress' => $progresso,
        ];

        // junta dados extras como 'details' ou qualquer outro
        if (!empty($extra)) {
            $payload = array_merge($payload, $extra);
        }

        echo "data: " . json_encode($payload, JSON_UNESCAPED_UNICODE) . "\n\n";
        flush(); // aqui é ok porque StreamedResponse faz o resto
    }

      public function getProdutoTiktok() {
        // Supondo que $this->getIdProduct() e outras propriedades ($products, $fotos)
        // já estão disponíveis ou são injetadas/obtidas aqui.
        $products = Products::where('id', $this->getIdProduct())->first();

        if (!$products) {
            return ["Produto não encontrado para integração."];
        }

        $fotos = Images::where('product_id', $products->id)->OrderBy('position', 'asc')->get();
        $photos = [];
        foreach ($fotos as $foto) {
            // Garante que o nome do arquivo na URL esteja codificado corretamente
            $encodedFilename = rawurlencode($foto->url);
            // Reconstrua a URL completa com o nome do arquivo codificado
            $baseUrl = "https://afilidrop2.s3.us-east-1.amazonaws.com/produtos/{$foto->product_id}/";
            array_push($photos, $baseUrl . $encodedFilename);
        }

        $payload = [
            'save_mode'              => 'LISTING',
            'description'            => $products->description,
            'category_id'            => $this->getIdCategoria(), // Confirme se este é o ID de categoria correto para TikTok
            'brand_id'               => '7531007243749033745', // Confirme se este é o ID de marca correto para TikTok
            'title'                  => $products->title,
            'minimum_order_quantity' => 1,
            'package_weight'         => ['value' => '100', 'unit' => 'KILOGRAM'],
            'imagens'                => $photos, // 'imagens' é a chave temporária que será processada
            'skus'                   => [[
                'sales_attributes'       => [],
                'inventory'              => [['warehouse_id' => '7528080962442594054',"quantity" => $products->estoque_afiliado]],
                'seller_sku'             => (string) $products->id, // Cast para string
                'price'                  => ['amount' => (string) $this->getPrice(), 'currency' => 'BRL'], // Cast para string
                'combined_skus'          => [],
                'external_urls'          => [],
                'extra_identifier_codes' => [],
                'external_list_prices'   => []
            ]],
            'package_dimensions' => [
                'length' => (string) $products->length, // Cast para string
                'width'  => (string) $products->width,  // Cast para string
                'height' => (string) $products->height, // Cast para string
                'unit'   => 'CENTIMETER'
            ],
        ];
        $errorsFromTikTok = new TikTokProductController('1','2');
        // Chama a função criarProdutoTikTok e retorna seu resultado
        // Se criarProdutoTikTok for um método desta mesma classe (ProdutoImplementacao), use $this->
        // Se for um método de um serviço injetado, use $this->seuServicoTikTok->criarProdutoTikTok($payload);
        return $errorsFromTikTok->criarProdutoTikTok($payload);// Retorna null para sucesso, ou array de erros
    }

    public function getProdutoByApi()
    {

        $products = Products::where('id', $this->getIdProduct())->first();
        $token = token::where('user_id', $this->getId())->first(); // CHAMANDO ANTIGO
        $produto = new ProdutoConcreto($products, $this->getIdCategoria(), $this->getPrice(), $token,$this->getName(),$this->getTipoAnuncio(),$this->getDados(),$this->getValorSemTaxa(),$this->getTotalInformado(),$this->getDadosIntegrado());
        $data = $produto->integrarViaApi($this->getDescricao(),$this->getIdProduct());

        if (!isset($data['id'])) {
            return ['message' => $data, 'statusCode' => 400];
        } else {
            EventoAfiliado::dispatch($products);
            return ['message' => $data, 'statusCode' => 200];
        }
    }

    public function getErrosFunction(array $data)
    {
        $this->setErros($data);
    }
}
