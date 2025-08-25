<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Bling\BlingContatos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\image\image;
use App\Http\Controllers\MercadoLivre\Generatecharts;
use App\Http\Controllers\MercadoLivre\GeneratechartsSneakers;
use App\Http\Controllers\MercadoLivre\MlbCallAttributes;
use App\Http\Controllers\MercadoLivre\MlbTipos;
use App\Http\Controllers\MercadoLivreHandler\ConcretoDomainController;
use App\Http\Controllers\MercadoLivreHandler\getDomainController;
use App\Http\Controllers\SaiuPraEntrega\SaiuPraEntregaService;
use App\Http\Controllers\SaiuPraEntrega\SendNotificationPraEntregaController;
use App\Http\Controllers\SaiuPraEntrega\TypeMessageController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\Shopify\LineItem;
use App\Http\Controllers\Shopify\Order;
use App\Http\Controllers\Shopify\SendOrder;
use App\Http\Controllers\Shopify\ShippingAddress;
use App\Http\Controllers\Shopify\ShopifyProduct;
use App\Http\Controllers\TikTokProductController;
use App\Jobs\UpdateProductPriceTikTok;
use App\Jobs\UpdateStockJob;
use App\Models\BlingCreateUserByFornecedor;
use App\Models\Contato;
use App\Models\FcmToken;
use App\Models\images;
use App\Models\IntegracaoBling;
use App\Models\order_site;
use App\Models\Products;
use App\Models\produtos_integrados;
use App\Models\SellerAccount;
use App\Models\ShippingUpdate;
use App\Models\Shopify;
use App\Models\token;
use App\Models\User;
use App\Notifications\PushNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class testController extends Controller {

        function getFormattedDate($date) {
            // Converte a data para o formato 'Y-m-d' se necessário
            $formattedDate = date('Y-m-d', strtotime($date));
            // Obtém o mês abreviado
            $monthAbbreviated = date('M', strtotime($formattedDate));

            $meses = [
                "January" => "Janeiro",
                "February" => "Fevereiro",
                "March" => "Março",
                "April" => "Abril",
                "May" => "Maio",
                "June" => "Junho",
                "July" => "Julho",
                "August" => "Agosto",
                "September" => "Setembro",
                "October" => "Outubro",
                "November" => "Novembro",
                "December" => "Dezembro"
            ];

            if(isset($meses[$monthAbbreviated])){
                $mesTraduzido = $meses[$monthAbbreviated];
            }
            // Obtém o dia do mês
            $dayOfMonth = date('j', strtotime($formattedDate));

            // Formata a data como "M DIA"
            $formattedDate = $mesTraduzido . ',' . $dayOfMonth;

            return $formattedDate;
        }


        public function teste(Request $request) {
            UpdateProductPriceTikTok::dispatch('517',100);
        }

function getTikTokPublicProduct(string $productId, array $fields = ['title', 'price', 'images', 'shop_id', 'category', 'stock']): array
{
    try {

        $accessToken = $this->getTikTokResearchAccessToken();
        Log::alert($accessToken);
        // *** ATENÇÃO: ALTERE ESTA URL PARA O ENDPOINT DE SANDBOX ***
        $sandboxProductUrl = 'https://open.tiktokapis.com/v2/research/tts/product/'; // VERIFIQUE NA DOCUMENTAÇÃO!
        // Ou 'https://sandbox.open.tiktokapis.com/v2/research/tts/product/' etc.


        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type'  => 'application/json',
        ])->post($sandboxProductUrl, [ // Use a URL de sandbox aqui
            'product_id' => $productId,
            'fields'     => implode(',', $fields),
        ]);

        if (! $response->successful()) {
            Log::warning('TikTok Research product query falhou', [
                'product_id' => $productId,
                'status'     => $response->status(),
                'body'       => $response->body(),
            ]);
            return [
                'success' => false,
                'error' => 'Erro na consulta do produto: HTTP ' . $response->status(),
                'details' => $response->json(),
            ];
        }

        $data = $response->json();
        if (isset($data['data'])) {
            return [
                'success' => true,
                'product' => $data['data'],
            ];
        }

        return [
            'success' => false,
            'error' => 'Resposta inesperada da API',
            'raw' => $data,
        ];
    } catch (\Throwable $e) {
        Log::error('Exceção ao buscar produto público TikTok', [
            'product_id' => $productId,
            'message' => $e->getMessage(),
        ]);
        return [
            'success' => false,
            'error' => 'Exceção: ' . $e->getMessage(),
        ];
    }
}

function getTikTokResearchAccessToken(): string
{
    return Cache::remember('tiktok_research_access_token', 100, function () {
        $clientId = config('services.tiktok_research.client_id') ?? env('TIKTOK_RESEARCH_CLIENT_ID');
        $clientSecret = config('services.tiktok_research.client_secret') ?? env('TIKTOK_RESEARCH_CLIENT_SECRET');


        if (!$clientId || !$clientSecret) {
            throw new \RuntimeException('client_id ou client_secret da Research API não configurados.');
        }

        // ***** IMPORTANTE: Substitua 'SUA_URL_DE_SANDBOX_DE_AUTENTICACAO' pela URL REAL de sandbox *****
        // Esta URL NÃO É a que está na documentação que você enviou, pois aquela é de produção.
        // Você precisa encontrar a URL de sandbox para 'oauth/token' na documentação da Research API.
        // Por exemplo, pode ser algo como:
        // 'https://open-sandbox.tiktokapis.com/v2/oauth/token/'
        // ou 'https://sandbox.open.tiktokapis.com/v2/oauth/token/'
        // A documentação da Research API (não a genérica de token) deve especificar isso.
        $authUrl = "https://open.tiktokapis.com/v2/oauth/token/"; // Adicione ao seu .env


        $response = Http::asForm()->post($authUrl, [ // Use a URL de sandbox aqui
            'grant_type'    => 'client_credentials',
            'client_key'     => $clientId,
            'client_secret' => $clientSecret,
        ]);

        if (! $response->successful()) {
            Log::error('Erro obtendo token da Research API', ['body' => $response->body()]);
            throw new \RuntimeException('Falha ao obter token da Research API: ' . $response->body());
        }

        $data = $response->json();
        if (empty($data['access_token'])) {
            Log::error('Resposta inválida ao obter token Research', ['json' => $data]);
            throw new \RuntimeException('access_token não retornado pela Research API.');
        }

        $expiresIn = $data['expires_in'] ?? 3600;
        // cache um pouco menos que expiração
        Cache::put('tiktok_research_access_token', $data['access_token'], $expiresIn - 60);

        return $data['access_token'];
    });
}



}
