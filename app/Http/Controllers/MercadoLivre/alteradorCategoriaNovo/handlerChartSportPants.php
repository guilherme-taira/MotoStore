<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class handlerChartSportPants extends BaseHandler
{
    public function PodeManipular($request):bool {
        if($request->domain == 'MLB-SPORT_PANTS'){
            return true;
        }
        return false;
    }

    public function processar($request){
        try {
            $chartCreator = new MeasurementChartSportPants($request);
            $result = $chartCreator->createSportPantsChart();
            return $result;
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
