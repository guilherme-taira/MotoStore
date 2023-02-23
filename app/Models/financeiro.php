<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financeiro extends Model
{
    use HasFactory;

    protected $table = 'financeiro';

    public static function SavePayment($status, $valor, $order_id, $user_id, $qrcode, $link, $status_name, $token_transaction)
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
        $newPayment->save();
    }

    public static function contareceber($user)
    {
        $data = financeiro::join('order_site', "order_site.id", '=', 'financeiro.order_id')
            ->where('user_id', $user)->paginate(10);
        return $data;
    }

    public static function contareceberCount($user)
    {
        $data = financeiro::where('status', '=', '4')->where('user_id', $user)->get();
        return $data;
    }

    public static function UpdateFinanceiroByToken($token, $status_id, $status_name)
    {
        financeiro::where('token_transaction', $token)->update(['status' => $status_id, 'value_status' => $status_name]);
    }
}
