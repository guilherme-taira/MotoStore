<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class handlerSlippers extends BaseHandler
{
public function PodeManipular($request):bool {
        if($request->domain == 'MLB-SLIPPERS'){
            return true;
        }
        return false;
    }


    public function processar($request){
        try {
            $chartCreator = new MeasurementChartSlippersCreator($request);
            $result = $chartCreator->createSlippersSizeChart();

            return $result;
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
