<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Double;

abstract class criadorDeProduto
{
    private String $name;
    private String $tipo_anuncio;
    private String $price;
    private String $id_categoria;

    public function __construct($name,$tipo_anuncio,$price,$id_categoria)
    {
        $this->name = $name;
        $this->tipo_anuncio = $tipo_anuncio;
        $this->price = $price;
        $this->id_categoria = $id_categoria;
    }



    public function cadastrarMercadoLivre(){
        $this->getProduto()->integrar();
    }

    public abstract function getProduto(): ProdutoConcreto;

    /**
     * Get the value of name
     */
    public function getName(): String
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(String $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of tipo_anuncio
     */
    public function getTipoAnuncio(): String
    {
        return $this->tipo_anuncio;
    }

    /**
     * Set the value of tipo_anuncio
     */
    public function setTipoAnuncio(String $tipo_anuncio): self
    {
        $this->tipo_anuncio = $tipo_anuncio;

        return $this;
    }

    /**
     * Get the value of price
     */
    public function getPrice(): String
    {
        return $this->price;
    }

    /**
     * Set the value of price
     */
    public function setPrice(String $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of id_categoria
     */
    public function getIdCategoria(): String
    {
        return $this->id_categoria;
    }

    /**
     * Set the value of id_categoria
     */
    public function setIdCategoria(String $id_categoria): self
    {
        $this->id_categoria = $id_categoria;

        return $this;
    }
}
