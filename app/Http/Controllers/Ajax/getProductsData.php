<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class getProductsData extends Controller
{
    public function infoSearch(Request $request){
        $data = json_encode(Products::where('name','LIKE','%'.$request->name.'%')->get());
        if($data){
            return response()->json(['dados' => $data],200);
        }
        return response()->json('Error: Não Há Produtos Com esse Nome',404);
    }
}
