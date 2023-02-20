<?php

namespace App\Http\Controllers\Yapay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Pix extends AbstractTipoDeCobranca
{
    public function CriarPagamento()
    {
        $produtos = []; // ARRRAY DE PRODUTOS
        $newLinkPagamento = new CobrarInteface();
        $pedido = new ProdutoCestaController("1", $this->getTransactionProduct());
        $pagamento = $newLinkPagamento->criarPagamento($pedido);
        $cliente = $newLinkPagamento->getDadosCliente($this->getPostalCode(), $this->getStreet(), $this->getNumber(), $this->getNeighborhood(), $this->getCity(), $this->getState(), $this->getName(), $this->getCpf(), $this->getEmail());
        return $this->criarData($pagamento, $cliente);
    }


    public function criarData($pagamento, $cliente)
    {
        $data = [
            "token_account" => $this->getTokenAccount(),
            "customer" => $cliente,
            "transaction_product" => $pagamento,
            "payment" => [
                "payment_method_id" => "27",
                "split" => "1"
            ]
        ];
        return $data;
    }
}
