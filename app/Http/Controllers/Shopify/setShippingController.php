<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use App\Models\ShippingUpdate;
use Illuminate\Http\Request;

abstract class setShippingController extends Controller
{
    private ShippingUpdate $shipping;
    private $data;


    public function __construct(ShippingUpdate $shipping, $data)
    {
        $this->shipping = $shipping;
        $this->data = $data;
    }

    abstract function setShipping();

    /**
     * Get the value of shipping
     */
    public function getShipping(): ShippingUpdate
    {
        return $this->shipping;
    }

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data;
    }
}
