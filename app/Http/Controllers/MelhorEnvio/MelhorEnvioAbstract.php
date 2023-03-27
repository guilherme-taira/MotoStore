<?php

namespace App\Http\Controllers\MelhorEnvio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class MelhorEnvioAbstract
{
    // POSTAL CODE
    private String $postal_code_remetente;
    private String $postal_code_destino;
    // ARRAY DE PRODUTOS
    private array $products;
    // ESTRUTURA DO ARRAY
    // "id": "x",
    // "width": 11,
    // "height": 17,
    // "length": 11,
    // "weight": 0.3,
    // "insurance_value": 10.1,
    // "quantity": 1

    public function __construct($postal_code_remetente,$postal_code_destino,$products)
    {
        $this->postal_code_remetente = $postal_code_remetente;
        $this->postal_code_destino = $postal_code_destino;
        $this->products = $products;
    }

    abstract function pegarFrete();
    abstract function criarProdutos();


    /**
     * Get the value of postal_code_remetente
     */
    public function getPostalCodeRemetente(): String
    {
        return $this->postal_code_remetente;
    }

    /**
     * Set the value of postal_code_remetente
     */
    public function setPostalCodeRemetente(String $postal_code_remetente): self
    {
        $this->postal_code_remetente = $postal_code_remetente;

        return $this;
    }

    /**
     * Get the value of postal_code_destino
     */
    public function getPostalCodeDestino(): String
    {
        return $this->postal_code_destino;
    }

    /**
     * Set the value of postal_code_destino
     */
    public function setPostalCodeDestino(String $postal_code_destino): self
    {
        $this->postal_code_destino = $postal_code_destino;

        return $this;
    }

    /**
     * Get the value of products
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Set the value of products
     */
    public function setProducts(array $products): self
    {
        $this->products = $products;

        return $this;
    }
}
