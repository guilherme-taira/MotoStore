<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryTest extends Controller
{
    public function index(){
        $viewData = [];
        $viewData["title"] = "Alterador de Categoria";
        $viewData['auth'] = Auth::user()->name;

        return view('mercadolivre.index',[
            "viewData" => $viewData
        ]);
    }

    public function categoria(){
        $viewData = [];
        $viewData["title"] = "Alterador de Categoria";
        $viewData['auth'] = Auth::user()->name;

        return view('mercadolivre.categoria',[
            "viewData" => $viewData
        ]);
    }
}
