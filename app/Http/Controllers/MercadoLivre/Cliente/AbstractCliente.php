<?php

namespace App\Http\Controllers\MercadoLivre\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NunoMaduro\Collision\Adapters\Phpunit\State;

abstract class AbstractCliente extends Controller
{
    // DADOS PESSOAIS
    private String $firstName;
    private String $email;
    private String $documento;
    // ENDEREÇO
    private String $address;
    private String $city;
    private String $state;
    private String $zip_code;
    // FOTO
    // private String $thumbnail;

    public function __construct($firstName, $email, $documento, $address, $city, $state, $zip_code)
    {
        $this->firstName = $firstName;
        $this->email = $email;
        $this->documento = $documento;
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->zip_code = $zip_code;
        //$this->thumbnail = $thumbnail;
    }
    abstract function VerifyIfExist($id): bool;
    abstract function createPayment();

    /**
     * Get the value of firstName
     */
    public function getFirstName(): String
    {
        return $this->firstName;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): String
    {
        return $this->email;
    }

    /**
     * Get the value of documento
     */
    public function getDocumento(): String
    {
        return $this->documento;
    }

    /**
     * Get the value of address
     */
    public function getAddress(): String
    {
        return $this->address;
    }

    /**
     * Get the value of city
     */
    public function getCity(): String
    {
        return $this->city;
    }

    /**
     * Get the value of state
     */
    public function getState(): String
    {
        return $this->state;
    }

    /**
     * Get the value of zip_code
     */
    public function getZipCode(): String
    {
        return $this->zip_code;
    }

    /**
     * Get the value of thumbnail
     */
    // public function getThumbnail(): String
    // {
    //     return $this->thumbnail;
    // }
}
