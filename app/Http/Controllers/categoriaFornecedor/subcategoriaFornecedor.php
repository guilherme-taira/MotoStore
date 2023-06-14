<?php

namespace App\Http\Controllers\categoriaFornecedor;

use App\Http\Controllers\Controller;
use App\Models\categorias_forncedores;
use App\Models\sub_categoria_fornecedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class subcategoriaFornecedor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Lista de Sub Categorias de Fornecedores";
        $viewData['categorias'] = sub_categoria_fornecedor::getAllSubCategoria();

        return view("subcategoriafornecedor.index", [
            'viewData' => $viewData
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
        $viewData['title'] = "Cadastro de Sub Categoria dos Fornecedore";
        $viewData['categorias'] = categorias_forncedores::getAllCategoria();

        return view("categoriasfornecedor.create", [
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'categoria' => 'required',
            'regiao' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $newCategoria = new sub_categoria_fornecedor();
        $newCategoria->name = $request->name;
        $newCategoria->categoria_principal = $request->categoria;
        $newCategoria->descricao = $request->regiao;
        $newCategoria->save();

        return redirect()->route('subcategoriafornecedor.index')->with('msg', "SubCategoria Cadastrado com sucesso!");
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
