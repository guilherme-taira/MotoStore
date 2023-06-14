<?php

namespace App\Http\Controllers\categoriaFornecedor;

use App\Http\Controllers\Controller;
use App\Models\categorias_forncedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class categoriasFornecedor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Lista de Categorias de Fornecedores";
        $viewData['categorias'] = categorias_forncedores::getAllCategoria();

        return view("categoriasfornecedor.index",[
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
        $viewData['title'] = "Cadastro de Categoria dos Fornecedore";
        $viewData['categorias'] = categorias_forncedores::getAllCategoria();

        return view("categoriasfornecedor.create",[
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
            'categoria' => 'required|min:5',
            'slug' => 'required',
            'regiao' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $newCategoria = new categorias_forncedores();
        $newCategoria->name = $request->categoria;
        $newCategoria->slug = $request->slug;
        $newCategoria->descricao = $request->regiao;
        $newCategoria->save();

        return redirect()->route('categoriasfornecedor.index')->with('msg',"Categoria Fornecedor Cadastrado com sucesso!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $viewData = [];
        $viewData['title'] = "Cadastro de Categoria dos Fornecedore";
        $viewData['categorias'] = categorias_forncedores::where('id',$id)->first();

        return view("categoriasfornecedor.edit",[
            'viewData' => $viewData
        ]);
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
        $validator = Validator::make($request->all(), [
            'categoria' => 'required|min:5',
            'slug' => 'required',
            'regiao' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        categorias_forncedores::where('id',$id)->update([
            "name" => $request->categoria,
            "slug" => $request->slug,
            "descricao" => $request->regiao
        ]);

        return redirect()->route('categoriasfornecedor.index')->with('msg',"Categoria Atualizada com sucesso!");

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
