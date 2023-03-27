<?php

namespace App\Http\Controllers\MelhorEnvio\Cart;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MelhorEnvio\MelhorEnvioImplementacao;
use Illuminate\Http\Request;

class RequestCompraFrete implements MelhorEnvioImplementacao
{
    // ENDPOINT DE TESTE NÂO PRODUCAO
    const URL_BASE_MELHOR_ENVIO = "https://sandbox.melhorenvio.com.br/";

    private CompraFreteImplementacao $dados;

    public function __construct(CompraFreteImplementacao $dados)
    {
        $this->dados = $dados;
    }

    public function get($resource)
    {

        //ENDPOINT PARA REQUISICAO
        $endpoint = self::URL_BASE_MELHOR_ENVIO . $resource;
        // PEGA DADOS DA COTACAO
        $data['orders'] = [$this->getDados()->getOrderid()];
        // ENDPOINT PARA REQUISICAO
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImRlMTlmOTJlZjBmMDIyNmE4YjQ2NTY3MmQzOGZjZGMzNGVmM2UyMGU1ZjExZGM2MjU5Yjg3Njc5MDA1ODNkMDE3YmM1OGI3NDVhNTgxMzkxIn0.eyJhdWQiOiI5NTYiLCJqdGkiOiJkZTE5ZjkyZWYwZjAyMjZhOGI0NjU2NzJkMzhmY2RjMzRlZjNlMjBlNWYxMWRjNjI1OWI4NzY3OTAwNTgzZDAxN2JjNThiNzQ1YTU4MTM5MSIsImlhdCI6MTY3ODEzMzEzNiwibmJmIjoxNjc4MTMzMTM2LCJleHAiOjE3MDk3NTU1MzYsInN1YiI6IjMxOTM4MGJmLWY3OWMtNGZkOS04ZDZiLWFiMTlhOWM5YjQ2YyIsInNjb3BlcyI6WyJjYXJ0LXJlYWQiLCJjYXJ0LXdyaXRlIiwiY29tcGFuaWVzLXJlYWQiLCJjb21wYW5pZXMtd3JpdGUiLCJjb3Vwb25zLXJlYWQiLCJjb3Vwb25zLXdyaXRlIiwibm90aWZpY2F0aW9ucy1yZWFkIiwib3JkZXJzLXJlYWQiLCJwcm9kdWN0cy1yZWFkIiwicHJvZHVjdHMtZGVzdHJveSIsInByb2R1Y3RzLXdyaXRlIiwicHVyY2hhc2VzLXJlYWQiLCJzaGlwcGluZy1jYWxjdWxhdGUiLCJzaGlwcGluZy1jYW5jZWwiLCJzaGlwcGluZy1jaGVja291dCIsInNoaXBwaW5nLWNvbXBhbmllcyIsInNoaXBwaW5nLWdlbmVyYXRlIiwic2hpcHBpbmctcHJldmlldyIsInNoaXBwaW5nLXByaW50Iiwic2hpcHBpbmctc2hhcmUiLCJzaGlwcGluZy10cmFja2luZyIsImVjb21tZXJjZS1zaGlwcGluZyIsInRyYW5zYWN0aW9ucy1yZWFkIiwidXNlcnMtcmVhZCIsInVzZXJzLXdyaXRlIiwid2ViaG9va3MtcmVhZCIsIndlYmhvb2tzLXdyaXRlIl19.ogeYkquZjelBOJMfzu7dkIlpq28l9d5GNeMyM5zHG-5LS9CUTMF3HRBxYr_5WeshFgeLKg7TBlxQpQalyHNHlzxULuBQVYpBkZ7QHERNteLaf6zQTsu9Ntgq-zycwYen4jl6om9XGHUoIQEOkZ26rE_LhxOeRJpn0TddoE3Dj-DWvZClu4DqEuWYCGEVsg-ds7lvnYKIdeh4J4jDwuG8q46MYI3zEEs7yGQKp0Ble3_4RjlGIpkVZ15lo44EXmOHsQORFDSsMTMN_Dyf-LuCn-GvFOMv3QhRamwfF5pq0_5jstR_lKm0VHoUuaH-IiX8h1XsEB3e_MDhnFRlALpwU2ym-4xRCIjKLCytqADnTiebJOTj7OKdnvz2-03cW6XmACFylFgMNcOa6bztBbqSPcgEFScy5PIRnZH01Qr08zGmVeeKpbKzVhFvw1go81KiiPCrB4PKYJlb2nIDUF0HrsyrTGylnRc860yIHPArg-3_P0weFdR8ag-TXoi_6fGWRea5y5Es_nPqr_BbR3Y5XHqOfuYK6JNDYU5Ea15Hpn017XKhFEinIak_z2xmOyJIXIPUdsryj1JLSr4FzFLOhL6ayGruCmZjkul-bIiScDwzXQed3serbPETQAHMw5xS3s_wnNiEJ2WhwOOVfvObVtWHzfNN43DB85cIMzdUw60"]);
        $reponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $transportadoras = json_decode($reponse);
        // echo "<pre>";
        // print_r($transportadoras);
    }

    public function resource()
    {
        return $this->get("api/v2/me/shipment/checkout");
    }


    /**
     * Get the value of dados
     */
    public function getDados(): CompraFreteImplementacao
    {
        return $this->dados;
    }
}
