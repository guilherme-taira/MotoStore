<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShippingUpdate extends Model
{
    use HasFactory;


    // Define a tabela associada ao modelo
    protected $table = 'shipping_updates';

    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'id_shopify',
        'rastreio',
        'url_rastreio',
        'isBrazil',
        'id_mercadoLivre',
        'id_user',
        'id_vendedor',
    ];

    public static function getDataById($id){
        $data = DB::table('shipping_updates')
        ->where('id_shopify','=',$id)->first();
        return $data;
    }
}
