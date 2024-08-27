<?php

namespace App\Http\Controllers\SaiuPraEntrega;

use App\Http\Controllers\Controller;
use App\Models\ShippingUpdate;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaiuPraEntregaMainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Fretes";
        $viewData['subtitle'] = "Compras Aliexpress";

        $id = token::where('user_id','=',Auth::user()->id)->first();

        if($id){
            $data = ShippingUpdate::getDataByIdMeli($id->user_id_mercadolivre);
            echo "<pre>";
         // Array para armazenar as chaves dos campos que possuem valor 1
            $wasFields = [];

            foreach ($data as $key => $value) {
                // Verifica se o campo começa com 'was_' e tem valor 1
                if (strpos($key, 'was_') === 0 && $value == 1) {
                    $wasFields[] = $key;
                }
            }

            // Exibindo as chaves que possuem valor 1
            print_r($wasFields);
            // $viewData['shipping']
        }else{
            $viewData['shipping'] = [];
        }

        // return view('fretes.index',
        // ['viewData' => $viewData]
        // );
    }


    function extrairNumeros($texto) {
        // Expressão regular para encontrar todos os dígitos
        preg_match('/\d+/', $texto, $matches);
        // Retorna o primeiro conjunto de números encontrado
        return $matches[0];
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
