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
        // OLD VERSION
        // $data = financeiro::join('order_site', "order_site.id", '=', 'financeiro.order_id')
        //     ->join('pivot_site','order_site.id', '=', 'pivot_site.order_id')
        //     ->join('users','pivot_site.id_user','=','users.id')
        //     ->join('product_site','pivot_site.product_id','=','product_site.id')
        //     ->join('products','product_site.codigo','=','products.id')
        //     ->select('financeiro.status as statusf','order_site.*','financeiro.*','order_site.id as id_venda','pivot_site.*','users.*','product_site.*','products.informacaoadicional','financeiro.id as financeiroId')
        //     ->where('financeiro.user_id', $user)
        //     ->orderBy('financeiro.id','desc')->paginate(10);

        // return $data;

            // NEW VERSION WITH FILTER
            $query = financeiro::join('order_site', 'order_site.id', '=', 'financeiro.order_id')
            ->join('pivot_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
            ->join('products', 'product_site.codigo', '=', 'products.id')
            // ->rightjoin('tiktok_products', 'products.id', '=', 'tiktok_products.local_product_id')
            ->select(
                'financeiro.status as statusf',
                'order_site.*',
                'financeiro.*',
                'order_site.id as id_venda',
                'pivot_site.*',
                'users.*',
                'product_site.*',
                'products.informacaoadicional',
                'financeiro.id as financeiroId'
            )
            ->where('financeiro.user_id', $user);

            // Filtro: Número do Pedido (assumindo que esteja em order_site.numeropedido)
            if (request()->filled('npedido')) {
                $npedido = request()->input('npedido');
                $query->where('order_site.numeropedido', 'like', "%{$npedido}%");
            }

            // Filtro: Nome do Cliente (assumindo que esteja em users.name)
            if (request()->filled('nome')) {
                $nome = request()->input('nome');
                $query->where('order_site.cliente', 'like', "%{$nome}%");
            }

            // Filtro: Nome do Cliente (assumindo que esteja em users.name)
            if (request()->filled('codigoafiliado')) {
                $codigoafiliado = request()->input('codigoafiliado');
                $query->where('pivot_site.id_user', 'like', "%{$codigoafiliado}%");
            }

            // Filtro: Data Inicial (assumindo que a data de criação está em order_site.created_at)
            if (request()->filled('datainicial')) {
                $datainicial = request()->input('datainicial');
                $query->whereDate('order_site.dataVenda', '>=', $datainicial);
            }

            // Filtro: Data Final
            if (request()->filled('datafinal')) {
                $datafinal = request()->input('datafinal');
                $query->whereDate('order_site.dataVenda', '<=', $datafinal);
            }

            // Filtro: Status do Pedido (assumindo que está em financeiro.status)
            if (request()->filled('status')) {
                $status = request()->input('status');
                $query->where('financeiro.status_envio', $status);
            }

            // Pagina e anexa os parâmetros da requisição
            $data = $query->orderBy('financeiro.id','desc')
            ->paginate(10)
            ->appends(request()->all());

           return $data;
    }

    public static function aguardandopagamento($user)
    {
        $data = DB::table('pivot_site')
        ->join('order_site', 'pivot_site.order_id', '=', 'order_site.id')
        ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
        ->join('products', 'product_site.seller_sku', '=', 'products.id')
        ->join('financeiro', 'pivot_site.order_id', '=', 'financeiro.order_id')
        ->where('financeiro.user_id', $user)
        ->where('financeiro.status', 3)
        ->sum('products.price');
        return $data;
    }


    public static function aguardandopagamentoOrders($user)
    {
        $data = financeiro::join('order_site', "order_site.id", '=', 'financeiro.order_id')
            ->where('user_id', $user)->where('status_id','=',3);
        return $data;
    }

    public static function pago($user)
    {
        $data = DB::table('pivot_site')
        ->join('order_site', 'pivot_site.order_id', '=', 'order_site.id')
        ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
        ->join('products', 'product_site.seller_sku', '=', 'products.id')
        ->join('financeiro', 'pivot_site.order_id', '=', 'financeiro.order_id')
        ->where('financeiro.user_id', $user)
        ->where('financeiro.status', 4)
        ->sum('products.price');
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

    public static function GetTokenByNotification($order_id)
    {
        $data = DB::table('pivot_site')
            ->join('order_site', 'pivot_site.order_id', '=', 'order_site.id')
            ->join('financeiro', 'financeiro.order_id', 'order_site.id')
            ->join('users','pivot_site.id_user','=','users.id')
            ->join('fcm_tokens','pivot_site.id_user','fcm_tokens.user_id')
            ->where('financeiro.token_transaction', $order_id)->first();
        return $data;
    }

    public static function GetDataByUserApp($order_id)
    {
        $data = DB::table('pivot_site')
            ->join('order_site', 'pivot_site.order_id', '=', 'order_site.id')
            ->join('product_site', 'pivot_site.product_id', 'product_site.id')
            ->join('financeiro', 'financeiro.order_id', 'order_site.id')
            ->join('users','pivot_site.id_user','=','users.id')
            ->join('status_app','financeiro.status_envio','=','status_app.id')
            ->select('financeiro.*','product_site.nome as product_name','order_site.*','users.*')
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
