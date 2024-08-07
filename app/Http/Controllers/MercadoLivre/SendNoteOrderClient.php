<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class SendNoteOrderClient extends Controller
{
    private $numeroPedido;
    private $note;
    private $user_id;


    public function __construct($numeroPedido,$note,$user_id)
    {
        $this->numeroPedido = $numeroPedido;
        $this->note = $note;
        $this->user_id = $user_id;
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
}
