<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GenerateCode\GetCodeController;
use App\Http\Controllers\image\image;
use App\Models\banner;
use App\Models\categorias;
use App\Models\categorias_forncedores;
use App\Models\logo;
use App\Models\order_site;
use App\Models\product_site;
use App\Models\Products;
use App\Models\sub_categoria_fornecedor;
use App\Models\sub_category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // $id = User::getProducts(Auth::user()->user_subcategory);
        $viewData = [];
        $viewData['title'] = "Embaleme";
        $viewData['subtitle'] = '';
        $viewData['products'] = Products::getProducts();
        $viewData['bannersFix'] = banner::first();
        $viewData['banners'] = banner::where('id', '>', $viewData['bannersFix']->getId())->get();
        $viewData['logo'] = logo::first();

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $subcategorias = [];

        foreach (categorias_forncedores::all() as $value) {

            $subcategorias[$value->id] = [
                "nome" => $value->name,
                "subcategory" => sub_categoria_fornecedor::getAllCategory($value->id),
            ];
        }

        $viewData['subcategorias'] = $subcategorias;

        // if (Auth::user()->user_subcategory) {
        //     $viewData['bloqueado'] = 1;
        // }

        $viewData['categorias'] = $categorias;
        return view('store.index')->with('viewData', $viewData);
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

    public function setUser(Request $request)
    {
        $request->session()->put('user', $request->user);
        $request->session()->put('payment', $request->PaymentId);
        $request->session()->put('datePayment', $request->datePayment);
        return redirect()->route('cart.purchase');
    }

    public function getValueGraphic15days(Request $request){
        return response()->json(order_site::getDataValues($request->id));
    }

    public function getValueGraphic6Mounth(Request $request){
        return response()->json(order_site::getDataLast6Mounth($request->id));
    }




    public function thanks()
    {
        return view('store.thanks');
    }

    public function getCode(Request $request)
    {
        $getNewCode = new GetCodeController("authorization_code", "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $request->code, "https://afilidrop.com.br/home", $request->id);
        $data = $getNewCode->resource();
        return response()->json(["dados" => $data]);
    }

    public function getCodeUpMineracao(Request $request)
    {
        $getNewCode = new GetCodeController("authorization_code", "159024264044117", "EFQQgu53eaB0y5jdOi44o8morHq9WTPd", $request->code, "https://afilidrop.com.br/conta-integrada", $request->id);
        $data = $getNewCode->resource();
        return response()->json(["dados" => $data]);
    }

    public function getCodeBling(Request $request)
    {
        $getNewCode = new GetCodeController("authorization_code", "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $request->code, "https://afilidrop.com.br/home", $request->id);
        $data = $getNewCode->resource();
        return response()->json(["dados" => $data]);
    }
}
