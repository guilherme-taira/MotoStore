<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class MeasurementChartSwimwear extends Controller
{
    private $requestMain;

    public function __construct($requestMain)
    {
        $this->requestMain = $requestMain;
    }

    public function createSwimwearSizeChart()
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

        $size = [];
        foreach ($this->requestMain->data as &$attribute) {

            if ($attribute['id'] === "SIZE") {
                foreach ($attribute['values'] as &$value) {

                        $size['name'] = $value['name'];
                        $value['name'] .= " BR"; // Adiciona " BR" ao valor "32"
                        $size['id'] = $value['id'];

                }
            }
        }


        $data = [
            "names" => [
                "MLB" => $uniqueName
            ],
            "domain_id" => "SWIMWEAR",
            "site_id" => "MLB",
            "attributes" => [
                [
                    "id" => "GENDER",
                    "values" =>
                       $gender
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
                                ["name" => $size['name']. " BR"]
                            ]
                        ],
                        [
                            "id" => "FILTRABLE_SIZE",
                            "values" => [
                                ["name" => "P"]
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
                        ],
                        [
                            "id" => "LENGTH_FROM_WAIST_TO_ANKLE_FROM",
                            "values" => [
                                ["name" => "104 cm"]
                            ]
                        ],
                        [
                            "id" => "LENGTH_FROM_INSEAM_TO_ANKLE_FROM",
                            "values" => [
                                ["name" => "107 cm"]
                            ]
                        ],
                        [
                            "id" => "THIGH_CIRCUMFERENCE_FROM",
                            "values" => [
                                ["name" => "45 cm"]
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
