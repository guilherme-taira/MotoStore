<?php

namespace App\Http\Controllers\Fornecedor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Yapay\Pagamentos\RenovacaoController;
use App\Models\financeiro;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class fornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            if($order->status == 4){
                $viewData['haPagar'] += 1;
            }else if($order->status == 6){
                $viewData['contasDia'] += 1;
            }else if($order->status == 7){
                $viewData['contasAtrasada'] += 1;
            }
        }
        return view('orders.fornecedor.index',[
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
        //
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


}
