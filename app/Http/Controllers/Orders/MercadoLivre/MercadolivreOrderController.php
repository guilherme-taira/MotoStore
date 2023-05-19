<?php

namespace App\Http\Controllers\Orders\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Cliente\getDataShippingController;
use App\Http\Controllers\MercadoLivre\Cliente\implementacaoCliente;
use App\Http\Controllers\MercadoLivre\Cliente\InterfaceClienteController;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Http\Controllers\Yapay\GeradorPagamento;
use App\Http\Controllers\Yapay\ProdutoMercadoLivre;
use App\Models\financeiro;
use App\Models\order_site;
use App\Models\pivot_site;
use App\Models\product_site;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MercadolivreOrderController implements InterfaceMercadoLivre
{

    const URL_BASE_ML = "https://api.mercadolibre.com/";

    private String $sellerId;
    private String $token;

    public function __construct($sellerId, $token)
    {
        $this->sellerId = $sellerId;
        $this->token = $token;
    }

    public function getVenda($sellerId)
    {
    }

    public function saveOrder()
    {
    }

    public function get($resource)
    {
        // ENDPOINT PARA REQUISICAO
        $endpoint = self::URL_BASE_ML . $resource;
        /**
         * CURL REQUISICAO -X GET
         * **/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$this->getToken()}"]);
        $reponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json = json_decode($reponse);
         //echo "<pre>";
        if ($httpCode == 200) {
            foreach ($json->results as $result) {
                // ARRAY DE PRODUTOS
                $produtos = [];
                foreach ($result->payments as $payments) {

                    foreach ($result->order_items as $item) {
                        array_push($produtos, new ProdutoMercadoLivre($item->item->title, $item->quantity, $item->unit_price));
                    }
                    /***
                     * IMPLEMENTAÇÃO DO SELLER ID PARA PEGAR OS DADOS PARA GERAR O PIX NA CONTA
                     * DADOS ESSES COMO ENDEREÇO COMPLETO E DADOS PESSOAIS COMO NOME, CPF OU CNPJ
                     */
                    if (order_site::VerificarVenda($result->id) == false) {
                        $cliente = new InterfaceClienteController($result->buyer->id, $this->getToken());
                        $cliente->resource();
                        $id_order = $cliente->saveClient($result);

                        // if (!empty($result->shipping->id)) {
                        //     $getShipping = new getDataShippingController($result->shipping->id, $this->getToken());
                        //     $endereco = $getShipping->resource();

                        //     $CriarPix = new implementacaoCliente($result->buyer->nickname, $endereco->address_line, $endereco->zip_code, $endereco->address_line, $endereco->street_number, "A", $endereco->neighborhood, $endereco->city, $endereco->state, $result->buyer->nickname, "46857167877", "mercadolivre@mercadolivre.com", $produtos, 27, 1);
                        //     $CriarPix->CriarPagamento();

                        //     $gerarValor = new GeradorPagamento($CriarPix);
                        //     $pagamento = $gerarValor->resource();
                        //     $shipping = isset($result->shipping->id) ? $result->shipping->id : 0;
                        //     financeiro::SavePayment($pagamento->status_id, $payments->total_paid_amount, $id_order, Auth::user()->id, $pagamento->payment->url_payment, $pagamento->payment->qrcode_path,$pagamento->status_name,$pagamento->token_transaction,$shipping);
                        //  }
                    }
                }
            }
        }
    }

    public function resource()
    {
        return $this->get("orders/search?seller=" . $this->getSellerId() . "&order.status=paid&sort=date_desc&shipping");
    }

    /**
     * Get the value of sellerId
     */
    public function getSellerId(): String
    {
        return $this->sellerId;
    }

    /**
     * Set the value of sellerId
     */
    public function setSellerId(String $sellerId): self
    {
        $this->sellerId = $sellerId;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken(): String
    {
        return $this->token;
    }

    /**
     * Set the value of token
     */
    public function setToken(String $token): self
    {
        $this->token = $token;

        return $this;
    }
}
