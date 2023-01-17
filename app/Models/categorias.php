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
            "nome" => "required|min:3",
            "slug" => "required|min:3",
            "descricao" => "required|min:3",
        ]);
    }

    public function getId(){
        return $this->id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function SetNome($nome){
        $this->nome = $nome;
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
