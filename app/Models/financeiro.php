<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class financeiro extends Model
{
    use HasFactory;

    protected $table = 'financeiro';

    public static function SavePayment($status, $valor, $order_id, $user_id, $qrcode, $link, $status_name, $token_transaction, $shipping_id)
    {
        $newPayment = new financeiro();
        $newPayment->status = $status;
        $newPayment->valor = $valor;
        $newPayment->order_id = $order_id;
        $newPayment->user_id = $user_id;
        $newPayment->qrcode = $qrcode;
        $newPayment->link = $link;
        $newPayment->value_status = $status_name;
        $newPayment->token_transaction = $token_transaction;
        $newPayment->shipping_id = $shipping_id;
        $newPayment->save();
    }

    public static function contareceber($user)
    {
        $data = financeiro::join('order_site', "order_site.id", '=', 'financeiro.order_id')
            ->join('pivot_site','order_site.id', '=', 'pivot_site.order_id')
            ->join('users','pivot_site.id_user','=','users.id')
            ->join('product_site','pivot_site.product_id','=','product_site.id')
            ->select('financeiro.status as statusf','order_site.*','financeiro.*','order_site.id as id_venda','pivot_site.*','users.*','product_site.*')
            ->where('financeiro.user_id', $user)
            ->orderBy('financeiro.id','desc')->paginate(10);
        return $data;
    }

    public static function aguardandopagamento($user)
    {
        $data = financeiro::join('order_site', "order_site.id", '=', 'financeiro.order_id')
            ->where('user_id', $user)->where('status_id','=',3)->sum('valor');
        return $data;
    }

    public static function pago($user)
    {
        $data = financeiro::join('order_site', "order_site.id", '=', 'financeiro.order_id')
            ->where('user_id', $user)->where('status_id','=',4)->sum('valor');
        return $data;
    }

    public static function GetDataByUser($order_id)
    {
        $data = DB::table('pivot_site')
            ->join('order_site', 'pivot_site.order_id', '=', 'order_site.id')
            ->join('product_site', 'pivot_site.product_id', 'product_site.id')
            ->join('financeiro', 'financeiro.order_id', 'order_site.id')
            ->where('pivot_site.order_id', $order_id)->first();
        return $data;
    }

    public static function contareceberCount($user)
    {
        $data = financeiro::where('user_id', $user)->get();
        return $data;
    }

    public static function UpdateFinanceiroByToken($token, $status_id, $status_name)
    {
        financeiro::where('token_transaction', $token)->update(['status' => $status_id, 'value_status' => $status_name]);
    }
}
