<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Items;
use App\Models\Orders;
use DateTime;
use Illuminate\Http\Request;

class orderscontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Tela de Vendas";
        $viewData['subtitle'] = "Vendas";

        $orders = Orders::Ordersjoin();
        $viewData['orders'] = $orders;

        return view('orders.index',[
            'viewData' => $viewData,
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
        $order = Orders::getOrderjoin($id);

        $viewData = [];
        $viewData['title'] = "Pedido";
        $viewData['subtitle'] = "$id";
        $viewData['order'] = $order;

        return view('orders.show')->with('viewData',$viewData);
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

    public function Areceber(){
        $viewData = [];
        $viewData['title'] = "Contas a Receber";
        $viewData['subtitle'] = 'Valores a Receber';
        $viewData['orders'] = Items::contareceber();
        $viewData['countData'] = Items::contareceberCount();

        $viewData['contasDia'] = 0;
        $viewData['contasAtrasada'] = 0;

        // DATA ATUAL
        $dataNow = new DateTime();

        // INCREMENTA A QUANTIDADE DE VENDAS A RECEBER
        foreach ($viewData['countData'] as $order) {
            if($order->datapagamento < $dataNow->format('Y-m-d')){
                $viewData['contasDia'] += 1;
            }else{
                $viewData['contasAtrasada'] += 1;
            }
        }
        return view('orders.areceber',[
            'viewData' => $viewData,
        ]);
    }

    public function baixarvenda(Request $request){
        // BAIXA O PEDIDO
        Orders::BaixarVenda($request->id);
        return redirect()->route('orders.areceber')->with('msg','Pedido Baixado Com Sucesso!');
    }
}
