<?php

namespace App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class handlerChartSweatShirtsSandHoodies extends BaseHandler
{
       public function PodeManipular($request):bool {
        if($request->domain == 'MLB-SWEATSHIRTS_AND_HOODIES'){
            return true;
        }
        return false;
    }

    public function processar($request){
        try {
            $chartCreator = new MeasurementChartSweatShirtsSandHoodies($request);
            $result = $chartCreator->createSweatShirtsSandHoodies();
            return $result;
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
