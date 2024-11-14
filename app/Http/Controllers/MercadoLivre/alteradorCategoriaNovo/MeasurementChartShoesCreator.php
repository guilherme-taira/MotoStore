<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MeasurementChartShoesCreator extends Controller
{
    private $requestMain;

    public function __construct($requestMain)
    {
        $this->requestMain = $requestMain;
    }

    public function createShoeSizeChart()
    {
        $url = 'https://api.mercadolibre.com/catalog/charts';

        Log::alert(json_encode($this->requestMain));
        // Gera um unique_id com a data de hoje e o sufixo "sneakers"
        $uniqueName = uniqid(date('Y-m-d')."dresses");

         // Estrutura da tabela de medidas para sapatos, incluindo o unique_id no nome
         $data = [
            "names" => [
                "MLB" => $uniqueName
            ],
            "domain_id" => "SNEAKERS",
            "site_id" => "MLB",
            "main_attribute" => [
                "attributes" => [
                    [
                        "site_id" => "MLB",
                        "id" => "BR_SIZE"  // Usando BR_SIZE como o main_attribute
                    ]
                ]
            ],
            "attributes" => [
                [
                    "id" => "GENDER",
                    "values" => [
                        [
                            "id" => "339665", // Valor para gênero masculino, por exemplo
                            "name" => "Masculino"
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
                                     "id"=> "3189094",
                                    "name" => "32 BR",

                                ]
                            ]
                        ],
                        [
                            "id" => "FOOT_LENGTH",
                            "values" => [
                                ["name" => "24.5 cm"]
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

        $result = json_decode($response,true);


        foreach ($this->requestMain->data as &$attribute) {

            if ($attribute['id'] === "SIZE") {
                foreach ($attribute['values'] as &$value) {
                    if ($value['name'] === "32") {
                        $value['name'] .= " BR"; // Adiciona " BR" ao valor "32"
                    }
                }
            }
        }


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
