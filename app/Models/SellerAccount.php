<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',         // <--- adicione este
        'access_token',
        'refresh_token',
        'expires_in',
        // adicione outros campos se existirem na tabela
    ];
}
