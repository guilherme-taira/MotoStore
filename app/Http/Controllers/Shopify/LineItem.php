<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LineItem extends Controller
{
    public $variant_id;
    public $quantity;
    public $title = null;
    public $price = null;

    public function __construct($variant_id,$quantity,$title = null,$price = null)
    {

        if($variant_id == "ND"){
            $this->title = "produto não Integrado";
            $this->price = "10";
        }else{
            $this->variant_id = $variant_id;
        }
        $this->quantity = $quantity;
    }
}
