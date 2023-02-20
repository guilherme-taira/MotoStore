<?php

namespace App\Http\Controllers\subcategoria;

use App\Http\Controllers\Controller;
use App\Models\banner;
use App\Models\categorias;
use App\Models\logo;
use App\Models\Products;
use App\Models\sub_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Cadastrar Sub-Categoria";
        $viewData['subtitle'] = "Categoria";
        $viewData['subcategoria'] = sub_category::paginate(10);
        return view('subcategoria.index')->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $viewData = [];
        $viewData['title'] = "Cadastrar Sub-Categoria";
        $viewData['subtitle'] = "Categoria";
        $viewData['categorias'] = categorias::all();
        return view('subcategoria.create')->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        print_r($request->all());
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

        $subcategoria = new sub_category();
        $subcategoria->name = $request->nome;
        $subcategoria->slug = $request->slug;
        $subcategoria->descricao = $request->descricao;
        $subcategoria->id_categoria = $request->categoria;
        $subcategoria->save();

        return redirect()->route('subcategorias.index')->with('msg', 'Categoria Cadastrada com sucesso!');
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

    public function getProductByCategory(Request $request){

        $viewData = [];
        $viewData['title'] = "Embaleme";
        $viewData['subtitle'] = sub_category::getName($request->categoryId);
        $viewData['products'] = Products::productBySubCategory($request->categoryId);
        $viewData['logo'] = logo::first();

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $viewData['categorias'] = $categorias;

        return view('store.index')->with('viewData', $viewData);
    }
}
