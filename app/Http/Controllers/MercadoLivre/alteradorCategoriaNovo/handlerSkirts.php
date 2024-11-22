<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class handlerSkirts extends Controller
{
    public function PodeManipular($request):bool {
        if($request->domain == 'MLB-SKIRTS'){
            return true;
        }
        return false;
    }

    public function processar($request){
        try {
            $chartCreator = new MeasurementChartSkirtsCreator($request);
            $result = $chartCreator->createSkirtsSizeChart();
            return $result;
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
