<?php

namespace App\Http\Controllers\MelhorEnvio\Cart;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartImplementacao extends CartAbstract
{
    function criarVolumes()
    {
        $data = [];
        $altura = 0;
        $largura = 0;
        $comprimento = 0;
        $peso = 0;
        $i = 0;


        foreach ($this->getVolumes() as $key => $value) {
            $altura += $value['altura'];
            $largura += $value['largura'];
            $comprimento += $value['comprimento'];
            $peso += $value['peso'] * $this->getProducts()[0][$i]['quantidade'];
            $i++;
        }
        $data['height'] = $altura;
        $data['width'] = $largura;
        $data['length'] = $comprimento;
        $data['weight'] = $peso;
        return $data;
    }

    function criarprodutos()
    {
        $data = [];
        $produto = [];
        foreach ($this->getProducts() as $key => $produtos) {
            foreach ($produtos as $product) {
                $productInCart = Products::findMany($product['produto'])->first();
                $produto['name'] = $productInCart->title;
                $produto['quantity'] = $product['quantidade'];
                $produto['unitary_value'] = $productInCart->price;
                array_push($data, $produto);
            }
        }
        return $data;
    }

    public function Remetente()
    {
        $data = [];
        $data['name'] = "Afilidrop";
        $data['phone'] = "53984470102";
        $data['email'] = "contato@melhorenvio.com.br";
        $data['company_document'] = "48930389000109";
        $data['address'] = "R VITORIO LUPPI";
        $data['complement'] = "APT 22";
        $data['number'] = "470";
        $data['district'] = "JARDIM TUFANIN";
        $data['city'] = "LEME";
        $data['country_id'] = "BR";
        $data['postal_code'] = "13610296";
        return $data;
    }

    public function Destinatario()
    {
        $data = [];
        $users = Auth::user();

        $data['name'] = $users->name;
        $data['phone'] = $users->phone;
        $data['email'] = $users->email;
        $data['company_document'] = $users->cnpj != 0 ? $users->cnpj : "08469365000171";
        if (isset($users->cpf)) {
            $data['cpf'] = $users->cpf;
        }
        $data['address'] = $users->address;
        $data['complement'] = $users->complemento;
        $data['number'] = $users->numero;
        $data['district'] = $users->bairro;
        $data['city'] = $users->cidade;
        $data['country_id'] = "BR";
        $data['postal_code'] = $users->cep;
        return $data;
    }

    public function getDados()
    {
        $data = [];
        $data['service'] = $this->getService();
        $data['agency'] = $this->getAgencia();
        $data['from'] = $this->Remetente();
        $data['to'] = $this->Destinatario();
        $data['products'] = $this->criarprodutos();
        $data['volumes'] = $this->criarVolumes();
        $data['options'] = $this->options();
        return $data;
    }

    public function options()
    {
        $data = [];
        $data['insurance_value'] = 1;
        $data['receipt'] = false;
        $data['own_hand'] = false;
        $data['reverse'] = false;
        $data['non_commercial'] = false;
        $data['invoice'] = [
            "key" => ""
        ];
        $data['platform'] = "Afilidrop";
        return $data;
    }

    public function volumes()
    {
    }
}
