<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MeasurementChartBlouses extends Controller
{
    private $requestMain;

    public function __construct($requestMain)
    {
        $this->requestMain = $requestMain;
    }

    public function createBlousesSizeChart()
    {
        $url = 'https://api.mercadolibre.com/catalog/charts';

        // Cria nome único
        $uniqueName = uniqid(date('Y-m-d') . "blouses");

        // Extrai o gênero
        $gender = [];
        foreach (array_values($this->requestMain->data) as $value) {
            if ($value['id'] == 'GENDER') {
                $gender = array_values($value['values']);
            }
        }

        // Extrai tamanho
        $size = [];
        foreach ($this->requestMain->data as &$attribute) {
            if ($attribute['id'] === "SIZE") {
                foreach ($attribute['values'] as &$value) {
                    $size['name'] = $value['name'];
                    $value['name'] .= " BR"; // adiciona " BR"
                    $size['id'] = $value['id'];
                }
            }
        }

        // Monta payload corrigido
        $data = [
            "names" => [
                "MLB" => $uniqueName
            ],
            "domain_id" => "BLOUSES",
            "site_id" => "MLB",
            "attributes" => [
                [
                    "id" => "GENDER",
                    "values" => $gender
                ]
            ],
            "main_attribute" => [
                "attributes" => [
                    [
                        "site_id" => "MLB",
                        "id" => "SIZE"
                    ]
                ]
            ],
            "rows" => [
                [
                    "attributes" => [
                        [
                            "id" => "SIZE",
                            "values" => [
                                ["name" => $size['name'] . " BR"]
                            ]
                        ],
                        [
                            "id" => "FILTRABLE_SIZE",
                            "values" => [
                                ["name" => "P"]
                            ]
                        ],
                        [
                            "id" => "CHEST_CIRCUMFERENCE_FROM", // obrigatório
                            "values" => [
                                ["name" => "88 cm"]
                            ]
                        ],
                        [
                            "id" => "WAIST_CIRCUMFERENCE_FROM",
                            "values" => [
                                ["name" => "82 cm"]
                            ]
                        ],
                        [
                            "id" => "HIP_CIRCUMFERENCE_FROM",
                            "values" => [
                                ["name" => "90 cm"]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Requisição cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->requestMain->token,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception("Erro cURL: " . curl_error($ch));
        }

        Log::alert($response);
        curl_close($ch);

        $result = json_decode($response, true);

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

        return array_merge($this->requestMain->data, $grid);
    }
}
