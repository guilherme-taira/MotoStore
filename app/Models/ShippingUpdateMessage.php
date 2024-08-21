<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingUpdateMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_update_id',
        'mensagem',
    ];

    // Definindo a relação com o model ShippingUpdate
    public function shippingUpdate()
    {
        return $this->belongsTo(ShippingUpdate::class);
    }
}
