<?php

namespace App\Models;

use DateTime;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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

    public static function Ordersjoin($user_id, Request $request)
    {

        $data = DB::table('pivot_site')
            ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('status', 'order_site.status_id', '=', 'status.id')
            // ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
            // ->join('products', 'product_site.seller_sku', '=', 'products.id')
            ->select("*")
            ->orderBy('order_site.id', 'desc')
            ->where('users.id', $user_id);

        if ($request->nome) {
            $data->where('users.name', 'like', '%' . $request->nome . '%');
        }

        if ($request->cpf) {
            $data->where('users.cpf', 'like', '%' . $request->cpf . '%');
        }

        if ($request->npedido) {
            $data->where('pivot_site.order_id', $request->npedido);
        }

        if ($request->datainicial && $request->datafinal) {
            $data->whereBetween('order_site.dataVenda', [$request->datainicial, $request->datafinal]);
        }
        $dados = $data->paginate(10)->appends($request->all());
        return $dados;
    }

    public static function getOrderjoin($id)
    {
        $data = DB::table('pivot_site')
            // ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
            // ->join('products', 'product_site.seller_sku', '=', 'products.id')
            ->select('*')
            ->where('order_site.id', $id)->get();
        return $data;
    }

    public static function OrdersMercadoLivreMounth($user, $monthCurrent)
    {
        $data = DB::table('pivot_site')
            ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('product_site','pivot_site.product_id','=','product_site.id')
            ->where('id_user', $user)
            ->where('order_site.created_at', 'like', '%' . $monthCurrent->format('Y-m') . '%')->sum('valorVenda');
        return $data;
    }

    public static function OrdersMercadoLivreDay($user, $monthCurrent)
    {
        $data = DB::table('pivot_site')
            ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->where('id_user', $user)
            ->where('order_site.created_at', 'like', '%' . $monthCurrent->format('Y-m-d') . '%')->sum('valorVenda');
        return $data;
    }
}
