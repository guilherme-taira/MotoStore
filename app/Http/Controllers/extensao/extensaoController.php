<?php

namespace App\Http\Controllers\extensao;

use App\Http\Controllers\Controller;
use App\Models\token;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
set_time_limit(0); // Executa sem limite de tempo

class extensaoController extends Controller
{
   public function coletarTodasPaginasEProcessar(Request $request)
{
    $url = $request->query('url');
    $max = (int) $request->query('max', 3); // pega o "max" ou assume 3

    Log::alert("URL - ". $url);
    Log::alert("PAGINA - ". $max);
    if (!$url) return response()->json(['error' => 'URL inicial não fornecida.'], 400);

    $paginaAtual = 1;
    $nextPages = [$url];
    $visitados = [$url];

    while ($url && $paginaAtual <= $max) {
        $html = $this->obterHtmlViaCurl($url);
        $jsonRaw = $this->extrairJsonPreloadedState($html);

        if (!$jsonRaw) break;

        $jsonDecoded = json_decode($jsonRaw, true);
        if (json_last_error() !== JSON_ERROR_NONE) break;

        $pagination = $this->encontrarPagination($jsonDecoded['pageState'] ?? []);
        if ($pagination && isset($pagination['next_page']['url']) && $pagination['next_page']['show']) {
            $nextUrl = html_entity_decode($pagination['next_page']['url']);
            // Log::alert("nextUrl - ". $nextUrl);
            if (in_array($nextUrl, $visitados)) break;

            $nextPages[] = $nextUrl;
            $visitados[] = $nextUrl;
            $url = $nextUrl;
            $paginaAtual++;
        } else {
            break;
        }
    }

    $resultados = [];
    foreach ($nextPages as $paginaUrl) {
        $html = $this->obterHtmlViaCurl($paginaUrl);
        $resultados = array_merge($resultados, $this->extrairProdutosDaPagina($html));
    }

    return response()->json($resultados);
  }

    private function extrairProdutosDaPagina($html)
    {
        $token = token::where('user_id','1')->first();
        $todosProdutos = [];

        $doc = new \DOMDocument();
        @$doc->loadHTML($html);
        $xpath = new \DOMXPath($doc);

        $scriptTag = $xpath->query('//script[@id="__PRELOADED_STATE__"]')->item(0);

        $linksVenda = [];
        $linksNodes = $xpath->query("//a[contains(@class, 'poly-component__title')]");
        foreach ($linksNodes as $node) {
            $linksVenda[] = $node->getAttribute('href');
        }

        if (!$scriptTag) return [];

        $json = $scriptTag->nodeValue;
        $jsonDecoded = json_decode($json, true);
        $results = $jsonDecoded['pageState']['initialState']['results'] ?? [];

        foreach ($results as $index => $item) {
            $id = $item['polycard']['metadata']['id'] ?? null;
            $permalink = $linksVenda[$index] ?? null;

            if ($id) {
                $res = Http::withToken($token->access_token)->get("https://api.mercadolibre.com/items/{$id}");
                $vendidos = 0;

                if ($permalink) {
                    try {
                        $vendasRes = Http::withHeaders([
                            'User-Agent' => 'Mozilla/5.0',
                            'Accept-Language' => 'pt-BR,pt;q=0.9',
                        ])->withOptions([
                            'proxy' => "http://jwcxnvon-rotate:zpkdrpl7n7as@p.webshare.io:80",
                            'timeout' => 15,
                        ])->get($permalink);

                        if ($vendasRes->successful()) {
                            preg_match('/<span class=\"ui-pdp-subtitle\">[^|]+\|\s*\+?([\d.]+)\s+vendid[oa]s?(?:\s+\((mil)\))?/i', $vendasRes->body(), $matches);

                            $vendidos = 0;
                            if (isset($matches[1])) {
                                $quantidade = (float) str_replace('.', '', $matches[1]);
                                $temMil = isset($matches[2]) && strtolower($matches[2]) === 'mil';

                                $vendidos = $temMil ? $quantidade * 1000 : $quantidade;
                            }

                            // Log::alert("Vendidos: $vendidos");
                        }
                    } catch (\Exception $e) {
                        Log::error('Erro ao buscar vendas: ' . $e->getMessage());
                    }
                }

                $dadosApi = $res->ok() ? $res->json() : [];


                $diasCriado = isset($dadosApi['date_created'])
                    ? Carbon::parse($dadosApi['date_created'])->diffInDays(now())
                    : null;

                $todosProdutos[] = [
                    'id' => $dadosApi['id'] ?? $id,
                    'titulo' => $dadosApi['title'] ?? 'sem título',
                    'preco' => $dadosApi['price'] ?? 'sem preço',
                    'vendidos' => $vendidos,
                    'catalog_listing' => $dadosApi['catalog_listing'] ?? false,
                    'logistica' => isset($dadosApi['shipping']['logistic_type']) ? $dadosApi['shipping']['logistic_type'] : 'sem info',
                    'frete_gratis' => isset($dadosApi['shipping']['free_shipping']) && $dadosApi['shipping']['free_shipping'] ? 'sim' : 'nao',
                    'internacional' => ($dadosApi['international_delivery_mode'] ?? '') === 'DDP' ? 'sim' : 'nao',
                    'permalink' => $dadosApi['permalink'] ?? 'sem link',
                    'dias_criado' => $diasCriado ?? 'N/A',
                    'date_created' => $dadosApi['date_created'] ?? null,
                ];
            }
        }

        return $todosProdutos;
    }

    private function obterHtmlViaCurl($url)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private function extrairJsonPreloadedState($html)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $scriptTags = $dom->getElementsByTagName('script');

        foreach ($scriptTags as $script) {
            if ($script->getAttribute('id') === '__PRELOADED_STATE__') {
                return $script->nodeValue;
            }
        }
        return null;
    }

    private function encontrarPagination($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'pagination') {
                return $value;
            } elseif (is_array($value)) {
                $result = $this->encontrarPagination($value);
                if ($result !== null) return $result;
            }
        }
        return null;
    }

}
