<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class handlerDresses extends BaseHandler{


    public function PodeManipular($request):bool {
        if($request->domain == 'MLB-DRESSES'){
            return true;
        }
        return false;
    }

    public function processar($request){
        // Exemplo de uso
        try {
            $chartCreator = new MeasurementChartCreator($request);
            $result = $chartCreator->createDressSizeChart();
            return $result;
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

}
