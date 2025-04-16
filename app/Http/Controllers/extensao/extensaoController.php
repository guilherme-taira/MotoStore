<?php

namespace App\Http\Controllers\extensao;

use App\Http\Controllers\Controller;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
set_time_limit(0); // Executa sem limite de tempo

class extensaoController extends Controller
{
    public function paginarAutomaticamente(Request $request)
    {
        $url = $request->query('url');
        $token = token::where('user_id','34')->first();
        $todosProdutos = [];

        while ($url) {
            $html = file_get_contents($url);
            $doc = new \DOMDocument();
            @$doc->loadHTML($html);
            $xpath = new \DOMXPath($doc);

            $scriptTag = $xpath->query('//script[@id="__PRELOADED_STATE__"]')->item(0);

            // Captura os links de venda visíveis na página
            $linksVenda = [];
            $linksNodes = $xpath->query("//a[contains(@class, 'poly-component__title')]");
            foreach ($linksNodes as $node) {
                $linksVenda[] = $node->getAttribute('href');
            }

            if ($scriptTag) {
                $json = $scriptTag->nodeValue;
                $jsonDecoded = json_decode($json, true);

                if ($jsonDecoded && isset($jsonDecoded['pageState']['initialState']['results'])) {
                    $results = $jsonDecoded['pageState']['initialState']['results'];
                    foreach ($results as $index => $item) {
                        $id = $item['polycard']['metadata']['id'] ?? null;
                        $permalink = $linksVenda[$index] ?? null;

                        if ($id) {
                            // Requisição à API do Mercado Livre
                            $res = Http::withToken($token->access_token)->get("https://api.mercadolibre.com/items/{$id}");
                            $vendidos = 0;
                            if ($permalink) {
                                try {
                                    $vendasRes = Http::withHeaders([
                                        'User-Agent' => 'Mozilla/5.0',
                                        'Accept-Language' => 'pt-BR,pt;q=0.9',
                                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                                    ])->withOptions([
                                        'proxy' => "http://jwcxnvon-rotate:zpkdrpl7n7as@p.webshare.io:80",
                                        'timeout' => 15,
                                    ])->get($permalink);

                                    if ($vendasRes->successful()) {
                                        preg_match('/<span class=\"ui-pdp-subtitle\">[^|]+\|\s*\+?([\d.]+)\s+vendid[oa]s?<\/span>/i', $vendasRes->body(), $matches);
                                        $vendidos = isset($matches[1]) ? (int)str_replace('.', '', $matches[1]) : 0;
                                        Log::alert($vendidos);
                                    }
                                } catch (\Exception $e) {
                                    Log::error('Erro ao buscar vendas: ' . $e->getMessage());
                                }
                            }

                            if ($res->ok()) {
                                $dadosApi = $res->json();

                                $todosProdutos[] = [
                                    'id' => $dadosApi['id'] ?? $id,
                                    'titulo' => $dadosApi['title'] ?? 'sem título',
                                    'preco' => $dadosApi['price'] ?? 'sem preço',
                                    'vendidos' => $vendidos,
                                    'catalog_listing' => $dadosApi['catalog_listing'] ?? false,
                                    'logistica' => $dadosApi['shipping']['logistic_type'] ?? 'sem info',
                                    'frete_gratis' => $dadosApi['shipping']['free_shipping'] ? 'sim' : 'nao',
                                    'internacional' => $dadosApi['international_delivery_mode'] === 'DDP' ? 'sim' : 'nao',
                                    'permalink' => $dadosApi['permalink'] ?? 'sem link' // ✅ adicionando o link do anúncio
                                ];

                            }
                        }
                    }
                }
            }

            // Buscar link "Seguinte"
            $nextLink = $xpath->query("//li[contains(@class, 'andes-pagination__button--next')]/a")->item(0);
            $url = $nextLink ? $nextLink->getAttribute('href') : null;

            Log::alert($url);
            sleep(1);
        }

        return response()->json($todosProdutos);
    }
}
