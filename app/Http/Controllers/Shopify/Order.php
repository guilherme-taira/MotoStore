<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Order extends Controller
{
    public $line_items;
    public $financial_status;
    public $currency;
    public $shipping_address;
    public $note;
    public $email;

    public function __construct($line_items, $financial_status, $currency, $shipping_address,$note,$email)
    {
        $this->line_items = $line_items;
        $this->financial_status = $financial_status;
        $this->currency = $currency;
        $this->shipping_address = $shipping_address;
        $this->note = $note;
        $this->email = $email;
    }
}
