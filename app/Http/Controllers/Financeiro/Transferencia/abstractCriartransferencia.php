<?php

namespace App\Http\Controllers\Financeiro\Transferencia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class abstractCriartransferencia
{
    private $idPagador;
    private $paymentId;
    private $amount;

    public function __construct($idPagador,$paymentId,$amount)
    {
        $this->idPagador = $idPagador;
        $this->paymentId = $paymentId;
        $this->amount = $amount;
    }

    abstract function chamarTransferencia();

    /**
     * Get the value of idPagador
     */
    public function getIdPagador()
    {
        return $this->idPagador;
    }

    /**
     * Get the value of paymentId
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * Get the value of amount
     */
    public function getAmount()
    {
        return $this->amount;
    }
}



