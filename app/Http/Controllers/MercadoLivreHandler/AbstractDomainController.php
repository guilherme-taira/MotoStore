<?php

namespace App\Http\Controllers\MercadoLivreHandler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class AbstractDomainController {

    private getDomainController $domain_id;

    public function __construct(getDomainController $domain_id)
    {
        $this->domain_id = $domain_id;
    }

    /**
     * Set the value of domain_id
     */
    public function setDomainId(getDomainController $domain_id): self
    {
        $this->domain_id = $domain_id;

        return $this;
    }

    public function CallAttributes($attributes){
         $this->domain_id->handlerAttributes($attributes);
    }


    public function CallErrorAttributes($error,$produto,$categoria,$category_id){
        return $this->domain_id->HandlerError($error,$produto,$categoria,$category_id);
   }


    /**
     * Get the value of domain_id
     */
    public function getDomainId(): getDomainController
    {
        return $this->domain_id;
    }
}
