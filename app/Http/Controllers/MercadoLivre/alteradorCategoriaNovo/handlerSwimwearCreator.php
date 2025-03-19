<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class handlerSwimwearCreator extends BaseHandler
{
    public function PodeManipular($request):bool {
        if($request->domain == 'MLB-SWIMWEAR'){
            return true;
        }
        return false;
    }

    public function processar($request){
        try {
            $chartCreator = new MeasurementChartSwimwear($request);
            $result = $chartCreator->createSwimwearSizeChart();
            return $result;
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
