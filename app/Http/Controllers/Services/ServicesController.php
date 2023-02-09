<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public static function RegexPrice($preco){
        if($preco == 0 || !isset($preco)){
            return 0;
        }else{
            $pattern = "/,/";
            return preg_replace($pattern, ".", $preco);
        }

    }
}
