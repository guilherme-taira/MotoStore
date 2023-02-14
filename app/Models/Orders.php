<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class Orders extends Model
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'payment_id',
        'dataBaixa'
    ];

    protected $table = 'orders';

    public function getId()
    {
        return $this->attributes['id'];
    }
    public function setId($id)
    {
        $this->attributes['id'] = $id;
    }
    public function getTotal()
    {
        return $this->attributes['total'];
    }
    public function setTotal($total)
    {
        $this->attributes['total'] = $total;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function setUserId($userId)
    {
        $this->attributes['user_id'] = $userId;
    }

    public function setPaymentId($payment)
    {
        $this->attributes['payment_id'] = $payment;
    }

    public function getCreatedAt()
    {
        return $this->attributes['created_at'];
    }
    public function setCreatedAt($createdAt)
    {
        $this->attributes['created_at'] = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->attributes['updated_at'];
    }
    public function setUpdatedAt($updatedAt)
    {
        $this->attributes['updated_at'] = $updatedAt;
    }
    public function getUser()
    {
        return $this->user_id;
    }
    public function setUser($user_id)
    {
        $this->user_id = $user_id;
    }
    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function getItems()
    {
        return $this->items;
    }
    public function setItems($items)
    {
        $this->items = $items;
    }

    public function User()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'order_user', 'order', 'user');
    }

    public function setDatePayment($dataPayment){
        $this->attributes['dataPayment'] = $dataPayment;
    }

    public function getColor(){
        return $this->attributes['color'];
    }

    public function setColor($color){
        $this->attributes['color'] = $color;
    }

    public function getDatePayment(){
        return $this->attributes['dataPayment'];
    }


    public static function validate($request)
    {
        $request->validate([
            "total" => "required|numeric",
            "user_id" => "required|exists:users,id",
        ]);
    }

    public static function Ordersjoin($user_id)
    {
        $data = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('payment', 'orders.payment_id', '=', 'payment.id')
            ->select('orders.id','orders.total','users.name','orders.created_at','payment.name as pagamento','orders.color')
            ->where('user_id',$user_id)
            ->orderby('orders.created_at','desc')->limit(5)->get();
        return $data;
    }

    public static function OrdersjoinAjax($user,$formPayment)
    {

        $data = orders::join('users', 'orders.user_id', '=', 'users.id')
            ->join('payment', 'orders.payment_id', '=', 'payment.id')
            ->select('orders.id','orders.total','users.name','orders.created_at','payment.name as pagamento');

            if($user){
                $data->where('users.name','LIKE', '%'.$user.'%');
            }

            if($formPayment){
                $data->where('orders.payment_id',$formPayment);
            }

           $dados = $data->get();

        return $dados;
    }

    public static function OrderJoinGenerateReport($user,$formPayment,$datainicial,$datafinal){

        $data = orders::join('users', 'orders.user_id', '=', 'users.id')
        ->join('payment', 'orders.payment_id', '=', 'payment.id')
        ->select('orders.id','orders.total','users.name','orders.created_at','payment.name as pagamento');

        if($user){
            $data->where('users.name','LIKE', '%'.$user.'%');
        }

        if($formPayment){
            $data->where('orders.payment_id',$formPayment);
        }

        if($datainicial && $datafinal){
            $data->whereBetween('orders.created_at',[$datainicial, $datafinal]);
        }

       $dados = $data->get();
       return $dados;
    }

    public static function getOrderjoin($id)
    {
        $data = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('items', 'orders.id', '=', 'items.order_id')
            ->join('products', 'products.id', '=', 'items.product_id')
            ->select('users.name','users.email','items.product_id','products.id','products.image','products.description','items.quantity','products.price','orders.total')
            ->where('orders.id',$id)->get();

        return $data;
    }

    public static function BaixarVenda($id){
        $data = new DateTime();

        $dados =  Orders::find($id);
        // ATUALIZA NA DATA ATUAL
        $dados->update(['dataBaixa' => $data->format('Y-m-d H:i:s'), 'payment_id' => '1']);
    }
}
