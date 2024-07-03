<?php

namespace App\Http\Controllers\Mercadopago\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Unique;
use App\Http\Controllers\MercadoPago\Pagamento\MercadoPagoCesta;

abstract class AbstractMercadoPagoPreference
{
    private $externalPreference;
    private $itens;
    private $notificationUrl;
    private $userid;


    public function __construct(MercadoPagoCesta $itens,$notificationUrl,$userid)
    {

        $this->externalPreference = uniqid('afilidrop');
        $this->itens = $itens;
        $this->notificationUrl = $notificationUrl;
        $this->userid = $userid;
    }


    abstract function toJson();
    abstract function resource();
    abstract function get($resource);
    /**
     * Get the value of externalPreference
     */
    public function getExternalPreference()
    {
        return $this->externalPreference;
    }

    /**
     * Set the value of externalPreference
     */
    public function setExternalPreference($externalPreference): self
    {
        $this->externalPreference = $externalPreference;

        return $this;
    }

    /**
     * Get the value of itens
     */
    public function getItens()
    {
        return $this->itens;
    }

    /**
     * Set the value of itens
     */
    public function setItens($itens): self
    {
        $this->itens = $itens;

        return $this;
    }


    /**
     * Get the value of notificationUrl
     */
    public function getNotificationUrl()
    {
        return $this->notificationUrl;
    }

    /**
     * Set the value of notificationUrl
     */
    public function setNotificationUrl($notificationUrl): self
    {
        $this->notificationUrl = $notificationUrl;

        return $this;
    }

    /**
     * Get the value of userid
     */
    public function getUserid()
    {
        return $this->userid;
    }
}
