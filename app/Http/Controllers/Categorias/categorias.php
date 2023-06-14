<?php

namespace App\Http\Controllers\Categorias;

use App\Http\Controllers\Controller;
use App\Models\categorias as ModelsCategorias;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class categorias extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Categorias";
        $viewData['subtitle'] = "Categorias dos Produtos";
        $viewData['categorias'] = ModelsCategorias::all();
        return view('categorias.index')->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $viewData = [];
        $viewData['title'] = "Cadastrar Categoria";
        $viewData['subtitle'] = "Categoria";
        return view('categorias.create')->with('viewData', $viewData);
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
            "nome" => "required|min:3",
            "slug" => "required|min:3",
            "descricao" => "required|min:3",
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $categoria = new ModelsCategorias();
        $categoria->SetNome($request->nome);
        $categoria->SetSlug($request->slug);
        $categoria->SetDescricao($request->descricao);
        $categoria->save();

        return redirect()->route('categorias.index')->with('msg', 'Categoria Cadastrada com sucesso!');
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
        $viewData['title'] = "Cadastrar Categoria";
        $viewData['subtitle'] = "Categoria";
        $viewData['product'] = ModelsCategorias::where('id', $id)->first();

        return view('categorias.edit', ['id' => $id])->with('viewData', $viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $viewData = [];
        $viewData['title'] = "Cadastrar Categoria";
        $viewData['subtitle'] = "Categoria";
        $viewData['categoria'] = ModelsCategorias::where('id', $id)->first();

        return view('categorias.edit')->with([
            'viewData' =>  $viewData,
            'id' => $id,
        ]);
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
            "nome" => "required|min:3",
            "slug" => "required|min:3",
            "descricao" => "required|min:3",
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ModelsCategorias::where('id', $id)->update([
            'nome' => $request->nome,
            'slug' => $request->slug,
            'descricao' => $request->descricao
        ]);

        return redirect()->route('categorias.index')->with('msg', 'Categoria Atualizada com sucesso!');
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


    // ROUTE API

    public function getAllCategories(){
       $dados = ModelsCategorias::all();
       return response()->json(["result" => $dados]);
    }

    public function getAllProductByCategorieID(Request $request){
        $product = Products::where('categoria', '=', $request->id)->get();
        return response()->json(["ResultCategoria" => $product]);
     }
}
