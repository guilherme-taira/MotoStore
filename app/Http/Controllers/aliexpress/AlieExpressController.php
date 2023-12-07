<?php

namespace App\Http\Controllers\aliexpress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlieExpressController
{
    public function getToken()
    {
        // AliExpress Class
        // $aliexpress = new implementadorAuthController("501442", "MufXLBonCTb8LvFYjx3WGBNuYQFuoOXT", "3_501442_lYjW2aLGA5jR9lBpVMbDi6Wv10591");
        // $aliexpress->resource();

        $order = new AliExpressOrderGetController("aliexpress.solution.order.get","50000900413zMMZZqMiqBKEt11f609f85Rug9YkUGhpBzsd9geP7mxtGKhADrdLKVAV9","501442","MufXLBonCTb8LvFYjx3WGBNuYQFuoOXT");
        $order->resource();
    }
}
