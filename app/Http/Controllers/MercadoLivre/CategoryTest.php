<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryTest extends Controller
{
    public function index(){
        $viewData = [];
        $viewData["title"] = "Alterador de Categoria";

        return view('mercadolivre.index',[
            "viewData" => $viewData
        ]);
    }
}
