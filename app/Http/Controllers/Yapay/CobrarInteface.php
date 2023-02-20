<?php

namespace App\Http\Controllers\Yapay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CobrarInteface implements CobrancaPixController
{

    private ProdutoMercadoLivre $ProdutoMercadoLivre;

    public function getToken(String $tokenAccount)
    {
        return ["token_account" => $tokenAccount];
    }

    public function criarPagamento(ProdutoCestaController $data): array
    {
        $produtos = [];
        if ($data->getQuantidade() > 1) {
            foreach ($data->produtos as $value) {
                $dados = [
                    "description" => $value->descricao,
                    "quantity" =>  $value->quantidade,
                    "price_unit" => $value->valor,
                    "code" => "1",
                    "sku_code" => "0001",
                    "extra" => "Informação Extra"
                ];

                array_push($produtos,$dados);
            }
        } else {
            foreach ($data as $value) {
                $dados = [
                    "description" => $value->descricao,
                    "quantity" =>  $value->quantidade,
                    "price_unit" => $value->valor,
                    "code" => "1",
                    "sku_code" => "0001",
                    "extra" => "Informação Extra"
                ];
                array_push($produtos,$dados);
            }
        }

        return $produtos;
    }

    public function getDadosCliente($postal_code, $street, $number, $neighborhood, $city, $state, $name, $cpf, $email)
    {
        $data =  [
            "contacts" => [
                [
                    "type_contact" => "H",
                    "number_contact" => "1133221122"
                ],
                [
                    "type_contact" => "M",
                    "number_contact" => "11999999999"
                ]
            ],
            "addresses" => [
                [
                    "type_address" => "B",
                    "postal_code" => $postal_code,
                    "street" => $street,
                    "number" => $number,
                    "completion" => "A",
                    "neighborhood" => $neighborhood,
                    "city" => $city,
                    "state" => $state
                ],
            ],
            "name" => $name,
            "birth_date" => "21/05/1941",
            "cpf" => $cpf,
            "email" => $email
        ];

        return $data;
    }
}
