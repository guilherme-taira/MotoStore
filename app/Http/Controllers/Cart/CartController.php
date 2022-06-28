<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Items;
use App\Models\order_user;
use App\Models\Orders;
use App\Models\Products;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function indexCart(Request $request)
    {

        $total = 0;
        $productInCart = [];

        $productInSection = $request->session()->get("products");
        if ($productInSection) {
            $productInCart = Products::findMany(array_keys($productInSection));
            $total = $this->sumPricesByQuantities($productInCart, $productInSection);
        }

        $viewData = [];
        $viewData['title'] = "Cart MotoStore";
        $viewData['subtitle'] = "Shopping Cart";
        $viewData['total'] = $total;
        $viewData['products'] = $productInCart;
        return view('cart.index')->with("viewData", $viewData);
    }

    public function add(Request $request, $id)
    {
        $products = $request->session()->get("products");
        $products[$id] = $request->input('quantity');
        $request->session()->put('products', $products);

        return redirect()->route('cart.index');
    }

    public static function sumPricesByQuantities($products, $productsInSession)
    {
        $total = 0;
        foreach ($products as $product) {
            $total = $total + ($product->getPrice() * $productsInSession[$product->getId()]);
        }
        return $total;
    }

    public function delete(Request $request)
    {
        $request->session()->forget('products');
        return back()->with('message', 'Produtos removido com successo!');
    }

    public function deleteOne(Request $request, $id)
    {
        $remove = $request->id;

        foreach ($request->session()->get('products') as $key => $value) {
            if ($key == $remove) {
                $request->Session()->pull('products.' . $key);
                break;
            }
        }
        return back()->with('message', 'Produto removido com successo!');
    }


    public function purcharse(Request $request)
    {

        $productInSection = $request->session()->get('products');

        if ($productInSection) {
            $userId = $request->session()->get('user');
            $payment = $request->session()->get('payment');
            $dataPayment = $request->session()->get('datePayment');

            if ($userId) {
                $order = new Orders();
                $order->setUser($userId);
                $order->setPaymentId($payment);
                $order->setDatePayment($dataPayment);
                $order->setColor($this->SelectPaymentColor($payment));
                $order->setTotal(0);
                $order->save();

                $total = 0;
                $productInCart = Products::findMany(array_keys($productInSection));
                foreach ($productInCart as $product) {
                    $quantity = $productInSection[$product->getId()];
                    $item = new Items();
                    $item->setQuantity($quantity);
                    $item->setPrice($product->getPrice());
                    $item->setProductId($product->getId());
                    $item->setOrderId($order->getId());
                    $item->save();
                    $total = $total + ($product->getPrice() * $quantity);

                    // BAIXA SALDO
                    $this->BaixaSaldo($product->getId(),$quantity);
                }

                $order->setTotal($total);
                $order->save();

                // GRAVA RELACIONAMENTO DA VENDA
                $order_user = new order_user();
                $order_user->order = $order->getId();
                $order_user->user = $userId;
                $order_user->save();

                $request->session()->forget('products');
                $request->session()->forget('user');
                $request->session()->forget('payment');
                $request->session()->forget('datePayment');

                $viewData = [];
                $viewData["title"] = "Purchase - Online Store";
                $viewData["subtitle"] = "Venda Status";
                $viewData["order"] = $order;
                return view('purchase.order')->with("viewData", $viewData);
            } else {
                return redirect()->route('cart.index');
            }
        }
        return false;
    }

    public function BaixaSaldo($product_id,$quantity){

        $product = Products::where('id',$product_id)->first();

        if($product){
            $stockCurrent = $product->stock - $quantity;
            $product->update(['stock' => $stockCurrent]);
        }
    }

    public function status(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'MotoStore Status da Venda';
        $viewData['subtitle'] = 'Status da Venda';
        return view('purchase.index')->with('viewData', $viewData);
    }

    public function SelectPaymentColor($paymentId){

        switch($paymentId){
            case 1:
                return '#007bff';
                break;
            case 2:
                return '#ac15e8';
                break;
            case 3:
                return '#15dde8';
                break;
            case 4:
                return '#d3e815';
                break;
            case 5:
                return '#ff0000';
                break;
            default:
                return '#007bff';
                break;
        }
    }
}
