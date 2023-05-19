<?php

namespace App\Http\Controllers\MercadoLivre\Job;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class getProductDataController extends Controller
{
    public function getProduct($id)
    {
        // ENDPOINT PARA REQUISICAO
        $endpoint = 'https://api.mercadolibre.com/items/' . $id;

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $dados = json_decode($response);
            echo "<pre>";
            if ($httpcode == '200') {
                if ($dados->available_quantity < 5) {
                    print_r(['code' => $httpcode, 'ID' => $id]);
                    Products::where('id_mercadolivre', $id)->update(['available_quantity' => $dados->initial_quantity, 'price' => $dados->price, 'isPublic' => 0]);
                } else {
                    print_r(['code' => $httpcode, 'ID' => $id]);
                    Products::where('id_mercadolivre', $id)->update(['available_quantity' => $dados->initial_quantity, 'price' => $dados->price]);
                }
            } else {
                echo $httpcode . $id;
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
