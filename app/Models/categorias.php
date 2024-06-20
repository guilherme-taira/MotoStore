<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categorias extends Model
{
    use HasFactory;

    protected $table = 'categorias';


    public static function validate($request)
    {
        $request->validate([
            "name" => "required|min:3",
            "slug" => "required|min:3",
            "descricao" => "required|min:3",
        ]);
    }

    public function getId(){
        return $this->id;
    }

    public function getNome(){
        return $this->name;
    }

    public function SetNome($name){
        $this->name = $name;
    }

    public function SetNumber($number){
        $this->number = $number;
    }


    public function getSlug(){
        return $this->slug;
    }

    public function SetSlug($slug){
        $this->slug = $slug;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function SetDescricao($descricao){
        $this->descricao = $descricao;
    }

}
