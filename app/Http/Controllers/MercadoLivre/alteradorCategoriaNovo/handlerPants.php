<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class handlerPants extends BaseHandler
{
    public function PodeManipular($request):bool {
        if($request->domain == 'MLB-PANTS'){
            return true;
        }
        return false;
    }

    public function processar($request){
        try {
            $chartCreator = new MeasurementChartPantsCreator($request);
            $result = $chartCreator->createPantsSizeChart();
            return $result;
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
