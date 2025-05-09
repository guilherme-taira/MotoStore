<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class handlerBras extends BaseHandler
{
    public function PodeManipular($request):bool {
        if($request->domain == 'MLB-BRAS'){
            return true;
        }
        return false;
    }


    public function processar($request){
        try {
            $chartCreator = new MeasurementChartBrasCreator($request);
            $result = $chartCreator->createBrasSizeChart();

            return $result;
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
