<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categorias_forncedores extends Model
{
    use HasFactory;

    protected $table = "categorias_forncedores";

    public static function getAllCategoria(){
        $data = categorias_forncedores::paginate(10);
        return $data;
    }
}
