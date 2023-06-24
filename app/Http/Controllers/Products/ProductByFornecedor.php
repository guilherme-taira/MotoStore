<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\categorias;
use App\Models\categorias_forncedores;
use App\Models\logo;
use App\Models\sub_categoria_fornecedor;
use App\Models\sub_category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductByFornecedor extends Controller
{
    public function getProductsByFornecedor(Request $request,$id){

        $title = sub_categoria_fornecedor::where('id',$id)->first();
        $viewData = [];
        $viewData['title'] = "Produtos de Fornecedores";
        $viewData['subtitle'] = "Fornecedores : " .  $title->name;
        $viewData['products'] =  User::getProductByFornecedor($id);
        $viewData['logo'] = logo::first();

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $subcategorias = [];

        foreach (categorias_forncedores::all() as $value) {

            $subcategorias[$value->id] = [
                "nome" => $value->name,
                "subcategory" => sub_categoria_fornecedor::getAllCategory($value->id),
            ];
        }

        $viewData['subcategorias'] = $subcategorias;
        $viewData['categorias'] = $categorias;

        if(Auth::user()->user_subcategory == $id){
        $viewData['bloqueado'] = 1;
       }else{
        $viewData['bloqueado'] = 0;
       }

       return view('store.index')->with('viewData', $viewData);

    }
}
