<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class chartsController extends Controller
{
    private $names;
    private $domain_id;
    private $site_id;
    private $attributes;
    private $main_attribute;
    private $rows;

    public function __construct($names,$domain_id, $attributes,$main_attribute,$rows)
    {
        $this->names = $names;
        $this->domain_id = $domain_id;
        $this->attributes = $attributes;
        $this->main_attribute = $main_attribute;
        $this->rows = $rows;
    }

    abstract function brokeDomain();

    abstract function generateCharts();

    abstract function requestChart($user);

    abstract function handleResult($response);

    abstract function insertDataResult($produto,$data);

    /**
     * Get the value of names
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * Set the value of names
     */
    public function setNames(array $names): self
    {
        $this->names = $names;

        return $this;
    }

    /**
     * Get the value of domain_id
     */
    public function getDomainId()
    {
        return $this->domain_id;
    }

    /**
     * Set the value of domain_id
     */
    public function setDomainId($domain_id): self
    {
        $this->domain_id = $domain_id;

        return $this;
    }

    /**
     * Get the value of site_id
     */
    public function getSiteId()
    {
        return $this->site_id;
    }

    /**
     * Set the value of site_id
     */
    public function setSiteId($site_id): self
    {
        $this->site_id = $site_id;

        return $this;
    }

    /**
     * Get the value of attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set the value of attributes
     */
    public function setAttributes($attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get the value of main_attribute
     */
    public function getMainAttribute()
    {
        return $this->main_attribute;
    }


    /**
     * Get the value of rows
     */
    public function getRows()
    {
        return $this->rows;
    }
}
