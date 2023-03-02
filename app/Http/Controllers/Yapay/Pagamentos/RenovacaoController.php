<?php

namespace App\Http\Controllers\Yapay\Pagamentos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Cliente\getDataShippingController;
use App\Http\Controllers\MercadoLivre\Cliente\implementacaoCliente;
use App\Http\Controllers\Yapay\GeradorPagamento;
use App\Http\Controllers\Yapay\ProdutoMercadoLivre;
use App\Models\financeiro;
use Illuminate\Http\Request;

class RenovacaoController extends Controller
{
    public function UpdatePayment($user,$getToken)
    {
        $data = financeiro::GetDataByUser($user);
        // ARRAY DE PRODUTOS
        $produtos = [];

        array_push($produtos, new ProdutoMercadoLivre($data->nome, $data->quantidade, $data->valor));

        $getShipping = new getDataShippingController($data->shipping_id, $getToken);
        $endereco = $getShipping->resource();

        $CriarPix = new implementacaoCliente($data->cliente, $endereco->address_line, $endereco->zip_code, $endereco->address_line, $endereco->street_number, "A", $endereco->neighborhood, $endereco->city, $endereco->state,$data->cliente, "46857167877", "mercadolivre@mercadolivre.com", $produtos, 27, 1);
        $CriarPix->CriarPagamento();

        $gerarValor = new GeradorPagamento($CriarPix);
        $pagamento = $gerarValor->resource();
        $shipping = isset($data->shipping_id) ? $data->shipping_id: 0;
        financeiro::where('shipping_id',$data->shipping_id)->update(['status' => $pagamento->status_id,'qrcode' => $pagamento->payment->url_payment, 'link' => $pagamento->payment->url_payment,'token_transaction' => $pagamento->token_transaction, 'value_status' => $pagamento->status_name]);
    }
}
