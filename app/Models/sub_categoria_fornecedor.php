<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class sub_categoria_fornecedor extends Model
{
    use HasFactory;

    protected $table = "sub_categoria_fornecedor";

    public static function getAllSubCategoria(){
        $data = sub_categoria_fornecedor::paginate(10);
        return $data;
    }

    public static function getAllCategory($id){

        $data = DB::table('categorias_forncedores')
        ->join('sub_categoria_fornecedor', 'categorias_forncedores.id', '=', 'sub_categoria_fornecedor.categoria_principal')
        ->where('categoria_principal',$id)
        ->select('*',)->get();
        return $data;
    }
}
