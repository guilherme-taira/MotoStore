<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class order_site extends Model
{
    use HasFactory;

    protected $table = "order_site";

    public static function VerificarVenda($numero): bool
    {
        $data = order_site::where('numeropedido', '=', $numero)->first();
        if ($data) {
            return true;
        } else {
            return false;
        }
    }

    public static function Ordersjoin()
    {
        $data = DB::table('pivot_site')
            ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->select("*")
            ->orderby('order_site.created_at', 'desc')->limit(5)->get();
        return $data;
    }

    public static function getOrderjoin($id)
    {
        $data = DB::table('pivot_site')
            ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
            ->join('products', 'product_site.seller_sku', '=', 'products.id')
            ->select('*')
            ->where('order_site.id', $id)->get();
        return $data;
    }

    public static function OrdersMercadoLivreMounth($user,$monthCurrent)
    {
        $data = DB::table('pivot_site')
            ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->where('id_user',$user)
            ->where('order_site.created_at', 'like', '%' . $monthCurrent->format('Y-m') . '%')->sum('valorVenda');
        return $data;
    }

    public static function OrdersMercadoLivreDay($user,$monthCurrent)
    {
        $data = DB::table('pivot_site')
            ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->where('id_user',$user)
            ->where('order_site.created_at', 'like', '%' . $monthCurrent->format('Y-m-d') . '%')->sum('valorVenda');
        return $data;
    }

}
