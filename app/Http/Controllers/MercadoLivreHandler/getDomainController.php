<?php

namespace App\Http\Controllers\MercadoLivreHandler;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class getDomainController extends Controller
{
    protected $doimain_id;
    protected $attributes;

    public function __construct($doimain_id,$attributes)
    {
        $this->doimain_id = $doimain_id;
        $this->attributes = $attributes;
    }

    /**
     * Get the value of doimain_id
     */
    public function getDoimainId()
    {
        return $this->doimain_id;
    }

    /**
     * Set the value of doimain_id
     */
    public function setDoimainId($doimain_id): self
    {
        $this->doimain_id = $doimain_id;

        return $this;
    }

    public function handlerAttributes($attributes){
        //  print_r($attributes);
    }

    public function HandlerError($error,$data,$categoria = false,$category_id,$newtitle,$atributos,$token){

        $handerError = [];
        try {
            if(count($error->cause) > 1){
                foreach ($error->cause as $erro) {
                    $keys = explode('.', $erro->references[0]); // if($keys[count($keys) - 1])
                    if(count($keys) === 1){
                        array_push($handerError,[ $keys[0], 'error' => $erro->cause_id]);
                    }else{
                        array_push($handerError,[ $keys, 'error' => $erro->cause_id]);
                    }
                }
            }else{
                $dados = (array) $error;
                $keys = explode('.', $dados['cause'][0]->references[0]);
                array_push($handerError,[ $keys, 'error' => $dados['cause'][0]->cause_id]);
            }

        } catch (\Exception $th) {
            // echo $th->getMessage();
        }
        if($categoria == false){
            return $this->DeleteAttribute($data,$handerError,$newtitle);
        }else{
            return $this->IncluirAttribute($data,$handerError,$error,$category_id,$atributos,$token);
        }

    }


    public function forbidenAttributes($name){
        switch ($name) {
            case 'item':
                return false;
            case 'variations':
                return false;
                case 'catalog_product_id':
            return $name;
                case 'health':
                return $name;
            case 'location':
                    return $name;
        }
    }

    public function searchAttributeError($array){

        $allErros = [];
        foreach ($array as $key => $value) {

            if(is_array($value[0])){

                if($value['error'] == 147){
                    array_push($allErros,$value['error']);
                }
                if($value['error'] == 287){
                    array_push($allErros,$value['error']);
                }
                if($value['error'] == 403){
                    array_push($allErros,$value['error']);
                }
                if($value['error'] == 101){
                    array_push($allErros,$value['error']);
                }
                if($value['error'] == 2617){
                    array_push($allErros,$value['error']);
                }
                if($value['error'] == 323){
                    array_push($allErros,$value['error']);
                }
                if($value['error'] == 146){

                    array_push($allErros,$value['error']);
                }
                if($value['error'] == 2610){
                    array_push($allErros,$value['error']);
                }
                else{

                foreach ($value as $chave => $each) {
                    if(is_countable($each)){
                       for ($i=0; $i < count($each); $i++) {
                            if($this->forbidenAttributes($each[$i])){
                                array_push($allErros,$each[$i]);
                            }
                       }
                    }
                }
                array_push($allErros,$value[0][0]);
            }
            }else{
                array_push($allErros,$value[0]);
         }
        }
        return $allErros;
    }

    public function converteObtToArrat($dados){
        return (array) $dados;
    }

    function getShortenedTitle($title) {
        return substr($title, 0, 60);
    }

    public function DeleteAttribute($data,$array,$newtitle){

        $arrayData = (array) $data;
        $arrayData['title'] = $newtitle;
        // APAGA A QUANTADADE MAXIMA
        foreach ($this->searchAttributeError($array) as $key => $erro) {
        // // remove o warranty
        if(isset($arrayData['warranty'])){
            unset($arrayData['warranty']);
        }
        if(isset($arrayData['status'])){
            unset($arrayData['status']);
        }
        if(isset($arrayData['tags'])){
            unset($arrayData['tags']);
        }
        if(isset($arrayData['official_store_id'])){
            unset($arrayData['official_store_id']);
        }

        if(isset($arrayData['variations'])){
           if(count($arrayData['variations']) > 0 ){
                if(is_array($arrayData['variations'][0]['attribute_combinations']))
                foreach ($arrayData['variations'][0]['attribute_combinations'] as $key => $value) {
                    array_push($arrayData['attributes'],$value);
                }else{
                    array_push($arrayData['attributes'],$arrayData['variations'][0]['attribute_combinations']);
                }
           }
        }

        if($erro == 147){
            if(is_array($arrayData['variations'][0]['attribute_combinations']))
            foreach ($arrayData['variations'][0]['attribute_combinations'] as $key => $value) {
                array_push($arrayData['attributes'],$value);
            }else{
                array_push($arrayData['attributes'],$arrayData['variations'][0]['attribute_combinations']);
            }
         }
        if($erro == 101){
           unset($arrayData['sale_terms']);
        }
        if($erro == 323){
            $arrayData['available_quantity'] = 100;
        }
        if($erro == 287){
            // $arrayData['available_quantity'] = 100;
        }

        if($erro == 146){
            unset($arrayData['variations']);
            $fotos = [];
            for ($i=0; $i < count($arrayData['pictures']); $i++) {
                if($i <= 11){
                    array_push($fotos,$arrayData['pictures'][$i]);
                }
            }
            $arrayData['pictures'] = $fotos;
        }

         // Removendo o objeto com o id igual a "SIZE_GRID_ID"
         foreach ($arrayData['attributes'] as $keyD => $attribute) {
            if ($attribute['id'] === "SIZE_GRID_ID") {
                unset($arrayData['attributes'][$keyD]);
                $arrayData['attributes'] = array_values($arrayData['attributes']);
            }

        }

        if($erro == 2617){

            // Removendo o objeto com o id igual a "SIZE_GRID_ID"
            foreach ($arrayData['attributes'] as $keyD => $attribute) {
                if ($attribute['id'] === "SIZE_GRID_ID") {
                    unset($arrayData['attributes'][$keyD]);
                }
            }

            $arrayData['attributes'] = array_values($arrayData['attributes']);

            unset($arrayData['variations']);

            $fotos = [];
            if(isset($arrayData['pictures'])){
                for ($i=0; $i < count($arrayData['pictures']); $i++) {
                    if($i <= 11){
                        array_push($fotos,$arrayData['pictures'][$i]);
                    }
                }
                $arrayData['pictures'] = $fotos;
            }

        }
        if($erro == 2610){
            unset($arrayData['variations']);
            $fotos = [];
            if(isset($arrayData['pictures'])){
                for ($i=0; $i < count($arrayData['pictures']); $i++) {
                    if($i <= 11){
                        array_push($fotos,$arrayData['pictures'][$i]);
                    }
                }
                $arrayData['pictures'] = $fotos;
            }
        }

         foreach ($arrayData as $key => $value) {

            if(!is_array($value)){

                if($key == $erro){
                    unset($arrayData[$key]);
                }

                if(empty($value)){
                    unset($arrayData[$key]);
                }
            }
            else{

                if($key == $erro){
                    unset($arrayData[$key]);
                }
                if(empty($value)){
                    unset($arrayData[$key]);
                }
                foreach ($value as $chave1 => $val) {

                    if(!is_array($val)){

                    }else{
                        foreach ($value as $chave2 => $valArray) {
                            if(is_array($valArray)){
                                if(array_search('PURCHASE_MAX_QUANTITY',$valArray)){
                                    unset($arrayData['sale_terms'][$chave2]);
                                }
                            }

                            if(!is_array($valArray)){
                                // echo $key . " => " . $valArray. "<hr>";
                            }else{
                               foreach ($valArray as $chave3 => $dados) {

                                if(!is_array($dados)){
                                 if($erro == $chave3){

                                    if(array_search('PURCHASE_MAX_QUANTITY',$valArray)){
                                        unset($arrayData['sale_terms'][$chave2]);
                                    }
                                    if(array_search('PACKAGE_WEIGHT',$valArray)){
                                        unset($arrayData['sale_terms'][$chave2]);
                                    }
                                    if(array_search('PACKAGE_WIDTH',$valArray)){
                                        unset($arrayData['sale_terms'][$chave2]);
                                    }


                                    if(isset($arrayData['variations'])){
                                        foreach ($arrayData['variations'] as $key => $value) {
                                            unset($arrayData['variations'][$key]['catalog_product_id']);
                                            $arrayData['variations'][$key]['available_quantity'] = 100;
                                        }
                                    }
                                 }
                                }
                               }
                            }

                        }
                    }

                }
            }

         }
        }

        $arrayData['title'] = $this->getShortenedTitle($arrayData['title']);
        return $arrayData;
    }

    function extrairPalavrasMaiusculas($frase) {
        preg_match_all('/\b([A-Z_]+)\b/', $frase, $matches);
        return $matches[1];
    }

    public function IncluirAttribute($data,$array,$message,$category_id,$atributos,$token){

        $attributes = [];
        // foreach ($message->cause as $value) {
        //     // SIZE_GRID_ID
        //     if($value->cause_id == 147){
        //         $dados = $this->extrairPalavrasMaiusculas($value->message);
        //         // Log::emergency(json_encode($dados));
        //         foreach ($this->getAttributescategoria($category_id) as $categoria) {

        //             if(in_array($categoria->id,$dados)){
        //                 $categoria->values = [array_shift($categoria->values)];
        //                 array_push($attributes,$categoria);
        //             }
        //         }
        //     }
        // }

        foreach ($this->getAttributescategoria($category_id) as $categoria) {

            if($categoria->id == "SIZE_GRID_ID"){
                foreach ($atributos as $atributo) {

                    // Filtra o array, removendo o item com "id" igual a "COLOR"
                    $filteredArray = array_filter($atributos, function($item) {
                        return $item['id'] !== 'SIZE';
                    });
                    //
                    // *** CRIA A TABELA DE MEDIDAS
                    //
                    if($atributo['id'] == 'GENDER'){
                        // CRIA A TABELA DE MEDIDAS CONFORME O GENERO
                        $atributos = $filteredArray;
                        foreach ($this->getAttributesCharts($token,$atributo['value']) as $key => $value) {
                            array_push($atributos,$value);
                        }
                    }


                }

            }
        }

        $data = [
            'category_id' => $category_id,
            'attributes' => $atributos
        ];

        Log::alert(json_encode($data));

        return $data;
    }

    public function getAttributescategoria($categoria) {

        $endpoint = "https://api.mercadolibre.com/categories/$categoria/attributes";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $json = json_decode($reponse);

            if ($httpCode == '200') {
                return $json;
            }

    }


    public function getAttributesCharts($token,$gender) {

        $endpoint = "https://api.mercadolibre.com/catalog/charts";

            $data_json = json_encode($this->TypeCharts($gender));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $json = json_decode($reponse,true);

            $data = [];
            if ($httpCode == '201') {
                // // Extraindo a primeira linha de rows
                $first_row = $json['rows'][0];

                // Encontrando o valor de SIZE
                $size_value = null;
                foreach ($first_row['attributes'] as $attribute) {
                    if ($attribute['id'] === 'SIZE') {
                        $size_value = $attribute;
                        break;
                    }
                }

                $data = [
                    [
                        "id" => "SIZE_GRID_ID",
                        "name" => "ID da guia de tamanhos",
                        "value_id" => null,
                        "value_name" => $json['id'],
                        "values" => [
                            [
                                "id" => null,
                                "name" => $json['id'],
                                "struct" => null
                            ]
                        ]
                        ],
                    [
                        "id" => "SIZE_GRID_ROW_ID",
                        "value_id" => $json['id'],
                        "value_name" => $json['id'].":1"
                    ],
                    $size_value
                ];
                return array_values($data);
            }

    }


    public function TypeCharts($gender){

        $letra = null;
        if($gender == 339665){
            $letra = "F";
        }else{
            $letra = "M";
        }


        $name = uniqid("ALTERADOR" . date('Y-m-d'));
        return  [
          "names" => [
                "MLB" => "GUIA $name"
             ],
          "domain_id" => "SNEAKERS",
          "site_id" => "MLB",
          "main_attribute" => [
                   "attributes" => [
                      [
                         "site_id" => "MLB",
                         "id" => $letra."_US_SIZE"
                      ]
                   ]
                ],
          "attributes" => [
                    [
                    "id" => "GENDER",
                    "values" => [
                    [
                    "id" => "339665",
                    "name" => "Feminino"
                    ]
                    ]
                    ]
                    ],
                    "rows" => [
                    [
                    "attributes" => [
                    [
                    "id" => "BR_SIZE",
                    "values" => [
                    [
                        "name" => "40 BR"
                    ]
                    ]
                    ],
                    [
            "id" => $letra."_US_SIZE",
            "values" => [
            [
            "name" => "8,5 US"
            ]
            ]
            ],
            [
            "id" => "FOOT_LENGTH",
            "values" => [
            [
            "name" => "10 cm"
            ]
            ]
            ]
            ]
            ],
            [
            "attributes" => [
            [
            "id" => "BR_SIZE",
            "values" => [
            [
            "name" => "41 BR"
            ]
            ]
            ],
            [
            "id" => $letra."_US_SIZE",
            "values" => [
            [
            "name" => "9 US"
            ]
            ]
            ],
            [
            "id" => "FOOT_LENGTH",
            "values" => [
            [
            "name" => "15 cm"
            ]
            ]
            ]
            ]
            ],
            [
            "attributes" => [
            [
            "id" => "BR_SIZE",
            "values" => [
            [
            "name" => "42 BR"
            ]
            ]
            ],
            [
            "id" => $letra."_US_SIZE",
            "values" => [
            [
            "name" => "9,5 US"
            ]
            ]
            ],
            [
            "id" => "FOOT_LENGTH",
            "values" => [
            [
            "name" => "20 cm"
            ]
            ]
            ]
            ]
            ]
                    ]
       ];


    }

    function manterApenasNumeros($termo) {
        // Substitui tudo que não for número por uma string vazia
        $termoApenasNumeros = preg_replace('/[^0-9]/', '', $termo);

        return $termoApenasNumeros;
    }

    public function notRemoveAttribute($array){
        switch ($array) {
            case 'variations':
                return false;
            case 'item':
                return false;
                case 'item':
                    return false;
            default:
                return true;
        }
    }



    function separarPalavra($palavra) {
        $arrayResultante = array();

        // Verifica se a palavra não está vazia
        if (!empty($palavra)) {
            // Encontra a posição do caractere underscore
            $posicaoUnderscore = strpos($palavra, '_');

            // Se o underscore for encontrado, divide a palavra em duas partes
            if ($posicaoUnderscore !== false) {
                // Parte antes do underscore
                $arrayResultante[] = substr($palavra, 0, $posicaoUnderscore);

                // Parte após o underscore
                $arrayResultante[] = substr($palavra, $posicaoUnderscore + 1);
            } else {
                // Se não houver underscore, a palavra original vai para o índice 0
                $arrayResultante[] = $palavra;
                // O índice 1 será uma string vazia
                $arrayResultante[] = '';
            }
        }

        return $arrayResultante;
    }

    function removerLetras($termo) {
        // Substitui todas as letras alfabéticas por uma string vazia
        $termoSemLetras = preg_replace('/[a-zA-Z]/',"", $termo);
        return $this->separarPalavra($termoSemLetras);
    }

    // Exemplo de uso

    public function exceptions($data){
        switch ($data) {
            case 'catalog_product_id':
                return 'variations';
            case '101':
                return ['type' => 'remove','where'=> $data, 'word' => 'sale_terms', 'location' => 'variations'];
            case '370':
                return ['value' => ["available_quantity" => 100],'type' => 'insert','where' => 'variations'];
            case '240':
                return ['type' => 'remove','where'=>$data];
            case '276':
                return ['type' => 'remove','where'=> $data];
            case '258':
                return ['type' => 'remove','where'=> $data];
            case '290':
                return ['type' => 'remove','where'=> $data];
            case '291':
                return ['type' => 'remove','where'=> $data];
            case '226':
                return ['type' => 'remove','where'=> $data, 'location' => 'sale_terms'];
                     default:
                return false;
        }
    }
}
