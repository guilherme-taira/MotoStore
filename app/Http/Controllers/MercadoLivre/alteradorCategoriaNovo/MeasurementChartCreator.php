<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MeasurementChartCreator
{
    private $requestMain;

    public function __construct($requestMain)
    {
        $this->requestMain = $requestMain;
    }

    public function createDressSizeChart()
    {
        $url = 'https://api.mercadolibre.com/catalog/charts';

        // Gera um unique_id com a data de hoje e o sufixo "sneakers"
        $uniqueName = uniqid(date('Y-m-d')."dresses");

        $gender = [];

        foreach (array_values($this->requestMain->data) as $key => $value) {
           if($value['id'] == 'GENDER'){
                $gender = array_values($value['values']);
           }
        }

        $data = [
            "names" => [
                "MLB" => $uniqueName
            ],
            "domain_id" => "DRESSES",
            "site_id" => "MLB",
            "attributes" => [
                [
                    "id" => "GENDER",
                    "values" =>
                       $gender
                ]
            ],
            "rows" => [
                [
                    "attributes" => [
                        [
                            "id" => "SIZE",
                            "values" => [
                                ["name" => "PP"]
                            ]
                        ],
                        [
                            "id" => "FILTRABLE_SIZE",
                            "values" => [
                                [

                                    "name" => "PP"
                                ]
                            ]
                        ],
                        [
                            "id" => "CHEST_CIRCUMFERENCE_FROM",
                            "values" => [
                                ["name" => "85 cm"]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Configuração da requisição cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->requestMain->token,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Executa a requisição
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception("Erro cURL: " . curl_error($ch));
        }

        curl_close($ch);
        // Decodifica o JSON da resposta
        $result = json_decode($response,true);

        $grid = [
            [
                "id" => "SIZE_GRID_ID",
                "name" => "ID da guia de tamanhos",
                "value_id" => null,
                "value_name" => $result['id'],
                "values" => [
                    [
                        "id" => null,
                        "name" => $result['id'],
                        "struct" => null
                    ]
                ]
                    ],
            [
                "id" => "SIZE_GRID_ROW_ID",
                "value_id" => $result['id'],
                "value_name" => $result['rows'][0]['id']
            ]
            ];
           return array_merge($this->requestMain->data,$grid);

    }
}
