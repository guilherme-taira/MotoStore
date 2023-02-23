<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\financeiro;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // LIST ALL METHOD PAYMENT
        $payments = Payment::all();

        $viewData = [];
        $viewData['title'] = "Formas de Pagamento";
        $viewData['subtitle'] = "Formas de Pagamento";
        $viewData['payments'] = $payments;

        return view('payment.index', [
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
        $viewData = [];
        $viewData['title'] = "Cadastro de Meios de Pagamentos";
        $viewData['subtitle'] = "Cadastro dos Meios de Pagamentos";
        return view('payment.create', [
            'viewData' => $viewData,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payment = new Payment();
        $payment->name = $request->name;
        $response = $payment->save();

        if ($response) {
            return redirect()->route('payment.index')->with('message', 'Método de pagamento criado com sucesso!');
        }

        return back();
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


    public function getQueueData()
    {

        $payments = financeiro::where('status', 4)->get();

        foreach ($payments as $payment) {
            \App\Jobs\YapayPagamento::dispatch($payment->token_transaction);
        }
    }
}
