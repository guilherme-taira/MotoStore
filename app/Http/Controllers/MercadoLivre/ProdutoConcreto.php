<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Models\images;
use App\Models\mercado_livre_history;
use App\Models\Products;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Cast\Double;

class ProdutoConcreto implements Produto
{
    private Products $produto;
    private String $categoria;
    private String $price;
    private token $userId;

    public function __construct(Products $produto, $categoria, $price, token $userId)
    {
        $this->produto = $produto;
        $this->categoria = $categoria;
        $this->price = $price;
        $this->userId = $userId;
    }

    public function integrar()
    {
        $error_message = [];
        $success_data = [];
        $fotos = images::where('product_id', $this->getProduto()->id)->get();
        $photos = [];
        foreach ($fotos as $foto) {
            array_push($photos, ["source" => "https://file-upload-motostore.s3.sa-east-1.amazonaws.com/produtos/" . $foto->product_id . "/" . $foto->url]);
        }
        $data = [];
        if ($this->getProduto()) {
            $data['title'] = $this->getProduto()->title;
            $data['category_id'] = $this->getCategoria();
            $data['price'] = $this->getPrice();
            $data['currency_id'] = $this->getProduto()->currency_id;
            $data['available_quantity'] = $this->getProduto()->available_quantity;
            $data['buying_mode'] = $this->getProduto()->buying_mode;
            $data['listing_type_id'] = $this->getProduto()->listing_type_id;
            $data['condition'] = $this->getProduto()->condition;
            $data['description'] = $this->getProduto()->description;
            $data['tags'] = [
                "immediate_payment",
            ];
            $data['attributes'] = [
                [
                    "id" => "BRAND",
                    "value_name" => $this->getProduto()->brand,
                ],
                [
                    "id" => "GTIN",
                    "value_name" => $this->getProduto()->gtin
                ],
            ];

            if ($this->getPrice() > 79.99) {
                $data['shipping'] = [
                    "mode" => "me2",
                    "free_shipping" => "true",
                ];
            }

            if ($photos) {
                $data['pictures'] = $photos;
            } else {
                $data['pictures'] = [[
                    "source" =>
                    "https://file-upload-motostore.s3.sa-east-1.amazonaws.com/produtos/" . $this->getProduto()->id . "/" . $this->getProduto()->image
                ]];
            }

            $data_json = json_encode($data);
            // GET TOKEN
            $token = json_decode($this->getUserId())->access_token;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/items");
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token}"]);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $json = json_decode($reponse);
            if ($httpCode == 400) {
                if (empty($json->cause)) {
                    $error_message = $json->message;
                } else {
                    foreach ($json->cause as $erros) {
                        array_push($error_message, $erros->message);
                    }
                }
                //return $error_message;
            } else if ($httpCode == 201) {
                $mercado_livre_history = new mercado_livre_history();
                $mercado_livre_history->name = $json->title;
                $mercado_livre_history->id_ml = $json->id;
                $mercado_livre_history->id_user = Auth::user()->id;
                $mercado_livre_history->product_id = $this->getProduto()->id;
                $mercado_livre_history->save();
            }
        }
    }

    /**
     * Get the value of produto
     */
    public function getProduto(): Products
    {
        return $this->produto;
    }

    /**
     * Set the value of produto
     */
    public function setProduto(Products $produto): self
    {
        $this->produto = $produto;

        return $this;
    }


    /**
     * Get the value of categoria
     */
    public function getCategoria(): String
    {
        return $this->categoria;
    }

    /**
     * Set the value of categoria
     */
    public function setCategoria(String $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get the value of price
     */
    public function getPrice(): String
    {
        return $this->price;
    }

    /**
     * Set the value of price
     */
    public function setPrice(String $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Set the value of userId
     */
    public function setUserId(String $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of userId
     */
    public function getUserId(): String
    {
        return $this->userId;
    }
}
