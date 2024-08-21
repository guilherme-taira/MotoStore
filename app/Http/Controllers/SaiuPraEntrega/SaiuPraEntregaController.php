<?php

namespace App\Http\Controllers\SaiuPraEntrega;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class SaiuPraEntregaController extends Controller
{
    private $shippingID;
    private $traking;
    private $message;
    private $idMercadoLivre;
    private $seller;
    private $client;

    public function __construct($shippingID,$traking,$message,$idMercadoLivre,$seller,$client)
    {
        $this->shippingID = $shippingID;
        $this->traking = $traking;
        $this->message = $message;
        $this->idMercadoLivre = $idMercadoLivre;
        $this->seller = $seller;
        $this->client = $client;
    }

    abstract function save();
    abstract function saveMessage();
    abstract function notifyClient();


    /**
     * Get the value of traking
     */
    public function getTraking()
    {
        return $this->traking;
    }

    /**
     * Get the value of message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get the value of idMercadoLivre
     */
    public function getIdMercadoLivre()
    {
        return $this->idMercadoLivre;
    }

    /**
     * Get the value of seller
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Get the value of client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get the value of shippingID
     */
    public function getShippingID()
    {
        return $this->shippingID;
    }
}
