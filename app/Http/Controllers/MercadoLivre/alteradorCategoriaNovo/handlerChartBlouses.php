<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class handlerChartBlouses extends BaseHandler
{
     public function PodeManipular($request):bool {
        if($request->domain == 'MLB-BLOUSES'){
            return true;
        }
        return false;
    }

    public function processar($request){
        try {
            $chartCreator = new MeasurementChartBlouses($request);
            $result = $chartCreator->createBlousesSizeChart();
            return $result;
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
