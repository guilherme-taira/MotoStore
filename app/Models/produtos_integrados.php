<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class produtos_integrados extends Model
{
    use HasFactory;

    protected $table = 'produtos_integrados';

    public static function getProdutos($user){
        $data = DB::table('products')
        ->join('produtos_integrados', 'products.id', '=', 'produtos_integrados.product_id')
        ->select('produtos_integrados.id_mercadolivre','produtos_integrados.name','produtos_integrados.product_id','products.image','produtos_integrados.id','produtos_integrados.created_at')
        ->where('user_id', $user)->paginate(10);
    return $data;
    }

    public static function cadastrar($name,$image,$id_prod){
        $integrado = new produtos_integrados();
        $integrado->name = $name;
        $integrado->image = $image;
        $integrado->product_id = $id_prod;
        $integrado->user_id = Auth::user()->id;
        $integrado->save();
    }

    public static function removeStockProduct($produto,$quantidade){
        $data = produtos_integrados::where('id',$produto)->first();
        if($data){
            $atualStock = Products::where('id',$data->product_id)->first();
            $novoEstoque = $atualStock->available_quantity - $quantidade;
            Products::where('id',$data->product_id)->update([
                'available_quantity' => $novoEstoque
            ]);
        }
    }

}
