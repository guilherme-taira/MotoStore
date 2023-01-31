<?php

namespace App\Http\Controllers\GenerateCode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class RequestCodeMercadoLivre extends Controller
{
    private String $grant_type;
    private String $client_id;
    private String $client_secret;
    private String $code;
    private String $redirect_uri;
    private String $id;

    public function __construct($grant_type,$client_id,$client_secret,$code,$redirect_uri,$id)
    {
        $this->grant_type = $grant_type;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->code = $code;
        $this->redirect_uri = $redirect_uri;
        $this->id = $id;
    }

    public function getGrantType(){
        return $this->grant_type;
    }

    public function getClientId(){
        return $this->client_id;
    }

    public function getClientSecret(){
        return $this->client_secret;
    }
    public function getCode(){
        return $this->code;
    }

    public function getRedirectUri(){
        return $this->redirect_uri;
    }

    public function getUserId(){
        return $this->id;
    }

    abstract function saveCode($codeReturn);
}

