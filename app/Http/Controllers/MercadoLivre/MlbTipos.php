<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use stdClass;

class MlbTipos extends Controller
{
    private $tipo;


    public function __construct($tipo)
    {
        $this->tipo = $tipo;
    }


    public function requiredAtrributes($data,$produto){
        echo "<pre>";
        $dados = [];
        $genero = [];
        if(is_object($data)){
            foreach ($produto->attributes as $attr) {
                if($attr->id == "GENDER"){
                    $genero = $attr;
                }
            }

            foreach ($data as $attribute) {
                foreach ($attribute->groups as $group) {
                    foreach ($group->components as $component) {
                        foreach ($component->attributes as $attribut) {
                            foreach ($attribut->tags as $tag) {
                                if($tag == "grid_template_required"){
                                    foreach ($attribut->values as $key => $attrRequired) {
                                        if($genero->values[0]->id == $attrRequired->id){
                                            $dados['attributes'] = $attribut;
                                            $dados['attributes']->values = [$genero->values[0]];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $dados;
    }
    /**
     * Get the value of tipo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     */
    public function setTipo($tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}
