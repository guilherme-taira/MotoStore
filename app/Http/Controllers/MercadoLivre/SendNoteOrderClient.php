<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class SendNoteOrderClient extends Controller
{
    private $numeroPedido;
    private $note;
    private $user_id;
    private $id;
    private $id_meli;


    public function __construct($numeroPedido,$note,$user_id,$id,$id_meli = null)
    {
        $this->numeroPedido = $numeroPedido;
        $this->note = $note;
        $this->user_id = $user_id;
        $this->id = $id;
        $this->id_meli = $id_meli;
    }
    /**
     * Get the value of numeroPedido
     */
    public function getNumeroPedido()
    {
        return $this->numeroPedido;
    }

    /**
     * Get the value of note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Get the value of token
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of id_meli
     */
    public function getIdMeli()
    {
        return $this->id_meli;
    }
}
