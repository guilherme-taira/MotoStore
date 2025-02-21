<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\financeiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class devolucaoController extends Controller
{

    protected $topic;
    protected $metodo;
    protected $token;
    protected $userId;

    public function __construct($topic,$metodo = "GET",$token,$userId)
    {
        $this->topic = $topic;
        $this->metodo = $metodo;
        $this->token = $token;
        $this->userId = $userId;
    }


    public function getdataByClaims(){
        try {
            $url = "https://api.mercadolibre.com/".$this->getTopic();

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer {$this->getToken()}",
                    "x-format-new: true"
                ]
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            Log::alert($response);
            if ($httpCode >= 200 && $httpCode < 300) {
                $data = json_decode($response, true); // Decodifica como array associativo

                if (isset($data['related_entities']) && is_array($data['related_entities'])) {
                    if (in_array("return", $data['related_entities'])) {
                        $this->saveData($data['id']);
                    }
                } else {
                    Log::alert("related_entities não está presente ou não é um array.");
                }
            }
        } catch (\Exception $e) {
            // Tratamento de erro
            Log::alert($e->getMessage());
        }
    }


    public function saveData($claim){
        try {
            $url = "https://api.mercadolibre.com/post-purchase/v2/claims/$claim/returns";

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer {$this->getToken()}",
                    "x-format-new: true"
                ]
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            if ($httpCode >= 200 && $httpCode < 300) {
                $data = json_decode($response, true); // Decodifica como array associativo
                  // Pegando dados para inserção
                  $rastreio = $data['shipping']['tracking_number'] ?? null;
                  $id_venda = $data['resource_id'] ?? null;
                  $fornecedor =  financeiro::where('token_transaction','=',$data['resource_id'])->first();
                  $shippingId = $data['shipping']['id'] ?? null;
                  $dados = json_encode($data); // Salvando o JSON completo

                  if ($rastreio && $id_venda && $fornecedor) {
                      // Inserindo no banco de dados
                      DB::table('devolucoes')->insert([
                          'rastreio' => $rastreio,
                          'id_venda' => $id_venda,
                          'id_user' => $fornecedor->user_id,
                          'shippingId' => $shippingId,
                          'dados' => $dados,
                          'created_at' => now(),
                          'updated_at' => now()
                      ]);

                    //   Log::alert("Registro inserido na tabela devolucoes com sucesso!");
                  } else {
                    //   Log::alert("Dados insuficientes para inserção na tabela devolucoes.");
                  }
            }
        } catch (\Exception $e) {
            // Tratamento de erro
            Log::alert($e->getMessage());
        }
    }

    /**
     * Get the value of shipping
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Get the value of versao
     */
    public function getMetodo()
    {
        return $this->metodo;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {
        return $this->token;
    }
}
