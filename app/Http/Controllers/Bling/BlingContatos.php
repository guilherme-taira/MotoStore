<?php

namespace App\Http\Controllers\Bling;

use App\Http\Controllers\Controller;
use App\Models\Contato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlingContatos extends Controller
{
    private $apiUrl;
    private $authorization;
    private $id;

    public function __construct($authorization,$id)
    {
        $this->apiUrl = 'https://api.bling.com.br/Api/v3/contatos';
        $this->authorization = $authorization; // Substitua pelo token real
        $this->id = $id;
    }

    /**
     * Envia os dados do contato para a API do Bling.
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function enviarContato(array $data)
    {
        $ch = curl_init();
        // Configurar a URL e os cabeçalhos
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->authorization,
            'Content-Type: application/json',
        ]);

        // Configurar o corpo da requisição
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Executar a requisição
        $response = curl_exec($ch);

        $res = json_decode($response);
        // Obter o código HTTP da resposta
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Fechar o cURL
        curl_close($ch);

        Log::alert($response);
        // Verificar se a requisição falhou
        if ($httpCode == 201) {

            // Verificar se o contato existe
            $contato = Contato::find($this->id);

        Log::alert($contato);
            // Atualizar o campo bling_id
            $contato->update([
                'bling_id' => $res->data->id
            ]);
        }

        // Retornar a resposta decodificada
        return json_decode($response, true);

    }
}
