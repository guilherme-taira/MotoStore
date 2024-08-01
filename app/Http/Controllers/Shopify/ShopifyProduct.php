<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopifyProduct extends Controller
{
    private $produtos;
    private $loja;

    public function __construct($produtos,$loja)
    {
        $this->produtos = $produtos;
        $this->loja = $loja;
    }

    /**
     * Get the value of produtos
     */
    public function getProdutos()
    {
        return $this->produtos;
    }

    public function get($resource)
    {


        $idsArray = array_map('trim', explode(',', $this->getProdutos()));
        $variants = [];

        // foreach ($idsArray as $id) {
        // URL PARA REQUISICAO
        $endpoint = $this->getLoja()->name_loja . $resource.$this->produtos.".json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $this->toJson());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "X-Shopify-Access-Token: {$this->getLoja()->token}"]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = json_decode($response,true);
        return $res;
        // curl_close($ch);

        //     if (isset($res['variant'])) {
        //         $variants[] = $res['variant'];
        //     }
        // }


        // usort($variants, function($a, $b) {
        //     if ($a['price'] == $b['price']) {
        //         return $b['inventory_quantity'] - $a['inventory_quantity'];
        //     }
        //     return $a['price'] - $b['price'];
        // });

        // $cheapestWithStock = null;
        // $allVariantsSorted = [];

        // foreach ($variants as $variant) {
        //     if ($variant['inventory_quantity'] > 0 && $cheapestWithStock === null) {
        //         $cheapestWithStock = $variant;
        //     }else{
        //         $allVariantsSorted[] = $variant;
        //     }
        // }

        // return [
        //     '1' => $cheapestWithStock,
        //     'opcoes' => $allVariantsSorted
        // ];
    }


    public function resource()
    {

        return $this->get("variants/");
    }


    /**
     * Get the value of loja
     */
    public function getLoja()
    {
        return $this->loja;
    }
}
