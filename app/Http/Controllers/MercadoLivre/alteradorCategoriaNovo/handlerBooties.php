<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class handlerBooties extends BaseHandler
{
    public function PodeManipular($request):bool {
        if($request->domain == 'MLB-BOOTS_AND_BOOTIES'){
            return true;
        }
        return false;
    }

    public function processar($request){
        try {
            $chartCreator = new MeasurementChartBootiesCreator($request);
            $result = $chartCreator->createBootiesSizeChart();
            return $result;
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
