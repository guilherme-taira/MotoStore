<?php

namespace App\Http\Controllers\MercadoPago\Bridge;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

abstract class AbstractPagamento
{
    protected InterfacePagamento $tipopagamento;
    protected float $valor;

    public function __construct(float $valor)
    {
        $this->valor = $valor;
    }

    public function setTipoPagamento(InterfacePagamento $tipopagamento){
        $this->tipopagamento = $tipopagamento;
    }

    public function gerarPagamento(){
        if($this->tipopagamento){
            return $this->_gerar();
        }else{
            new Exception("Nenhum Método Encontrado");
        }
    }

    protected abstract function _gerar();


    /**
     * Get the value of valor
     */
    public function getValor(): float
    {
        return $this->valor;
    }

    /**
     * Set the value of valor
     */
    public function setValor(float $valor): self
    {
        $this->valor = $valor;

        return $this;
    }
}

