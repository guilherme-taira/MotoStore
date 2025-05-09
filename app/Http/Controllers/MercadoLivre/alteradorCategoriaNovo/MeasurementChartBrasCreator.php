<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class MeasurementChartBrasCreator extends Controller
{
    private $requestMain;

    public function __construct($requestMain)
    {
        $this->requestMain = $requestMain;
    }

    public function createBrasSizeChart()
    {
        $url = 'https://api.mercadolibre.com/catalog/charts';

        // Nome único com base na data
        $uniqueName = uniqid(date('Y-m-d') . "-bras");

        $gender = [];

        foreach (array_values($this->requestMain->data) as $value) {
            if ($value['id'] === 'GENDER') {
                $gender = array_values($value['values']);
            }
        }

        $data = [
            "names" => [
                "MLB" => $uniqueName
            ],
            "domain_id" => "BRAS",
            "site_id" => "MLB",
            "attributes" => [
                [
                    "id" => "GENDER",
                    "values" => $gender
                ]
            ],
           "rows" => [
                [
                    "attributes" => [
                        [
                            "id" => "SIZE",
                            "values" => [
                                ["name" => "28"]
                            ]
                        ],
                        [
                            "id" => "FILTRABLE_SIZE",
                            "values" => [
                                ["name" => "M"]
                            ]
                        ],
                        [
                            "id" => "BUST_CIRCUMFERENCE_FROM",
                            "values" => [
                                ["name" => "86 cm"]
                            ]
                        ],
                        [
                            "id" => "BUST_CIRCUMFERENCE_TO",
                            "values" => [
                                ["name" => "90 cm"]
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
        $result = json_decode($response, true);


        if ($httpCode !== 201 || empty($result['id'])) {
            throw new Exception("Erro ao criar a grade de medidas: " . $response);
        }

        // Retorna os dados da grade criada
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
                "value_name" => $result['rows'][0]['id'] ?? null
            ]
        ];

        return array_merge($this->requestMain->data, $grid);
    }

}
