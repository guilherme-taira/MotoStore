<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shopify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopifyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = [
            [
                'id' => 3631,
                'date' => 'Hoje, às 13:33',
                'client' => 'Não há cliente',
                'channel' => 'Loja virtual',
                'total' => 'R$10,93',
                'payment_status' => 'Pagamento pendente',
                'order_status' => 'Não processado',
                'items' => '1 item',
                'delivery_status' => ''
            ],
            [
                'id' => 3630,
                'date' => 'Hoje, às 13:32',
                'client' => 'Não há cliente',
                'channel' => 'Loja virtual',
                'total' => 'R$10,93',
                'payment_status' => 'Pago',
                'order_status' => 'Não processado',
                'items' => '1 item',
                'delivery_status' => ''
            ],
            [
                'id' => 3629,
                'date' => 'Hoje, às 13:20',
                'client' => 'Não há cliente',
                'channel' => 'Loja virtual',
                'total' => 'R$10,90',
                'payment_status' => 'Pago',
                'order_status' => 'Em andamento',
                'items' => '1 item',
                'delivery_status' => ''
            ],
            // Adicione mais pedidos conforme necessário
        ];

        return view('shopify.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $shopify = $user->shopify;


        $viewData = [];
        $viewData['shopify'] = $shopify;
        $created = isset($shopify->created_at) ? $shopify->created_at : "";

        if($shopify){
            $viewData['integrado'] = "Conta Integrada: " . $created;
        }else{
            $viewData['integrado'] = "";
        }

        return view('shopify.create',[
            'viewData' => $viewData
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

        try {
            $request->validate([
                'apiKey' => 'required|string',
                'token' => 'required|string',
                'name_store' => 'required|string',
                'email' => 'required|string',
                'telefone' => 'required|string'
            ]);

            $comunicando = isset($request->comunicando) ? 1 : 0;
            $user = Auth::user();
            // Verifica se já existe um registro shopify para este usuário
            Shopify::updateOrCreate(
                ['user_id' => $user->id],
                ['apiKey' => $request->apiKey, 'token' => $request->token,
                 'name_loja' => $request->name_store,'comunicando' => $comunicando,
                 'email' => $request->email, 'telefone' => $request->telefone]
            );

        } catch (\Throwable $th) {
            print_r($th->getMessage());
        }

        return redirect()->route('shopify.create');
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
