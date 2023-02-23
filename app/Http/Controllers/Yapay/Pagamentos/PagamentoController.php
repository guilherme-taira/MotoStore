<?php

namespace App\Http\Controllers\Yapay\Pagamentos;

use App\Http\Controllers\Controller;
use App\Models\financeiro;
use Illuminate\Http\Request;

class PagamentoController implements interfacePagamento
{

    private String $token;
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function getToken(){
        return $this->token;
    }

    public function setStatus(array $status){
        // DADOS OBTIDOS
        echo "<pre>";
        $data = json_decode(json_encode($status));
        print_r($data->data_response->transaction->token_transaction);
        financeiro::UpdateFinanceiroByToken($data->data_response->transaction->token_transaction,$data->data_response->transaction->status_id,$data->data_response->transaction->status_name);

    }
}
