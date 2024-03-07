<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Generatecharts extends chartsController
{
     function brokeDomain(){
        return explode("-", $this->getDomainId());
     }

     function generateCharts(){
        $chart = [
            "names" => ["MLB" => $this->getNames()],
            "domain_id" => $this->brokeDomain()[1],
            "site_id" => $this->brokeDomain()[0],
            'attributes' => [array_values($this->getAttributes())[0]],
            'main_attribute' =>$this->getMainAttribute(),
            'rows' => $this->getRows()['rows']
        ];

        return json_encode($chart);
    }

    public function requestChart($user){
        // TOKEN
        $token = token::where('user_id_mercadolivre',$user)->first();
         // URL PARA REQUISICAO
         $endpoint = 'https://api.mercadolibre.com/catalog/charts';
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $endpoint);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $this->generateCharts());
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
         $response = curl_exec($ch);
         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         $res = json_decode($response);
         curl_close($ch);
         return $this->handleResult($res);
    }

    public function handleResult($response){


        $data = [];
        $data['id'] = [
            "id" => "SIZE_GRID_ID",
            "value_name" => $response->id
        ];
        $data['attributes'] = $response->attributes;
        foreach ($response->rows as $id => $row) {
            $data['rows'][$id] = $row;
        }
        return $data;

    }


    public function insertDataResult($produto, $data){

        $chaves = [];
        $atributos = [];
        foreach ($data['attributes']  as $key => $value) {
            array_push($atributos,$value->id);
        }

        foreach ($produto->attributes as $key => $attribute) {
            if(in_array($attribute->id,$atributos)){
                $chaves[] = $key;
            }
            if($attribute->id == "SIZE_GRID_ID"){
                $chaves[] = $key;
            }
        }
        foreach ($chaves as $chave) {
            unset($produto->attributes[$chave]);
        }

        $attributosWord = [];
        $size = [];
        $fotos = [];
        // coloca os attributos para criar a grade.
        array_push($produto->attributes,$data['id'],$data['attributes'][0]);
        // pega todos os attributos das variacoes tirando size
        foreach ($produto->variations as $variation) {
            // GRAVA FOTOS
            array_push($fotos,json_encode(['cor' => $variation->attribute_combinations[0]->value_name, 'pictures' => $variation->picture_ids]));
            foreach ($variation->attribute_combinations as $key => $combination) {
                if($combination->id != "SIZE"){
                    $attributosWord[$combination->value_name] = $combination;
                }else{
                    $size[] = $combination;
                }
            }
        }


        $fotosSerealizada = array_map('json_decode',array_values(array_unique($fotos)));

        $arraySemRepetidos = array_unique(array_map('json_encode', $size));
        // Convertendo de volta para objetos
        $arraySemRepetidos = array_map('json_decode', $arraySemRepetidos);
        // Resultado
        $attribute_combinations = [];

        foreach ($attributosWord as $key => $value) {
           foreach (array_values($arraySemRepetidos) as $k => $chave) {
                $attribute_combinations[] = [
                    "attribute_combinations" => [
                        $attributosWord[$key],$chave
                    ]
                ];
           }
        }

        usort($attribute_combinations, function($a, $b) {
            // Extrair tamanho e cor de $a
            $tamanhoA = null;
            $corA = null;
            foreach ($a['attribute_combinations'] as $atributo) {
                if ($atributo->id === 'SIZE') {
                    $tamanhoA = $atributo->value_name;
                } elseif ($atributo->id === 'COLOR') {
                    $corA = $atributo->value_name;
                }
            }

            // Extrair tamanho e cor de $b
            $tamanhoB = null;
            $corB = null;
            foreach ($b['attribute_combinations'] as $atributo) {
                if ($atributo->id === 'SIZE') {
                    $tamanhoB = $atributo->value_name;
                } elseif ($atributo->id === 'COLOR') {
                    $corB = $atributo->value_name;
                }
            }
            // Comparar pelo tamanho
            $resultadoTamanho = strcmp($tamanhoA, $tamanhoB);
            // Se os tamanhos forem diferentes, retornar a comparação dos tamanhos
            if ($resultadoTamanho !== 0) {
                return $resultadoTamanho;
            }
            // Se os tamanhos forem iguais, comparar pelas cores
            return strcmp($corA, $corB);
        });


        $numeros = [];
        // PEGA O VALOR QUE VAI SER CRIADO AS VARIACOES DE GRADE
        foreach ($attribute_combinations as $item) {
            foreach ($item['attribute_combinations'] as $attribute) {
                if ($attribute->id === 'SIZE') {
                    // Se for, pega o valor do comprimento do pé
                    array_push($numeros,$attribute->value_name);

                }
            }
        }

        $numeroParaCriar = array_values(array_unique($numeros));


        foreach ($data['rows'] as $item) {
            foreach ($item->attributes as $attribute) {
                if ($attribute->id === 'SIZE') {

                if(in_array($this->removerBR($attribute->values[0]->name),$this->removerLetrasArray($numeroParaCriar))){
                    // Se for, pega o valor do comprimento do pé  foreach ($attribute_combinations as $item) {
                        foreach ($attribute_combinations as $key => $itemV) {
                            if($this->removerBR($attribute->values[0]->name) == $itemV['attribute_combinations'][1]->value_name){
                                foreach ($fotosSerealizada as $foto) {
                                    if($foto->cor == $itemV['attribute_combinations'][0]->value_name){
                                        $arrayData =
                                            [
                                               [ 'id' => "SIZE_GRID_ROW_ID",
                                                'value_name' => $item->id ]
                                            ];


                                        array_push($attribute_combinations[$key],$arrayData,$foto->pictures,$produto->price);
                                    }

                                }

                            }
                        }
                    }
                }

            }
        }


        $definitivo = [];
        foreach ($attribute_combinations as $i => $val) {
         try {
                $data1 = isset($val[1]) ? $val[1] : [];
                $data2 = isset($val[2]) ? $val[2] : 0;
                    if(count($data1) > 0 && $data2 > 0){
                        $definitivo[$i]['attribute_combinations'] = $this->adicionarSufixoBR($val['attribute_combinations'], false);
                        $definitivo[$i]['attributes'] = $val[0];
                        $definitivo[$i]['picture_ids'] = $val[1];
                        $definitivo[$i]['price'] = $val[2];
                        $definitivo[$i]['available_quantity'] = 100;
                    }
             } catch (\Exception $th) {
                echo $th->getMessage();
            }
        }
        return $definitivo;
    }

    function adicionarSufixoBR($produto,$sizeAdded) {
        foreach ($produto as $key => $atributo) {
            if ($atributo->id === 'SIZE' && !$sizeAdded) {
                $atributo->value_name .= ' BR';
                $atributo->value_name = preg_replace('/\s+BR(?=.*BR)/', '', $atributo->value_name);
                $sizeAdded = true; // Marcamos que o sufixo foi adicionado
            }
        }
        return $produto;
    }

    function removerLetrasArray($array) {
        // Loop através de cada elemento do array
        foreach ($array as &$texto) {
            // Remove todas as letras de cada string usando expressões regulares
            $texto = preg_replace('/[a-zA-Z]/', '', $texto);
        }
        return $array;
    }

    function removerBR($texto) {
        // Verifica se a string contém "BR"
        if (strpos($texto, 'BR') !== false) {
            // Se contiver "BR", remove
            $texto = str_replace('BR', '', $texto);
        }
        return $texto;
    }

    }
