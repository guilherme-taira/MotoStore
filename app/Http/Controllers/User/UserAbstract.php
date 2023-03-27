<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class UserAbstract
{
    private String $name;
    private String $phone;
    private String $email;
    private String $cpf;
    private String $cnpj;
    private String $endereco;
    private String $complemento;
    private String $numero;
    private String $bairro;
    private String $cidade;
    private String $uf;
    private String $countryId = "BR";
    private String $cep;

    public function __construct($name, $phone, $email, $cpf, $cnpj, $endereco, $complemento, $numero, $bairro, $cidade, $uf, $countryId, $cep)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->cpf = $cpf;
        $this->cnpj = $cnpj;
        $this->endereco = $endereco;
        $this->complemento = $complemento;
        $this->numero = $numero;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->uf = $uf;
        $this->countryId = $countryId;
        $this->cep = $cep;
    }

    abstract function criarCliente();

    /**
     * Get the value of name
     */
    public function getName(): String
    {
        return $this->name;
    }

    /**
     * Get the value of phone
     */
    public function getPhone(): String
    {
        return $this->phone;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): String
    {
        return $this->email;
    }

    /**
     * Get the value of cpf
     */
    public function getCpf(): String
    {
        return $this->cpf;
    }

    /**
     * Get the value of cnpj
     */
    public function getCnpj(): String
    {
        return $this->cnpj;
    }

    /**
     * Get the value of endereco
     */
    public function getEndereco(): String
    {
        return $this->endereco;
    }

    /**
     * Get the value of complemento
     */
    public function getComplemento(): String
    {
        return $this->complemento;
    }

    /**
     * Get the value of numero
     */
    public function getNumero(): String
    {
        return $this->numero;
    }

    /**
     * Get the value of bairro
     */
    public function getBairro(): String
    {
        return $this->bairro;
    }

    /**
     * Get the value of cidade
     */
    public function getCidade(): String
    {
        return $this->cidade;
    }

    /**
     * Get the value of uf
     */
    public function getUf(): String
    {
        return $this->uf;
    }

    /**
     * Get the value of countryId
     */
    public function getCountryId(): String
    {
        return $this->countryId;
    }

    /**
     * Get the value of cep
     */
    public function getCep(): String
    {
        return $this->cep;
    }
}
