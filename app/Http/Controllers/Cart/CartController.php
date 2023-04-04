<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MelhorEnvio\Cart\CartImplementacao;
use App\Http\Controllers\MelhorEnvio\Cart\CartSendFreteController;
use App\Http\Controllers\MelhorEnvio\Cart\CompraFreteImplementacao;
use App\Http\Controllers\MelhorEnvio\Cart\RequestCompraFrete;
use App\Http\Controllers\MelhorEnvio\MelhorEnvioGetDataController;
use App\Http\Controllers\MelhorEnvio\MelhorEnvioRequestCotacao;
use App\Http\Controllers\MercadoPago\Bridge\Pix;
use App\Http\Controllers\MercadoPago\Bridge\ServicoPix;
use App\Http\Controllers\MercadoPago\Bridge\ServicoTodosPagamento;
use App\Models\categorias;
use App\Models\endereco;
use App\Models\Items;
use App\Models\order_site;
use App\Models\order_user;
use App\Models\Orders;
use App\Models\pivot_site;
use App\Models\product_site;
use App\Models\Products;
use App\Models\sub_category;
use DateTime;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Unique;
use MercadoPago\SDK as ML;
use MercadoPago\Payment as MercadoPreference;
use MercadoPago\Item as MercadoItem;
use MercadoPago\Payer as payer;

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

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $viewData = [];
        $viewData['categorias'] = $categorias;
        $viewData['title'] = "Cart MotoStore";
        $viewData['enderecos'] = endereco::where('user_id', Auth::user()->id)->get();
        $viewData['subtitle'] = "Shopping Cart";
        $viewData['total'] = $total;
        $viewData['products'] = $productInCart;
        $viewData['produto'] = [];
        return view('cart.index')->with("viewData", $viewData);
    }

    public function CriarArrayProdutosCarrinho(Request $request, array $productInSection)
    {
        $produtosSession = [];
        $productKeys = array_keys($productInSection);
        $productvalues = array_values($productInSection);
        for ($i = 0; $i < count($productInSection); $i++) {
            $data = Products::findMany($productKeys[$i])->first();
            array_push($produtosSession, ['produto' => $productKeys[$i], 'quantidade' => $productvalues[$i], 'image' => $data->image, 'name' => $data->title, 'price' => $data['price']]);
        }
        $request->session()->put('carrinho', $produtosSession);
    }

    public function orderFinished(Request $request)
    {

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $viewData = [];
        $viewData["title"] = "Purchase - Online Store";
        $viewData["subtitle"] = "Venda Status";
        $viewData['categorias'] = $categorias;

        return view('purchase.order')->with("viewData", $viewData);
    }

    public function createPayment(Request $request)
    {
        echo "<pre>";
        print_r($request->all());
    }

    public function add(Request $request, $id)
    {
        $products = $request->session()->get("products");
        $products[$id] = $request->input('quantity');
        $request->session()->put('products', $products);

        $this->CriarArrayProdutosCarrinho($request, $products);
        return redirect()->route('cart.index');
    }

    public function checkout(Request $request)
    {
        // $request->session()->forget('products');

        $produtos = [];
        $dimensoes = [];
        $volumes = [];

        foreach ($request->all() as $key => $value) {
            if ($this->MatchProduct($key) == true) {
                $medidas = Products::findMany($value)->first();
                $dimensoes['altura'] = $medidas->height;
                $dimensoes['largura'] = $medidas->width;
                $dimensoes['comprimento'] = $medidas->length;
                $dimensoes['peso'] = $medidas->weight;
                array_push($volumes, $dimensoes);
                array_push($produtos, ["produto" => $value, "quantidade" => $this->MatchQuantity($request->all(), $key)]);
            }
        };

        foreach ($produtos as $key => $value) {
            $products = $request->session()->get("products");
            $products[$value['produto']] = $value['quantidade'];
            $request->session()->put('products', $products);
        }

        // CODIGO POSTAL
        $postalCode = endereco::where('id', $request->endereco)->first();

        $cotacaoFrete = new MelhorEnvioGetDataController("13610296", isset($postalCode->cep) ? $postalCode->cep : Auth::user()->cep, [$produtos]);

        $cotar = new MelhorEnvioRequestCotacao($cotacaoFrete);
        $transportadora = $cotar->resource();

        $total = 0;
        $productInCart = [];
        $productInSection = $request->session()->get("products");

        if ($productInSection) {
            $productInCart = Products::findMany(array_keys($productInSection));
            $total = $this->sumPricesByQuantities($productInCart, $productInSection);
        }

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }


        $servicoPix = new ServicoPix();
        $servicoOutrosPagamento = new ServicoTodosPagamento();
        $executar = new Pix($produtos);
        $executar->setTipoPagamento($servicoPix);
        $pix = $executar->gerarPagamento();
        $executar->setTipoPagamento($servicoOutrosPagamento);
        $preference = $executar->gerarPagamento();

        $request->session()->put('pref', $preference['id']);
        $request->session()->put('external_reference', $preference['external_reference']);

        $viewData = [];
        $viewData['categorias'] = $categorias;
        $viewData['title'] = "Cart MotoStore";
        $viewData['subtitle'] = "Shopping Cart";
        $viewData['total'] = $total;
        $viewData['products'] = $productInCart;
        $viewData['transportadora'] = $transportadora;
        $viewData['produto'] = [];
        $viewData['pref'] = $preference['id'];
        $viewData['external_reference'] = $preference['external_reference'];
        return view('cart.checkout')->with("viewData", $viewData);
    }

    public function addItemMl(MercadoItem $item, array $array)
    {
        $data = array_push($array, $item);
        return $data;
    }

    public static function sumPricesByQuantities($products, $productsInSession)
    {
        $total = 0;
        foreach ($products as $product) {
            if ($product->getPricePromotion() > 0) {
                $total = $total + ($product->getPricePromotion() * $productsInSession[$product->getId()]);
            } else {
                $total = $total + ($product->getPrice() * $productsInSession[$product->getId()]);
            }
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
                if ($value['produto'] == $remove) {
                    $request->Session()->pull('carrinho.' . $key);
                    $request->Session()->pull('products.' . $key);
                    break;
                }
                break;
            }
        }
        return back()->with('message', 'Produto removido com successo!');
    }

    public function deleteOneCarrinho(Request $request, $id)
    {
        $remove = $request->id;
        foreach ($request->session()->get('carrinho') as $key => $value) {
            if ($value['produto'] == $remove) {
                $request->Session()->pull('carrinho.' . $key);
            }
        }

        foreach ($request->session()->get('products') as $key => $value) {
            if ($key == $remove) {
                $request->Session()->pull('products.' . $key);
                break;
            }
        }

        return back()->with('message', 'Produto removido com successo!');
    }


    public function MatchProduct($value)
    {
        $regex = "/\produto/";
        if (preg_match_all($regex, $value)) {
            return true;
        }
    }

    public function MatchQuantity($array, $value)
    {
        $str = preg_replace('/[^0-9]/s', '', $value);
        $regex = "/quantity$str/";
        foreach ($array as $key => $produto) {
            if (preg_match($regex, $key)) {
                return $produto;
            }
        }
    }

    public function purcharse(Request $request)
    {
        $productInSection = $request->session()->get('products');
        $produtos = [];
        $dimensoes = [];
        $volumes = [];

        foreach ($this->CriarArrayProdutos($productInSection) as $key => $value) {
            $medidas = Products::findMany($value['produto'])->first();
            $dimensoes['altura'] = $medidas->height;
            $dimensoes['largura'] = $medidas->width;
            $dimensoes['comprimento'] = $medidas->length;
            $dimensoes['peso'] = $medidas->weight;
            array_push($volumes, $dimensoes);
            array_push($produtos, ["produto" => $value['produto'], "quantidade" => $value['quantidade']]);
        };

        // // // IMPLEMENTAÇÃO DO CARRINHO DO MELHOR ENVIO++
        $frete = new CartImplementacao($request->transportadora, "", [$produtos], $volumes);
        $frete->getDados();
        // ENVIA O FRETE PARA O CARRINHO DO MELHOR ENVIO
        $cartFrete = new CartSendFreteController($frete);
        $orderid = $cartFrete->resource();
        // //IMPLEMENTAÇÃO DO CHECKOUT DO MELHOR ENVIO -> FILA
        $compraFrete = new CompraFreteImplementacao($orderid['id']);
        $enviar = new RequestCompraFrete($compraFrete);
        $enviar->resource();

        //GRAVA NO BANCO
        if ($productInSection) {
            $userId = Auth::user()->id;
            $payment = 1;
            $dataPayment = new DateTime();

            if ($userId) {
                $order = new Orders();
                $order->setUser($userId);
                $order->setPaymentId($payment);
                $order->setDatePayment($dataPayment->format('Y-m-d H:i:s'));
                $order->setColor($this->SelectPaymentColor(1));
                $order->setTotal(0);
                $order->save();

                $total = 0;
                //ORDER _SITE
                $order_site = new order_site();
                $order_site->local = "LOJA VIRTUAL";
                $order_site->valorVenda = $total;
                $order_site->valorProdutos = $total;
                $order_site->dataVenda = $dataPayment->format('Y-m-d');
                $order_site->cliente = Auth::user()->name;
                $order_site->id_frete = $orderid['id'];
                $order_site->valorFrete = $orderid['price'];
                $order_site->status_id = 4;
                $order_site->save();

                // GRAVA RELACIONAMENTO DA VENDA
                $order_user = new order_user();
                $order_user->order = $order->getId();
                $order_user->user = $userId;
                $order_user->save();

                $i = 0;
                $productInCart = Products::findMany(array_keys($productInSection));
                $quantidades = array_values($productInSection);
                foreach ($productInCart as $product) {

                    $quantity = $productInSection[$product->getId()];
                    $item = new product_site();
                    $item->nome = $product->title;
                    $item->codigo = $order->getId();
                    $item->valor = $product->getPrice();
                    $item->seller_sku = $product->id;
                    $item->quantidade = $quantidades[$i];
                    $item->save();
                    $total = $total + ($product->getPrice() * $quantity);
                    // BAIXA SALDO
                    $this->BaixaSaldo($product->getId(), $quantity);
                    //PIVOT SITE
                    $pivot_site = new pivot_site();
                    $pivot_site->order_id = $order_site->id;
                    $pivot_site->product_id = $item->id;
                    $pivot_site->id_user = $userId;
                    $pivot_site->save();
                    $i++;
                }

                $order_site->valorVenda = $total;
                $order_site->save();

                $request->session()->forget('products');
                $request->session()->forget('user');
                $request->session()->forget('payment');
                $request->session()->forget('datePayment');

                $categorias = [];
                foreach (categorias::all() as $value) {
                    $categorias[$value->id] = [
                        "nome" => $value->nome,
                        "subcategory" => sub_category::getAllCategory($value->id),
                    ];
                }

                $viewData = [];
                $viewData["title"] = "Purchase - Online Store";
                $viewData["subtitle"] = "Venda Status";
                $viewData["order"] = $order;
                $viewData['categorias'] = $categorias;

                $request->session()->put('id_venda', $order_site);
                return redirect()->route('purchase.order')->with("viewData", $viewData);
            } else {
                return redirect()->route('cart.index');
            }
        }
        return false;
    }


    public function CriarArrayProdutos(array $productInSection)
    {
        $produtosSession = [];
        $productKeys = array_keys($productInSection);
        $productvalues = array_values($productInSection);
        for ($i = 0; $i < count($productInSection); $i++) {
            array_push($produtosSession, ['produto' => $productKeys[$i], 'quantidade' => $productvalues[$i]]);
        }
        return $produtosSession;
    }

    public function BaixaSaldo($product_id, $quantity)
    {

        $product = Products::where('id', $product_id)->first();

        if ($product) {
            $stockCurrent = $product->stock - $quantity;
            $product->update(['stock' => $stockCurrent]);
        }
    }

    public function status(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'MotoStore Status da Venda';
        $viewData['subtitle'] = 'Status da Venda';

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $viewData['categorias'] = $categorias;
        return view('purchase.index')->with('viewData', $viewData);
    }

    public function SelectPaymentColor($paymentId)
    {

        switch ($paymentId) {
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

    // add kit
    public function addKit(Request $request)
    {
        print_r($request->all());
    }
}
