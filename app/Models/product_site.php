<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_site extends Model
{
    use HasFactory;

    protected $table = "product_site";

    public static function getVerifyProduct($produto):bool{
        // $pedido->item->seller_sku
        $data = product_site::where('id',$produto)->first();
        if($data){
            return true;
        }else{
            return false;
        }
    }



}
