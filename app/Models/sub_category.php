<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class sub_category extends Model
{
    use HasFactory;

    protected $table = 'sub_category';

    public static function getAllCategory($id){

        $data = DB::table('categorias')
        ->join('sub_category', 'categorias.id', '=', 'sub_category.id_categoria')
        ->where('id_categoria',$id)
        ->select('*',)->get();
        return $data;
    }

    public static function getName($id){
        $data = sub_category::where('id',$id)->first();
        return $data->name;
    }
}
