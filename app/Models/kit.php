<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class kit extends Model
{
    use HasFactory;

    protected $table = 'kit';

    protected $fillable = [
        'product_id',
        'product_kit',
        'available_quantity',
    ];

    public static function getAllKits($user)
    {
        $data = Products::getKitByUser($user);
        return $data;
    }

    public static function getProductsByKit($id){
    // Query para buscar o produto principal (kit)
        $kit = DB::table('products')
        ->where('id', '=', $id)
        ->first();

        // Query para buscar os itens que compõem o kit
        $kitItems = DB::table('kit')
        ->leftJoin('products', 'kit.id_product_kit', '=', 'products.id')
        ->where('kit.product_id', '=', $id)
        ->select('kit.*', 'products.*','kit.available_quantity as available_quantity')
        ->get();

        // Retorna os dados
        return [
        'kit' => $kit,
        'kitItems' => $kitItems
        ];
    }
}
