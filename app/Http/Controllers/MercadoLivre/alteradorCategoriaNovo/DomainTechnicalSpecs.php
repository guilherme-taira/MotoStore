<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class DomainTechnicalSpecs extends Controller
{
    private $domain;

    public function __construct(ProdutoData $domain)
    {
        $this->domain = $domain;
    }

    public function fetchTechnicalSpecs()
    {
        $domainId = $this->domain->resource();
        $url = "https://api.mercadolibre.com/domains/{$domainId['domain_id']}/technical_specs";

        // Fazendo a requisição para a API usando file_get_contents
        $response = file_get_contents($url);
        if ($response === false) {
            throw new Exception("Erro ao fazer a requisição para {$url}");
        }

        // Decodificando o JSON de resposta
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erro ao decodificar a resposta JSON: " . json_last_error_msg());
        }

        return $data;
    }
}
