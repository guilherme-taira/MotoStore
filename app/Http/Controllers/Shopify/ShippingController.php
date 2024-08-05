<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use App\Models\ShippingUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingController extends setShippingController
{
    public function setShipping(){

        foreach ($this->getData() as $key => $fulfillment) {
          $data = [
            'rastreio' => $fulfillment['tracking_number'],
            'url_rastreio' => $fulfillment['tracking_url'],
        ];
        }

        // Condições para encontrar o registro
        $conditions = [
            'id' => $this->getShipping()->id,
        ];
        // Crie ou atualize o registro
        ShippingUpdate::updateOrCreate($conditions, $data);
    }
}
