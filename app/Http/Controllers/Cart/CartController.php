<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

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

    public function delete(Request $request, $id)
    {
        echo "<pre>";
        $remove = $request->id;

        foreach ($request->session()->get('products') as $key => $value) {
            if ($key == $remove) {
                $request->Session()->pull('products.'.$key);
                break;
            }
        }
        return back()->with('message','Produto removido com successo!');
    }
}
