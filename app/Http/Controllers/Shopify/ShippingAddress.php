<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippingAddress extends Controller
{
    public $first_name;
    public $address1;
    public $phone;
    public $city;
    public $zip;
    public $province;
    public $country;
    public $last_name;
    public $address2;
    public $company;
    public $name;
    public $country_code;
    public $province_code;
    public $note;

    public function __construct($first_name, $address1, $phone, $city, $zip, $province, $country, $last_name, $address2, $company, $name, $country_code, $province_code)
    {
        $this->first_name = $first_name;
        $this->address1 = $address1;
        $this->phone = $phone;
        $this->city = $city;
        $this->zip = $zip;
        $this->province = $province;
        $this->country = $country;
        $this->last_name = $last_name;
        $this->address2 = $address2;
        $this->company = $company;
        $this->name = $name;
        $this->country_code = $country_code;
        $this->province_code = $province_code;
    }
}
