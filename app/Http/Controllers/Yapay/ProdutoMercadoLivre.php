<?php

namespace App\Http\Controllers\Yapay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProdutoMercadoLivre extends Controller
{
    public String $descricao;
    public String $quantidade;
    public String $valor;

    public function __construct($descricao, $quantidade, $valor)
    {
        $this->descricao = $descricao;
        $this->quantidade = $quantidade;
        $this->valor = $valor;
    }

    /**
     * Get the value of descricao
     */
    public function getDescricao(): String
    {
        return $this->descricao;
    }

    /**
     * Set the value of descricao
     */
    public function setDescricao(String $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get the value of quantidade
     */
    public function getQuantidade(): String
    {
        return $this->quantidade;
    }

    /**
     * Set the value of quantidade
     */
    public function setQuantidade(String $quantidade): self
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Get the value of valor
     */
    public function getValor(): String
    {
        return $this->valor;
    }

    /**
     * Set the value of valor
     */
    public function setValor(String $valor): self
    {
        $this->valor = $valor;

        return $this;
    }
}
