<?php

namespace App\Http\Controllers\Yapay;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Cliente\implementacaoCliente;
use Illuminate\Http\Request;

abstract class AbstractTipoDeCobranca extends Controller
{
    private String $token_account;
    private String $type_address;
    private String $postal_code;
    private String $street;
    private String $number;
    private String $completion;
    private String $neighborhood;
    private String $city;
    private String $state;
    private String $name;
    private String $cpf;
    private String $email;
    private array $transaction_product = [];
    private String $payment_method_id; // USE 27 PARA PIX
    private String $split;

    public function __construct($token_account, $type_address, $postal_code, $street, $number, $completion, $neighborhood, $city, $state, $name, $cpf, $email, $transaction_product, $payment_method_id, $split)
    {
        $this->token_account = $token_account;
        $this->type_address = $type_address;
        $this->postal_code = $postal_code;
        $this->street = $street;
        $this->number = $number;
        $this->completion = $completion;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->state = $state;
        $this->name = $name;
        $this->cpf = $cpf;
        $this->email = $email;
        $this->transaction_product = $transaction_product;
        $this->payment_method_id = $payment_method_id;
        $this->split = $split;
    }

    abstract function CriarPagamento();
    abstract function EnviarOrdem(implementacaoCliente $data);

    /**
     * Get the value of token_account
     */
    public function getTokenAccount(): String
    {
        return $this->token_account;
    }

    /**
     * Set the value of token_account
     */
    public function setTokenAccount(String $token_account): self
    {
        $this->token_account = $token_account;

        return $this;
    }

    /**
     * Get the value of type_address
     */
    public function getTypeAddress(): String
    {
        return $this->type_address;
    }

    /**
     * Set the value of type_address
     */
    public function setTypeAddress(String $type_address): self
    {
        $this->type_address = $type_address;

        return $this;
    }

    /**
     * Get the value of postal_code
     */
    public function getPostalCode(): String
    {
        return $this->postal_code;
    }

    /**
     * Set the value of postal_code
     */
    public function setPostalCode(String $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    /**
     * Get the value of street
     */
    public function getStreet(): String
    {
        return $this->street;
    }

    /**
     * Set the value of street
     */
    public function setStreet(String $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get the value of number
     */
    public function getNumber(): String
    {
        return $this->number;
    }

    /**
     * Set the value of number
     */
    public function setNumber(String $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get the value of completion
     */
    public function getCompletion(): String
    {
        return $this->completion;
    }

    /**
     * Set the value of completion
     */
    public function setCompletion(String $completion): self
    {
        $this->completion = $completion;

        return $this;
    }

    /**
     * Get the value of neighborhood
     */
    public function getNeighborhood(): String
    {
        return $this->neighborhood;
    }

    /**
     * Set the value of neighborhood
     */
    public function setNeighborhood(String $neighborhood): self
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    /**
     * Get the value of city
     */
    public function getCity(): String
    {
        return $this->city;
    }

    /**
     * Set the value of city
     */
    public function setCity(String $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the value of state
     */
    public function getState(): String
    {
        return $this->state;
    }

    /**
     * Set the value of state
     */
    public function setState(String $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName(): String
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(String $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of cpf
     */
    public function getCpf(): String
    {
        return $this->cpf;
    }

    /**
     * Set the value of cpf
     */
    public function setCpf(String $cpf): self
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): String
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(String $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of transaction_product
     */
    public function getTransactionProduct(): array
    {
        return $this->transaction_product;
    }

    /**
     * Set the value of transaction_product
     */
    public function setTransactionProduct(array $transaction_product): self
    {
        $this->transaction_product = $transaction_product;

        return $this;
    }

    /**
     * Get the value of payment_method_id
     */
    public function getPaymentMethodId(): String
    {
        return $this->payment_method_id;
    }

    /**
     * Set the value of payment_method_id
     */
    public function setPaymentMethodId(String $payment_method_id): self
    {
        $this->payment_method_id = $payment_method_id;

        return $this;
    }

    /**
     * Get the value of split
     */
    public function getSplit(): String
    {
        return $this->split;
    }

    /**
     * Set the value of split
     */
    public function setSplit(String $split): self
    {
        $this->split = $split;

        return $this;
    }
}
