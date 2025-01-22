<?php

namespace App\Http\Controllers\Bling;

use App\Http\Controllers\Controller;
use App\Models\BlingCreateUserByFornecedor;
use App\Models\Contato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlingContatos extends Controller
{
    private $apiUrl;
    private $authorization;
    private $id;
    private $forncedor;
    private $method;

    public function __construct($authorization,$id,$forncedor,$method = "POST")
    {
        $this->apiUrl = 'https://api.bling.com.br/Api/v3/contatos';
        $this->authorization = $authorization; // Substitua pelo token real
        $this->id = $id;
        $this->forncedor = $forncedor;
        $this->method = $method;
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
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$this->method); // Método HTTP PUT
        // Configurar o corpo da requisição
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Executar a requisição
        $response = curl_exec($ch);

        Log::alert($response);
        $res = json_decode($response);
        // Obter o código HTTP da resposta
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Fechar o cURL
        curl_close($ch);

        Log::alert($response);
        // Verificar se a requisição falhou
        if ($httpCode == 201) {

            BlingCreateUserByFornecedor::create([
                'contato_id' => $this->id,
                'fornecedor_id' => $this->forncedor,
                'bling_id' =>  $res->data->id
            ]);
        }

        // Retornar a resposta decodificada
        return $res->data->id;

    }

    /**
     * Envia os dados do contato para a API do Bling.
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function atualizarContato(array $data,$id)
    {
        $ch = curl_init();
        // Configurar a URL e os cabeçalhos
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl."/".$id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->authorization,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$this->method); // Método HTTP PUT
        // Configurar o corpo da requisição
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Executar a requisição
        $response = curl_exec($ch);
        // Obter o código HTTP da resposta
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        log::alert($response);

        if ($httpCode == 400) {
            $res = json_decode($response, true); // Decodifica o JSON em um array associativo

            $errorMessages = [];

            // Verifica se existem campos com erros na resposta
            if (isset($res['error']['fields']) && is_array($res['error']['fields'])) {
                foreach ($res['error']['fields'] as $field) {
                    $errorMessages[] = $field['msg']; // Coleta as mensagens de erro
                }
            } else {
                // Mensagem genérica caso os campos não estejam definidos
                $errorMessages[] = $res['error']['message'] ?? 'Erro desconhecido.';
            }

            // Redireciona de volta com as mensagens de erro
            return redirect()->back()->with('errorMessages', $errorMessages);
        } elseif ($httpCode == 204) {
            return redirect()->back()->with('successMessage', 'Atualizado com sucesso!');
        } else {
            return redirect()->back()->with('successMessage','Atualizado com sucesso!');
        }


    }

    /**
     * Get the value of forncedor
     */
    public function getForncedor()
    {
        return $this->forncedor;
    }
}
