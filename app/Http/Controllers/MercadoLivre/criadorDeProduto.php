<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Double;

abstract class criadorDeProduto
{
    private criadorDeProduto $produtoInstance;
    private String $name;
    private String $tipo_anuncio;
    private String $price;
    private String $id_categoria;
    private int $id_product;
    private array $erros;
    private String $id;
    private ?String $descricao;
    private array $dados;

    public function __construct($name,$tipo_anuncio,$price,$id_categoria,$id_product,$id,$descricao,$dados)
    {
        $this->name = $name;
        $this->tipo_anuncio = $tipo_anuncio;
        $this->price = $price;
        $this->id_categoria = $id_categoria;
        $this->id_product = $id_product;
        $this->id = $id;
        $this->descricao = $descricao;
        $this->dados = $dados;
    }


    public function cadastrarMercadoLivre(){
        $this->getProduto()->integrar();
    }

    public abstract function getProduto();
    public abstract function getErrosFunction(array $data);

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

    /**
     * Get the value of id_product
     */
    public function getIdProduct(): int
    {
        return $this->id_product;
    }

    /**
     * Set the value of id_product
     */
    public function setIdProduct(int $id_product): self
    {
        $this->id_product = $id_product;

        return $this;
    }

    /**
     * Get the value of erros
     */
    public function getErros(): array
    {
        return $this->erros;
    }

    /**
     * Set the value of erros
     */
    public function setErros(array $erros): self
    {
        $this->erros = $erros;

        return $this;
    }

    /**
     * Get the value of produtoInstance
     */
    public function getProdutoInstance(): criadorDeProduto
    {
        return $this->produtoInstance;
    }

    /**
     * Set the value of produtoInstance
     */
    public function setProdutoInstance(criadorDeProduto $produtoInstance): self
    {
        $this->produtoInstance = $produtoInstance;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId(): String
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(String $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of descricao
     */
    public function getDescricao(): ?String
    {
        return $this->descricao;
    }

    /**
     * Get the value of dados
     */
    public function getDados(): array
    {
        return $this->dados;
    }
}
