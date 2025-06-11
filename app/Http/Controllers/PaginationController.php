<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaginationController extends Controller
{
    public function coletarPaginas(Request $request)
    {
        set_time_limit(0);

        $url = $request->input('url');
        if (!$url) {
            return response()->json(['error' => 'URL não fornecida'], 400);
        }

        $paginaAtual = 1;
        $nextPages = [];

        while ($url) {
            $html = $this->obterHtmlViaCurl($url);
            $jsonRaw = $this->extrairJsonPreloadedState($html);

            if (!$jsonRaw) {
                return response()->json(['error' => '__PRELOADED_STATE__ não encontrado']);
            }

            $jsonDecoded = json_decode($jsonRaw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Erro ao decodificar JSON']);
            }

            $pagination = $this->encontrarPagination($jsonDecoded['pageState'] ?? []);
            if ($pagination && isset($pagination['next_page']['url']) && $pagination['next_page']['show']) {
                $nextUrl = html_entity_decode($pagination['next_page']['url']);

                if (in_array($nextUrl, $nextPages)) break;

                $nextPages[] = $nextUrl;
                $url = $nextUrl;
                $paginaAtual++;
            } else {
                break;
            }
        }

        return response()->json([
            'paginas_visitadas' => count($nextPages),
            'next_pages' => $nextPages
        ]);
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
