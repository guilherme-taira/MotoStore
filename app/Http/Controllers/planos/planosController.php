<?php

namespace App\Http\Controllers\planos;

use App\Http\Controllers\Controller;
use App\Models\logo;
use App\Models\sub_category;
use App\Models\categorias;
use Illuminate\Http\Request;

class planosController extends Controller
{
    public function index(){

        $viewData = [];
        $viewData["title"] = "Planos";
        $viewData['logo'] = logo::first();

        $categorias = [];
        // // foreach (categorias::all() as $value) {
        // //     $categorias[$value->id] = [
        // //         "nome" => $value->nome,
        // //         "subcategory" => sub_category::getAllCategory($value->id),
        // //     ];
        // // }
        // $viewData['categorias'] = $categorias;

        return view("planos.index",[
            "viewData" => $viewData
        ]);
    }
}
