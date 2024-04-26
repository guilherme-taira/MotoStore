<?php

namespace App\Http\Controllers\Mercadopago\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Unique;

abstract class AbstractMercadoPagoPreference
{
    private $externalPreference;
    private $itens;
    private $notificationUrl;


    public function __construct(MercadoPagoCesta $itens,$notificationUrl)
    {

        $this->externalPreference = uniqid('afilidrop');
        $this->itens = $itens;
        $this->notificationUrl = $notificationUrl;
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
}
