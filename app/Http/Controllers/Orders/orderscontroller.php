<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Printer\PrinterController;
use App\Http\Controllers\Yapay\Pagamentos\RenovacaoController;
use App\Models\financeiro;
use App\Models\Items;
use App\Models\order_site;
use App\Models\Orders;
use App\Models\Products;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class orderscontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $viewData = [];
        $viewData['title'] = "Tela de Vendas";
        $viewData['subtitle'] = "Vendas";
        $viewData['pedidos'] = [];

        $orders = order_site::Ordersjoin(Auth::user()->id,$request);
        $viewData['orders'] = $orders;

        foreach ($orders as $order) {
            array_push($viewData['pedidos'], ['pedido' => $order, 'produtos' => order_site::getOrderjoin($order->order_id)]);
        }

        return view('orders.index', [
            'viewData' => $viewData,
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = order_site::getOrderjoin($id);

        $viewData = [];
        $viewData['title'] = "Pedido";
        $viewData['subtitle'] = "$id";
        $viewData['order'] = $order;
        $viewData['pedidos'] = [];

        $i = 0;
        foreach ($order as $key => $product) {

            if (isset($product->isKit)) {
                foreach (Products::getProducts($product->id) as $produto) {
                    array_push($viewData['pedidos'], ['produto' => $produto, 'venda' => $order[$i]]);
                }
                $i++;
            } else {
                array_push($viewData['pedidos'], ['produto' => $product, 'venda' => $order[$i]]);
            }
        }

        echo "<pre>";
        print_r($viewData);
        // return view('orders.show')->with('viewData', $viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function Areceber()
    {
        $viewData = [];
        $viewData['title'] = "Contas a Receber";
        $viewData['subtitle'] = 'Valores a Receber';
        $viewData['orders'] = financeiro::contareceber(Auth::user()->id);
        $viewData['countData'] = financeiro::contareceberCount(Auth::user()->id);

        $viewData['contasDia'] = 0;
        $viewData['contasAtrasada'] = 0;
        $viewData['haPagar'] = 0;

        // DATA ATUAL
        $dataNow = new DateTime();

        // INCREMENTA A QUANTIDADE DE VENDAS A RECEBER
        foreach ($viewData['countData'] as $order) {
            if ($order->status == 4) {
                $viewData['haPagar'] += 1;
            } else if ($order->status == 6) {
                $viewData['contasDia'] += 1;
            } else if ($order->status == 7) {
                $viewData['contasAtrasada'] += 1;
            }
        }
        return view('orders.areceber', [
            'viewData' => $viewData,
        ]);
    }

    public function baixarvenda(Request $request)
    {
        // BAIXA O PEDIDO
        Orders::BaixarVenda($request->id);
        return redirect()->route('orders.areceber')->with('msg', 'Pedido Baixado Com Sucesso!');
    }


    public function ImprimirEtiqueta(Request $request)
    {
        $token = token::where('user_id', Auth::user()->id)->first(); // CHAMANDO ANTIGO
        // IMPRIME ETIQUETA
        $data = new PrinterController($request->shipping_id, $token->access_token);
        $dados = $data->resource();
    }

    public function UpdateNewPayment(Request $request)
    {
        $token = token::where('user_id', Auth::user()->id)->first(); // CHAMANDO ANTIGO
        $newPayment = new RenovacaoController;
        $data = $newPayment->UpdatePayment($request->id, $token->access_token);

        $viewData = [];
        $viewData['title'] = "Contas a Receber";
        $viewData['subtitle'] = 'Valores a Receber';
        $viewData['orders'] = financeiro::contareceber(Auth::user()->id);
        $viewData['countData'] = financeiro::contareceberCount(Auth::user()->id);

        $viewData['contasDia'] = 0;
        $viewData['contasAtrasada'] = 0;
        $viewData['haPagar'] = 0;

        // DATA ATUAL
        $dataNow = new DateTime();

        // INCREMENTA A QUANTIDADE DE VENDAS A RECEBER
        foreach ($viewData['countData'] as $order) {
            if ($order->status == 4) {
                $viewData['haPagar'] += 1;
            } else if ($order->status == 6) {
                $viewData['contasDia'] += 1;
            } else if ($order->status == 7) {
                $viewData['contasAtrasada'] += 1;
            }
        }
        return view('orders.areceber', [
            'viewData' => $viewData,
        ]);
    }

    public function feedback(Request $request){
        $data = json_decode(json_encode($request->all()));
        echo "<pre> ";
        print_r($data);
        echo "<hr>merchant_order_id : $data->merchant_order_id , external_reference: $data->external_reference , payment_id : $data->payment_id ";
    }
}
