<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Generatecharts;
use App\Http\Controllers\MercadoLivre\GeneratechartsSneakers;
use App\Http\Controllers\MercadoLivre\MlbCallAttributes;
use App\Http\Controllers\MercadoLivre\MlbTipos;
use App\Http\Controllers\MercadoLivreHandler\ConcretoDomainController;
use App\Http\Controllers\MercadoLivreHandler\getDomainController;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class testController extends Controller
{
    public function teste(Request $request){

        $produto = json_decode('{
            "id": "MLB2698521622",
            "site_id": "MLB",
            "title": "Tênis Nike Revolution 6 Next Nature Feminino",
            "seller_id": 792353538,
            "category_id": "MLB3900",
            "official_store_id": 3921,
            "price": 259.99,
            "base_price": 259.99,
            "original_price": null,
            "currency_id": "BRL",
            "initial_quantity": 5123,
            "sale_terms": [
                {
                    "id": "WARRANTY_TYPE",
                    "name": "Tipo de garantia",
                    "value_id": "2230279",
                    "value_name": "Garantia de fábrica",
                    "value_struct": null,
                    "values": [
                        {
                            "id": "2230279",
                            "name": "Garantia de fábrica",
                            "struct": null
                        }
                    ],
                    "value_type": "list"
                }
            ],
            "buying_mode": "buy_it_now",
            "listing_type_id": "gold_pro",
            "condition": "new",
            "permalink": "https://produto.mercadolivre.com.br/MLB-2698521622-tnis-nike-revolution-6-next-nature-feminino-_JM",
            "thumbnail_id": "976201-MLB69861935884_062023",
            "thumbnail": "http://http2.mlstatic.com/D_976201-MLB69861935884_062023-I.jpg",
            "pictures": [
                {
                    "id": "976201-MLB69861935884_062023",
                    "url": "http://http2.mlstatic.com/D_976201-MLB69861935884_062023-O.jpg",
                    "secure_url": "https://http2.mlstatic.com/D_976201-MLB69861935884_062023-O.jpg",
                    "size": "500x217",
                    "max_size": "1149x500",
                    "quality": ""
                },
                {
                    "id": "981550-MLB69861935880_062023",
                    "url": "http://http2.mlstatic.com/D_981550-MLB69861935880_062023-O.jpg",
                    "secure_url": "https://http2.mlstatic.com/D_981550-MLB69861935880_062023-O.jpg",
                    "size": "500x219",
                    "max_size": "1138x500",
                    "quality": ""
                },
                {
                    "id": "713278-MLB69861935872_062023",
                    "url": "http://http2.mlstatic.com/D_713278-MLB69861935872_062023-O.jpg",
                    "secure_url": "https://http2.mlstatic.com/D_713278-MLB69861935872_062023-O.jpg",
                    "size": "500x217",
                    "max_size": "1149x500",
                    "quality": ""
                },
                {
                    "id": "786578-MLB69861935886_062023",
                    "url": "http://http2.mlstatic.com/D_786578-MLB69861935886_062023-O.jpg",
                    "secure_url": "https://http2.mlstatic.com/D_786578-MLB69861935886_062023-O.jpg",
                    "size": "380x500",
                    "max_size": "871x1144",
                    "quality": ""
                },
                {
                    "id": "883350-MLB69861935882_062023",
                    "url": "http://http2.mlstatic.com/D_883350-MLB69861935882_062023-O.jpg",
                    "secure_url": "https://http2.mlstatic.com/D_883350-MLB69861935882_062023-O.jpg",
                    "size": "500x217",
                    "max_size": "1149x500",
                    "quality": ""
                },
                {
                    "id": "686451-MLB69861935874_062023",
                    "url": "http://http2.mlstatic.com/D_686451-MLB69861935874_062023-O.jpg",
                    "secure_url": "https://http2.mlstatic.com/D_686451-MLB69861935874_062023-O.jpg",
                    "size": "500x297",
                    "max_size": "839x500",
                    "quality": ""
                },
                {
                    "id": "782048-MLB69861935876_062023",
                    "url": "http://http2.mlstatic.com/D_782048-MLB69861935876_062023-O.jpg",
                    "secure_url": "https://http2.mlstatic.com/D_782048-MLB69861935876_062023-O.jpg",
                    "size": "500x500",
                    "max_size": "1200x1200",
                    "quality": ""
                },
                {
                    "id": "699008-MLB69861935878_062023",
                    "url": "http://http2.mlstatic.com/D_699008-MLB69861935878_062023-O.jpg",
                    "secure_url": "https://http2.mlstatic.com/D_699008-MLB69861935878_062023-O.jpg",
                    "size": "500x500",
                    "max_size": "1200x1200",
                    "quality": ""
                }
            ],
            "video_id": null,
            "descriptions": [],
            "accepts_mercadopago": true,
            "non_mercado_pago_payment_methods": [],
            "shipping": {
                "mode": "me1",
                "methods": [],
                "tags": [],
                "dimensions": "0x0x0,510",
                "local_pick_up": false,
                "free_shipping": false,
                "logistic_type": "default",
                "store_pick_up": false
            },
            "international_delivery_mode": "none",
            "seller_address": {
                "city": {
                    "id": "TUxCQ0VYVDdjZTBi",
                    "name": "Extrema"
                },
                "state": {
                    "id": "BR-MG",
                    "name": "Minas Gerais"
                },
                "country": {
                    "id": "BR",
                    "name": "Brasil"
                },
                "search_location": {
                    "city": {
                        "id": "TUxCQ0VYVDdjZTBi",
                        "name": "Extrema"
                    },
                    "state": {
                        "id": "TUxCUE1JTlMxNTAyZA",
                        "name": "Minas Gerais"
                    }
                },
                "id": 1182553707
            },
            "seller_contact": null,
            "location": {},
            "coverage_areas": [],
            "attributes": [
                {
                    "id": "GARMENT_TYPE",
                    "name": "Tipo de roupa",
                    "value_id": null,
                    "value_name": "Calçados",
                    "values": [
                        {
                            "id": null,
                            "name": "Calçados",
                            "struct": null
                        }
                    ],
                    "value_type": "string"
                },
                {
                    "id": "ADJUSTMENT_TYPES",
                    "name": "Tipos de ajuste",
                    "value_id": "7445558",
                    "value_name": "Cadarços",
                    "values": [
                        {
                            "id": "7445558",
                            "name": "Cadarços",
                            "struct": null
                        }
                    ],
                    "value_type": "string"
                },
                {
                    "id": "AGE_GROUP",
                    "name": "Idade",
                    "value_id": "6725189",
                    "value_name": "Adultos",
                    "values": [
                        {
                            "id": "6725189",
                            "name": "Adultos",
                            "struct": null
                        }
                    ],
                    "value_type": "list"
                },
                {
                    "id": "BRAND",
                    "name": "Marca",
                    "value_id": "14671",
                    "value_name": "Nike",
                    "values": [
                        {
                            "id": "14671",
                            "name": "Nike",
                            "struct": null
                        }
                    ],
                    "value_type": "string"
                },
                {
                    "id": "DETAILED_MODEL",
                    "name": "Modelo detalhado",
                    "value_id": null,
                    "value_name": "DC3729-001",
                    "values": [
                        {
                            "id": null,
                            "name": "DC3729-001",
                            "struct": null
                        }
                    ],
                    "value_type": "string"
                },
                {
                    "id": "EXTERIOR_MATERIALS",
                    "name": "Materiais do exterior",
                    "value_id": null,
                    "value_name": "Malha,Tecido",
                    "values": [
                        {
                            "id": "5167250",
                            "name": "Malha",
                            "struct": null
                        },
                        {
                            "id": "312073",
                            "name": "Tecido",
                            "struct": null
                        }
                    ],
                    "value_type": "string"
                },
                {
                    "id": "FOOTWEAR_TYPE",
                    "name": "Tipo de calçado",
                    "value_id": "517583",
                    "value_name": "Tênis",
                    "values": [
                        {
                            "id": "517583",
                            "name": "Tênis",
                            "struct": null
                        }
                    ],
                    "value_type": "list"
                },
                {
                    "id": "GENDER",
                    "name": "Gênero",
                    "value_id": "339665",
                    "value_name": "Feminino",
                    "values": [
                        {
                            "id": "339665",
                            "name": "Feminino",
                            "struct": null
                        }
                    ],
                    "value_type": "list"
                },
                {
                    "id": "ITEM_CONDITION",
                    "name": "Condição do item",
                    "value_id": "2230284",
                    "value_name": "Novo",
                    "values": [
                        {
                            "id": "2230284",
                            "name": "Novo",
                            "struct": null
                        }
                    ],
                    "value_type": "list"
                },
                {
                    "id": "MAIN_COLOR",
                    "name": "Cor principal",
                    "value_id": "2450295",
                    "value_name": "Preto",
                    "values": [
                        {
                            "id": "2450295",
                            "name": "Preto",
                            "struct": null
                        }
                    ],
                    "value_type": "list"
                },
                {
                    "id": "MIDSOLE_MATERIALS",
                    "name": "Materiais da entressola",
                    "value_id": "8576918",
                    "value_name": "Espuma",
                    "values": [
                        {
                            "id": "8576918",
                            "name": "Espuma",
                            "struct": null
                        }
                    ],
                    "value_type": "string"
                },
                {
                    "id": "MODEL",
                    "name": "Modelo",
                    "value_id": null,
                    "value_name": "DC3729",
                    "values": [
                        {
                            "id": null,
                            "name": "DC3729",
                            "struct": null
                        }
                    ],
                    "value_type": "string"
                },
                {
                    "id": "RECOMMENDED_SPORTS",
                    "name": "Esportes recomendados",
                    "value_id": "6694768",
                    "value_name": "Corrida",
                    "values": [
                        {
                            "id": "6694768",
                            "name": "Corrida",
                            "struct": null
                        }
                    ],
                    "value_type": "string"
                },
                {
                    "id": "SHAFT_TYPE",
                    "name": "Tipo de cano",
                    "value_id": "1164226",
                    "value_name": "Curto",
                    "values": [
                        {
                            "id": "1164226",
                            "name": "Curto",
                            "struct": null
                        }
                    ],
                    "value_type": "list"
                },
                {
                    "id": "STYLE",
                    "name": "Estilo",
                    "value_id": "6694772",
                    "value_name": "Esportivo",
                    "values": [
                        {
                            "id": "6694772",
                            "name": "Esportivo",
                            "struct": null
                        }
                    ],
                    "value_type": "list"
                },
                {
                    "id": "SUITABLE_SURFACES",
                    "name": "Superfícies aptas",
                    "value_id": null,
                    "value_name": "Rua ou Esteira",
                    "values": [
                        {
                            "id": null,
                            "name": "Rua ou Esteira",
                            "struct": null
                        }
                    ],
                    "value_type": "string"
                },
                {
                    "id": "VERSION",
                    "name": "Versão",
                    "value_id": "11450592",
                    "value_name": "6 Next Nature",
                    "values": [
                        {
                            "id": "11450592",
                            "name": "6 Next Nature",
                            "struct": null
                        }
                    ],
                    "value_type": "string"
                },
                {
                    "id": "WITH_PADDED_COLLAR",
                    "name": "Com cano acolchoado",
                    "value_id": "242085",
                    "value_name": "Sim",
                    "values": [
                        {
                            "id": "242085",
                            "name": "Sim",
                            "struct": null
                        }
                    ],
                    "value_type": "boolean"
                }
            ],
            "listing_source": "",
            "variations": [
                {
                    "id": 180243612293,
                    "price": 259.99,
                    "attribute_combinations": [
                        {
                            "id": "COLOR",
                            "name": "Cor",
                            "value_id": "52049",
                            "value_name": "Preto",
                            "values": [
                                {
                                    "id": "52049",
                                    "name": "Preto",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        },
                        {
                            "id": "SIZE",
                            "name": "Tamanho",
                            "value_id": "11375419",
                            "value_name": "34 BR",
                            "values": [
                                {
                                    "id": "11375419",
                                    "name": "34 BR",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        }
                    ],
                    "sale_terms": [],
                    "picture_ids": [
                        "976201-MLB69861935884_062023",
                        "981550-MLB69861935880_062023",
                        "713278-MLB69861935872_062023",
                        "786578-MLB69861935886_062023",
                        "883350-MLB69861935882_062023",
                        "686451-MLB69861935874_062023",
                        "782048-MLB69861935876_062023",
                        "699008-MLB69861935878_062023"
                    ],
                    "catalog_product_id": "MLB18738734"
                },
                {
                    "id": 180243612295,
                    "price": 259.99,
                    "attribute_combinations": [
                        {
                            "id": "COLOR",
                            "name": "Cor",
                            "value_id": "52049",
                            "value_name": "Preto",
                            "values": [
                                {
                                    "id": "52049",
                                    "name": "Preto",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        },
                        {
                            "id": "SIZE",
                            "name": "Tamanho",
                            "value_id": "11375277",
                            "value_name": "35 BR",
                            "values": [
                                {
                                    "id": "11375277",
                                    "name": "35 BR",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        }
                    ],
                    "sale_terms": [],
                    "picture_ids": [
                        "976201-MLB69861935884_062023",
                        "981550-MLB69861935880_062023",
                        "713278-MLB69861935872_062023",
                        "786578-MLB69861935886_062023",
                        "883350-MLB69861935882_062023",
                        "686451-MLB69861935874_062023",
                        "782048-MLB69861935876_062023",
                        "699008-MLB69861935878_062023"
                    ],
                    "catalog_product_id": "MLB18738739"
                },
                {
                    "id": 180243612297,
                    "price": 259.99,
                    "attribute_combinations": [
                        {
                            "id": "COLOR",
                            "name": "Cor",
                            "value_id": "52049",
                            "value_name": "Preto",
                            "values": [
                                {
                                    "id": "52049",
                                    "name": "Preto",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        },
                        {
                            "id": "SIZE",
                            "name": "Tamanho",
                            "value_id": "11375293",
                            "value_name": "36 BR",
                            "values": [
                                {
                                    "id": "11375293",
                                    "name": "36 BR",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        }
                    ],
                    "sale_terms": [],
                    "picture_ids": [
                        "976201-MLB69861935884_062023",
                        "981550-MLB69861935880_062023",
                        "713278-MLB69861935872_062023",
                        "786578-MLB69861935886_062023",
                        "883350-MLB69861935882_062023",
                        "686451-MLB69861935874_062023",
                        "782048-MLB69861935876_062023",
                        "699008-MLB69861935878_062023"
                    ],
                    "catalog_product_id": "MLB18738740"
                },
                {
                    "id": 180243612299,
                    "price": 259.99,
                    "attribute_combinations": [
                        {
                            "id": "COLOR",
                            "name": "Cor",
                            "value_id": "52049",
                            "value_name": "Preto",
                            "values": [
                                {
                                    "id": "52049",
                                    "name": "Preto",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        },
                        {
                            "id": "SIZE",
                            "name": "Tamanho",
                            "value_id": "11375309",
                            "value_name": "37 BR",
                            "values": [
                                {
                                    "id": "11375309",
                                    "name": "37 BR",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        }
                    ],
                    "sale_terms": [],
                    "picture_ids": [
                        "976201-MLB69861935884_062023",
                        "981550-MLB69861935880_062023",
                        "713278-MLB69861935872_062023",
                        "786578-MLB69861935886_062023",
                        "883350-MLB69861935882_062023",
                        "686451-MLB69861935874_062023",
                        "782048-MLB69861935876_062023",
                        "699008-MLB69861935878_062023"
                    ],
                    "catalog_product_id": "MLB18738716"
                },
                {
                    "id": 180243612301,
                    "price": 259.99,
                    "attribute_combinations": [
                        {
                            "id": "COLOR",
                            "name": "Cor",
                            "value_id": "52049",
                            "value_name": "Preto",
                            "values": [
                                {
                                    "id": "52049",
                                    "name": "Preto",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        },
                        {
                            "id": "SIZE",
                            "name": "Tamanho",
                            "value_id": "11375320",
                            "value_name": "38 BR",
                            "values": [
                                {
                                    "id": "11375320",
                                    "name": "38 BR",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        }
                    ],
                    "sale_terms": [],
                    "picture_ids": [
                        "976201-MLB69861935884_062023",
                        "981550-MLB69861935880_062023",
                        "713278-MLB69861935872_062023",
                        "786578-MLB69861935886_062023",
                        "883350-MLB69861935882_062023",
                        "686451-MLB69861935874_062023",
                        "782048-MLB69861935876_062023",
                        "699008-MLB69861935878_062023"
                    ],
                    "catalog_product_id": "MLB18738725"
                },
                {
                    "id": 180243612303,
                    "price": 259.99,
                    "attribute_combinations": [
                        {
                            "id": "COLOR",
                            "name": "Cor",
                            "value_id": "52049",
                            "value_name": "Preto",
                            "values": [
                                {
                                    "id": "52049",
                                    "name": "Preto",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        },
                        {
                            "id": "SIZE",
                            "name": "Tamanho",
                            "value_id": "11375325",
                            "value_name": "39 BR",
                            "values": [
                                {
                                    "id": "11375325",
                                    "name": "39 BR",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        }
                    ],
                    "sale_terms": [],
                    "picture_ids": [
                        "976201-MLB69861935884_062023",
                        "981550-MLB69861935880_062023",
                        "713278-MLB69861935872_062023",
                        "786578-MLB69861935886_062023",
                        "883350-MLB69861935882_062023",
                        "686451-MLB69861935874_062023",
                        "782048-MLB69861935876_062023",
                        "699008-MLB69861935878_062023"
                    ],
                    "catalog_product_id": "MLB18738738"
                },
                {
                    "id": 180243612305,
                    "price": 259.99,
                    "attribute_combinations": [
                        {
                            "id": "COLOR",
                            "name": "Cor",
                            "value_id": "52049",
                            "value_name": "Preto",
                            "values": [
                                {
                                    "id": "52049",
                                    "name": "Preto",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        },
                        {
                            "id": "SIZE",
                            "name": "Tamanho",
                            "value_id": "11375348",
                            "value_name": "40 BR",
                            "values": [
                                {
                                    "id": "11375348",
                                    "name": "40 BR",
                                    "struct": null
                                }
                            ],
                            "value_type": "string"
                        }
                    ],
                    "sale_terms": [],
                    "picture_ids": [
                        "976201-MLB69861935884_062023",
                        "981550-MLB69861935880_062023",
                        "713278-MLB69861935872_062023",
                        "786578-MLB69861935886_062023",
                        "883350-MLB69861935882_062023",
                        "686451-MLB69861935874_062023",
                        "782048-MLB69861935876_062023",
                        "699008-MLB69861935878_062023"
                    ],
                    "catalog_product_id": "MLB18738733"
                }
            ],
            "status": "active",
            "sub_status": [],
            "tags": [
                "good_quality_thumbnail",
                "certified_quality_thumbnail",
                "immediate_payment",
                "cart_eligible"
            ],
            "warranty": null,
            "catalog_product_id": "MLB18715726",
            "domain_id": "MLB-SNEAKERS",
            "parent_item_id": null,
            "deal_ids": [
                "MLB21890",
                "MLB20481",
                "MLB11767",
                "MLB19773",
                "MLB18520",
                "MLB15003",
                "MLB24526",
                "MLB22943",
                "MLB22941",
                "MLB24375",
                "MLB24376",
                "MLB24377",
                "MLB24372",
                "MLB23481",
                "MLB24373",
                "MLB24374",
                "MLB17047",
                "MLB16766",
                "MLB16288",
                "MLB20499",
                "MLB24379",
                "MLB22674",
                "MLB22676",
                "MLB23492",
                "MLB23493",
                "MLB24385",
                "MLB17055",
                "MLB30463",
                "MLB24380",
                "MLB14857",
                "MLB24381",
                "MLB14854",
                "MLB15264",
                "MLB23856",
                "MLB23857",
                "MLB22003",
                "MLB24225",
                "MLB24227",
                "MLB22963",
                "MLB24199",
                "MLB23860",
                "MLB18196",
                "MLB30352",
                "MLB18514",
                "MLB23908",
                "MLB18957",
                "MLB11890",
                "MLB22936",
                "MLB30472",
                "MLB15010",
                "MLB20477",
                "MLB22459",
                "MLB21684",
                "MLB24599",
                "MLB23862",
                "MLB21646",
                "MLB23864"
            ],
            "automatic_relist": false,
            "date_created": "2022-06-29T22:57:46.000Z",
            "last_updated": "2024-02-23T14:39:03.000Z",
            "health": 0.75,
            "catalog_listing": false
        }');

        $tipo = new MlbTipos('MLB-SNEAKERS');
        $call = (new MlbCallAttributes($tipo))->resource();
        $dados = $tipo->requiredAtrributes($call,$produto);
        $chart = new GeneratechartsSneakers();
        $newCharts = new Generatecharts("GRADE TENIS UNIVERSAL".uniqid('CHART'),'MLB-SNEAKERS',$dados,$chart->getMainAttribute(),$chart->getAttributesSneakers());
        $chart = $newCharts->requestChart('1272736385');

        $newCharts->insertDataResult($produto,$chart);

        // $this->getAttributesTrade($request);
    }

    public function getAttributesTrade(Request $request)
    {
        // ENDPOINT PARA REQUISICAO
        $endpoint = 'https://api.mercadolibre.com/items/'.$request->id;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $data = json_decode($response,true);

            if($request->categoria){
                // TROCAR A CATEGORIA
                $this->TrocarCategoriaRequest($data,FALSE,$data['id'],$request->categoria);
            }else{
                // CADASTRA UM NOVO ANUNCIO
                $this->refazerRequest($data);
            }

    }


    public function refazerRequest($data) {

        $endpoint = 'https://api.mercadolibre.com/items/MLB4325943254';

        $token = token::where('user_id', '38')->first();


            $data_json = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $json = json_decode($reponse);

            if ($httpCode == '200') {
                Log::notice("FEITO");
            } else {
                Log::notice(json_encode($json));
                Log::notice($data_json);
                try {
                    $domain = new getDomainController('12',$data['attributes']);
                    $concreto = new ConcretoDomainController($domain);
                    $concreto->CallAttributes($data);
                    $data_json = $concreto->CallErrorAttributes($json,$data);

                     $this->refazerRequest($data_json);
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }

            }

    }

    public function TrocarCategoriaRequest($data, $try = FALSE, $id,$categoria) {
        $ids = $id;
        $category = $categoria;
        // NUMERO DE TENTATIVAS
        $endpoint = 'https://api.mercadolibre.com/items/'. $ids;

        $token = token::where('user_id', '38')->first();

            if($try){
                $data_json = json_encode($data);
            }else{
                $data_json = json_encode(['category_id' => $categoria]);
                $try = TRUE;
            }


            print_r($data_json);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $json = json_decode($reponse);

            if ($httpCode == '200') {
                echo "<li class='list-group-item'>Alterado com Sucesso</li>";
                // Log::notice("FEITO");
            } else {
                echo "<li class='list-group-item'>Arrumando Erro..</li>";
                // Log::notice(json_encode($json));
                // Log::notice($data_json);
                try {
                    $domain = new getDomainController('12',$data['attributes']);
                    $concreto = new ConcretoDomainController($domain);
                    $concreto->CallAttributes($data);
                    $data_json = $concreto->CallErrorAttributes($json,$data,true,$category);

                    $this->TrocarCategoriaRequest($data_json,TRUE,$ids,$category);
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }

            }

    }
}
