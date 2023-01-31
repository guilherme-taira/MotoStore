<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Models\images;
use App\Models\Products;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Double;

class ProdutoConcreto implements Produto
{
    private Products $produto;

    public function __construct(Products $produto)
    {
        $this->produto = $produto;
    }


    public function integrar()
    {
        $fotos = images::where('product_id', $this->getProduto()->id)->get();
        $photos = [];
        foreach ($fotos as $foto) {
            array_push($photos, ["source" => "https://file-upload-motostore.s3.sa-east-1.amazonaws.com/produtos/" . $foto->product_id . "/" . $foto->url]);
        }
        $data = [];
        if ($this->getProduto()) {
            $data['title'] = $this->getProduto()->title;
            $data['category_id'] = $this->getProduto()->category_id;
            $data['price'] = $this->getProduto()->price;
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
                    "name" => "Marca",
                    "value_name" => $this->getProduto()->brand
                ],
                [
                    "id" => "GTIN",
                    "name" => "Marca",
                    "value_name" => $this->getProduto()->gtin
                ],
            ];

            if (!$photos) {
                $data['pictures'] = $photos;
            }else{
                $data['pictures'] = ["source" => "https://file-upload-motostore.s3.sa-east-1.amazonaws.com/produtos/".$this->getProduto()->id."/".$this->getProduto()->image];
            }

            $data_json = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/items");
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer APP_USR-3029233524869952-013110-a14684b0ae3feb0327a02387ab39d5d3-141075614"]);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $json = json_decode($reponse);
            echo "<pre>";
            print_r($json);
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
}
