<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Items extends Model
{
    public static function validate($request)
    {
        $request->validate([
            "price" => "required|numeric|gt:0",
            "quantity" => "required|numeric|gt:0",
            "product_id" => "required|exists:products,id",
            "order_id" => "required|exists:orders,id",
        ]);
    }

    public function getId()
    {
        return $this->attributes['id'];
    }
    public function setId($id)
    {
        $this->attributes['id'] = $id;
    }
    public function getQuantity()
    {
        return $this->attributes['quantity'];
    }
    public function setQuantity($quantity)
    {
        $this->attributes['quantity'] = $quantity;
    }
    public function getPrice()
    {
        return $this->attributes['price'];
    }
    public function setPrice($price)
    {
        $this->attributes['price'] = $price;
    }
    public function getOrderId()
    {
        return $this->attributes['order_id'];
    }
    public function setOrderId($orderId)
    {
        $this->attributes['order_id'] = $orderId;
    }
    public function getProductId()
    {
        return $this->attributes['product_id'];
    }

    public function setProductId($productId)
    {
        $this->attributes['product_id'] = $productId;
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
    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
    public function getOrder()
    {
        return $this->order;
    }
    public function setOrder($order)
    {
        $this->order = $order;
    }
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
    public function getProduct()
    {
        return $this->product;
    }
    public function setProduct($product)
    {
        $this->product = $product;
    }

    public static function OrderJoinGenerateReportProduct($product_id,$formPayment,$datainicial,$datafinal){

        $data = Items::join('Products', 'items.product_id', '=', 'products.id')
        ->join('orders', 'items.order_id', '=', 'orders.id')
        ->join('payment', 'orders.payment_id', '=', 'payment.id')
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->select('orders.id','orders.total as totalVenda','orders.created_at as datavenda','items.quantity as quantidade','payment.name as pagamento','products.name','users.name as cliente');

        if($product_id){
            $data->where('products.id',$product_id);
        }

        if($formPayment){
            $data->where('orders.payment_id',$formPayment);
        }else{
            $data->where('orders.payment_id','<>',5);
        }

        if($datainicial && $datafinal){
            $data->whereBetween('orders.created_at',[$datainicial, $datafinal]);
        }

       $dados = $data->get();
       return $dados;
    }


    public static function contareceber(){
        $data = Items::join('Products', 'items.product_id', '=', 'products.id')
        ->join('orders', 'items.order_id', '=', 'orders.id')
        ->join('payment', 'orders.payment_id', '=', 'payment.id')
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->select('orders.id','orders.total as totalVenda','orders.created_at as datavenda','items.quantity as quantidade','payment.name as pagamento','products.title','users.name as cliente','orders.dataPayment as datapagamento')
        ->groupBy('orders.id')
        ->where('orders.payment_id','=',4)
        ->paginate(5);

       return $data;
    }

    public static function contareceberCount(){
        $data = Items::join('Products', 'items.product_id', '=', 'products.id')
        ->join('orders', 'items.order_id', '=', 'orders.id')
        ->join('payment', 'orders.payment_id', '=', 'payment.id')
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->select('orders.id','orders.total as totalVenda','orders.created_at as datavenda','items.quantity as quantidade','payment.name as pagamento','products.title','users.name as cliente','orders.dataPayment as datapagamento')
        ->groupBy('orders.id')
        ->where('orders.payment_id','=',4)
        ->get();

       return $data;
    }

}
