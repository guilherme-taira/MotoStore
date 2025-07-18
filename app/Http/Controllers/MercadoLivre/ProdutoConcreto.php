<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Events\EventoCadastroIntegrado;
use App\Http\Controllers\Controller;
use App\Models\images;
use App\Models\mercado_livre_history;
use App\Models\Products;
use App\Models\produtos_integrados;
use App\Models\token;
use App\Models\Variacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\Double;

class ProdutoConcreto implements Produto
{
    private Products $produto;
    private String $categoria;
    private String $price;
    private token $userId;
    private String $name;
    private String $tipo_anuncio;
    private ?array $opcionais;
    private float $valorSemTaxa;
    private float $totalInformado;
    private $dadosIntegrado;
    private $json;
    private $variations;

    public function __construct(Products $produto, $categoria, $price, token $userId,$name,$tipo_anuncio,$opcionais,$valorSemTaxa = 0,$totalInformado = 0,$dadosIntegrado,$json = null,$variations = null)
    {
        $this->produto = $produto;
        $this->categoria = $categoria;
        $this->price = $price;
        $this->userId = $userId;
        $this->name = $name;
        $this->tipo_anuncio = $tipo_anuncio;
        $this->opcionais = $opcionais;
        $this->valorSemTaxa = $valorSemTaxa;
        $this->totalInformado = $totalInformado;
        $this->dadosIntegrado = $dadosIntegrado;
        $this->json = $json;
        $this->variations = $variations;
    }

    public function integrar($descricao,$id_prod)
    {
        $error_message = [];
        $success_data = [];
        $fotos = images::where('product_id', $this->getProduto()->id)->OrderBy('position','asc')->get();
        $photos = [];
        foreach ($fotos as $foto) {
            array_push($photos, ["source" => "https://afilidrop2.s3.us-east-1.amazonaws.com/produtos/" . $foto->product_id . "/" . $foto->url]);
        }

        $jsonData = json_decode($this->getJson(), true);
        $jsonData = is_array($jsonData) ? $jsonData : []; // Garante que é um array

        $data = [];
        if ($this->getProduto()) {
            $data['title'] = $this->getName();
            $data['category_id'] = $this->getCategoria();
            $data['price'] = $this->getPrice();
            $data['currency_id'] = $this->getProduto()->currency_id;
            $data['available_quantity'] = $this->produto->available_quantity;
            $data['buying_mode'] = $this->getProduto()->buying_mode;
            $data['listing_type_id'] = $this->getTipoAnuncio();
            $data['condition'] = $this->getProduto()->condition;
            $data['description'] =  [
                "plain_text" => $this->getProduto()->description
            ];
            $data['tags'] = [
                "immediate_payment",
            ];

            $data['attributes'] = [
                ...$jsonData,
                [
                    "id" => "BRAND",
                    "value_name" => $this->getProduto()->brand,
                ],
                [
                    "id" => "GTIN",
                    "value_name" => $this->getProduto()->gtin
                ],
                [
                    "id" => "MODEL",
                    "value_name" => 'GENERIC'
                ],
                [
                    "id" => "HEIGHT",
                    "name" => "Altura",
                    "value_id" => null,
                    "value_name" => "{$this->getProduto()->height} cm",
                    "values" => [
                          [
                             "id" => null,
                             "name" => "{$this->getProduto()->height} cm",
                             "struct" => [
                                "number" => $this->getProduto()->height,
                                "unit" => "cm"
                             ]
                          ]
                       ],
                    "value_type" => "number_unit"
                ],
                [
                    "id" => "LENGTH",
                    "name" => "Comprimento",
                    "value_id" => null,
                    "value_name" => "{$this->getProduto()->length} cm",
                    "values" => [
                          [
                             "id" => null,
                             "name" => "{$this->getProduto()->length} cm",
                             "struct" => [
                                "number" => $this->getProduto()->length,
                                "unit" => "cm"
                             ]
                          ]
                       ],
                    "value_type" => "number_unit"
                ],
            ];

             if(empty($this->getVariations())){
                $data['attributes'][] =     [
                    "id" => "SELLER_SKU",
                    "value_name" =>  $this->getProduto()->id
                ];
            }

            if($this->getVariations() != null){
                $data['variations'] = $this->getVariations();
            }

            $jsonData = json_decode($this->getProduto()->atributos_json, true);
            $jsonData = is_array($jsonData) ? $jsonData : [];
            $jsonData = array_filter($jsonData, function ($attribute) {
                return !(isset($attribute['id']) && $attribute['id'] === 'precoFixo');
            });

            // Agora adiciona os opcionais (também filtrando precoFixo)
            if (count($this->getOpcionais()) >= 1) {
                foreach ($this->getOpcionais() as $key => $dados) {
                    if (!(isset($dados['id']) && $dados['id'] === 'precoFixo')) {
                        array_push($data['attributes'], $dados);
                    }
                }
            }

            if ($this->getPrice() > 79.99) {
                $data['shipping'] = [
                    "mode" => "me2",
                    "free_shipping" => "true",
                ];
            }

            if ($photos) {
                $data['pictures'] = $photos;
            } else
                $data['pictures'] = [[
                    "source" =>
                    "https://file-upload-motostore.s3.sa-east-1.amazonaws.com/produtos/" . $this->getProduto()->id . "/" . $this->getProduto()->image
                ]];
            }



            $data_json = json_encode($data);
            // Log::alert($data_json);
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
                return $error_message;
            } else if ($httpCode == 201) {


                // SALVAR AS VARIAÇOES. -- >
             foreach ($json->variations as $var) {

                $meliId = $var->id;
                $sku = optional(collect($var->attributes)->firstWhere('id', 'SELLER_SKU'))->value_name ?? null;

                if (!$sku) continue;
                    Variacao::updateOrCreate(
                        ['meli_variation_id' => $meliId],
                        [
                            'id_mercadolivre' => $json->id,
                            'sku' => $sku,
                            'price' => $var->price,
                            'available_quantity' => $var->available_quantity,
                            'attribute_combinations' => json_encode($var->attribute_combinations),
                            'picture_ids' => json_encode($var->picture_ids),
                        ]
                    );
            }
                // FINALIZAR AS VARIAÇÔES -- <

                $this->CreateDescription($data,$json->id);
                // // evento cadastra produto no historico
                EventoCadastroIntegrado::dispatch(Auth::user()->id ,$json->title,$json->thumbnail,$json->id,$this->getProduto()->id,$this->getValorSemTaxa(),$this->getDadosIntegrado());
                $mercado_livre_history = new mercado_livre_history();
                $mercado_livre_history->name = $json->title;
                $mercado_livre_history->id_ml = $json->id;
                $mercado_livre_history->id_user = Auth::user()->id;
                $mercado_livre_history->product_id = $this->getProduto()->id;
                $mercado_livre_history->priceNotFee = $this->getValorSemTaxa();
                $mercado_livre_history->save();
            }
        }

    public function integrarViaApi($descricao,$id_prod)
    {
        Log::alert("INTEGRANDO " .$this->getProduto()->id);
        $error_message = [];
        $success_data = [];
        $fotos = images::where('product_id', $this->getProduto()->id)->OrderBy('position','asc')->get();
        $photos = [];
        foreach ($fotos as $foto) {
            array_push($photos, ["source" => "https://afilidrop2.s3.us-east-1.amazonaws.com/produtos/" . $foto->product_id . "/" . $foto->url]);
        }
        $data = [];

        $jsonData = json_decode($this->getProduto()->atributos_json, true);
        $jsonData = is_array($jsonData) ? $jsonData : []; // Garante que é um array

        if ($this->getProduto()) {
            $data['title'] = $this->getName();
            $data['category_id'] = $this->getCategoria();
            $data['price'] = $this->getPrice();
            $data['currency_id'] = $this->getProduto()->currency_id;
            $data['available_quantity'] = $this->produto->available_quantity;
            $data['buying_mode'] = $this->getProduto()->buying_mode;
            $data['listing_type_id'] = $this->getTipoAnuncio();
            $data['condition'] = $this->getProduto()->condition;
            $data['description'] =  [
                "plain_text" => $this->getProduto()->description
            ];
            $data['tags'] = [
                "immediate_payment",
            ];
            $data['attributes'] = [
                ...$jsonData,
                [
                    "id" => "SELLER_SKU",
                    "value_name" =>  $this->getProduto()->id
                ],
                [
                    "id" => "BRAND",
                    "value_name" => $this->getProduto()->brand,
                ],
                [
                    "id" => "GTIN",
                    "value_name" => $this->getProduto()->gtin
                ],
                [
                    "id" => "MODEL",
                    "value_name" => 'GENERIC'
                ],
                [
                    "id" => "HEIGHT",
                    "name" => "Altura",
                    "value_id" => null,
                    "value_name" => "{$this->getProduto()->height} cm",
                    "values" => [
                          [
                             "id" => null,
                             "name" => "{$this->getProduto()->height} cm",
                             "struct" => [
                                "number" => $this->getProduto()->height,
                                "unit" => "cm"
                             ]
                          ]
                       ],
                    "value_type" => "number_unit"
                ],
                [
                    "id" => "LENGTH",
                    "name" => "Comprimento",
                    "value_id" => null,
                    "value_name" => "{$this->getProduto()->length} cm",
                    "values" => [
                          [
                             "id" => null,
                             "name" => "{$this->getProduto()->length} cm",
                             "struct" => [
                                "number" => $this->getProduto()->length,
                                "unit" => "cm"
                             ]
                          ]
                       ],
                    "value_type" => "number_unit"
                ],
            ];


            // Agora adiciona os opcionais (também filtrando precoFixo)
            if (count($this->getOpcionais()) >= 1) {
                foreach ($this->getOpcionais() as $key => $dados) {
                    if (!(isset($dados['id']) && $dados['id'] === 'precoFixo')) {
                        array_push($data['attributes'], $dados);
                    }
                }
            }

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
            Log::alert($data);
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

            Log::alert($reponse);
            if ($httpCode == 400) {
                if (empty($json->cause)) {
                    $error_message = $json->message;

                } else {
                    foreach ($json->cause as $erros) {
                        array_push($error_message, $erros->message);
                    }
                }
                return $error_message;
            } else if ($httpCode == 201) {

                $this->CreateDescription($data,$json->id);
                // evento cadastra produto no historico
                EventoCadastroIntegrado::dispatch($this->userId->user_id,$json->title,$json->thumbnail,$json->id,$this->getProduto()->id,$this->getValorSemTaxa(),$this->getDadosIntegrado());
                $mercado_livre_history = new mercado_livre_history();
                $mercado_livre_history->name = $json->title;
                $mercado_livre_history->id_ml = $json->id;
                $mercado_livre_history->id_user = 1;
                $mercado_livre_history->product_id = $this->getProduto()->id;
                $mercado_livre_history->priceNotFee = $this->getValorSemTaxa();
                $mercado_livre_history->save();
                return ['id' => $json->id];
            }
        }
    }


    public function CreateDescription($data,$id){

        $token = json_decode($this->getUserId())->access_token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/items/$id/description");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data['description']));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token}"]);
        $reponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        Log::critical($reponse);
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

    /**
     * Get the value of name
     */
    public function getName(): String
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(String $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of tipo_anuncio
     */
    public function getTipoAnuncio(): String
    {
        return $this->tipo_anuncio;
    }

    /**
     * Set the value of tipo_anuncio
     */
    public function setTipoAnuncio(String $tipo_anuncio): self
    {
        $this->tipo_anuncio = $tipo_anuncio;

        return $this;
    }

    /**
     * Get the value of opcionais
     */
    public function getOpcionais(): ?array
    {
        return $this->opcionais;
    }

    /**
     * Get the value of valorSemTaxa
     */
    public function getValorSemTaxa(): float
    {
        return $this->valorSemTaxa;
    }

    /**
     * Get the value of totalInformado
     */
    public function getTotalInformado(): float
    {
        return $this->totalInformado;
    }

    /**
     * Get the value of dadosIntegrado
     */
    public function getDadosIntegrado()
    {
        return $this->dadosIntegrado;
    }

    /**
     * Get the value of json
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * Get the value of variations
     */
    public function getVariations()
    {
        return $this->variations;
    }
}
