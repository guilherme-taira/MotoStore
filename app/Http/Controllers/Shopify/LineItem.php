<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LineItem extends Controller
{
    public $variant_id;
    public $quantity;

    public function __construct($variant_id, $quantity)
    {
        $this->variant_id = $variant_id;
        $this->quantity = $quantity;
    }
}
