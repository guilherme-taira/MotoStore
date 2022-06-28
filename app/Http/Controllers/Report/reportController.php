<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Items;
use App\Models\Orders;
use App\Models\Payment;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class reportController extends Controller
{
    public function generatePDF($dados){
        $pdf = App::make('dompdf.wrapper');
        $orders = $dados;
        $pdf->loadView('reports.report',['orders' => $orders]);

        return $pdf->stream();
    }

    public function generateProductsPDF($dados){
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A3');
        $data = $dados;
        $pdf->loadView('reports.reportProducts',[
        'data' => $data,
        ]);

        return $pdf->stream();
    }

    public function generateReporter(){

        $produtcs = Products::all();
        $payments = Payment::all();

        return view('reports.index',[
            'products' => $produtcs,
            'payments' => $payments
        ]);
    }

    public function generating(Request $request){

        $dados = Orders::OrderJoinGenerateReport($request->name,$request->formPayment,$request->dataInicial,$request->dataFinal);
        return $this->generatePDF($dados);
    }

    public function generatingProduct(Request $request){

        $dados = Items::OrderJoinGenerateReportProduct($request->product,$request->formPayment,$request->dataInicial,$request->dataFinal);
        return $this->generateProductsPDF($dados);
    }
}

