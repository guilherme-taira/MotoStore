<?php

namespace App\Http\Controllers\bi;

use App\Http\Controllers\Controller;
use App\Models\order_site;
use App\Models\Products;
use App\Models\SalesReport;
use App\Models\User;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function vendas()
    {
        // Retorna os dados em JSON
        $dados = SalesReport::select('*')->get();
        return response()->json($dados);
    }

     public function produtosBiWeb()
    {
        // Retorna os dados em JSON
        $dados = Products::select('id','title','priceWithFee','price','available_quantity','afiliados','fornecedor_id')->get();
        return response()->json($dados);
    }

    public function users(){
        // Retorna os dados em JSON
        $dados = User::select('id','name','email')->get();
        return response()->json($dados);
    }

    public function orders(){
        // Retorna os dados em JSON
       $dados = order_site::select(
        'order_site.id',
        'order_site.numeropedido',
        'order_site.valorVenda',
        'order_site.dataVenda',
        'pivot_site.id_user'
        )
        ->join('pivot_site', 'pivot_site.order_id', '=', 'order_site.id')
        ->get();

        return response()->json($dados);
    }





}
