<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TikTokProduct extends Model
{
    use HasFactory;

    protected $table = 'tiktok_products'; // <— garante que use essa

    protected $fillable = [
        'local_product_id',
        'tiktok_product_id',
        'shop_id',
        'shop_cipher',
        'title',
        'category',
        'price',
        'raw_response',
        'warnings',
        'priceNotFee',
        'acrescimo_reais',
        'acrescimo_porcentagem',
        'desconto_reais',
        'desconto_porcentagem',
        'isPorcem',
        'precofixo',
        'active',
        'estoque_minimo',
        'user_id',
        'tiktok_sku'
    ];

    protected $casts = [
        'raw_response' => 'array',
        'warnings' => 'array',
        'price' => 'decimal:2',
    ];


    public static function getProdutos($user){
        $data = DB::table('products')
            ->join('tiktok_products', 'products.id', '=', 'tiktok_products.local_product_id')
            ->select(
                'tiktok_products.tiktok_product_id as id_mercadolivre',
                'tiktok_products.title as name',
                'tiktok_products.local_product_id as product_id',
                'products.image',
                'tiktok_products.id',
                'tiktok_products.created_at',
                'tiktok_products.priceNotFee',
                'tiktok_products.acrescimo_reais',
                'tiktok_products.acrescimo_porcentagem',
                'tiktok_products.desconto_reais',
                'tiktok_products.desconto_porcentagem',
                'tiktok_products.isPorcem',
                'tiktok_products.precofixo',
                'tiktok_products.active',
                'tiktok_products.estoque_minimo',
                'products.variation_data',
                'products.isVariation'
            )
            ->where('user_id', $user)
            ->orderBy('tiktok_products.created_at', 'desc')
            ->paginate(10);

        return $data;
    }
}
