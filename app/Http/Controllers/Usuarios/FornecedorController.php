<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\categorias_forncedores;
use App\Models\sub_categoria_fornecedor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Lista de Fornecedores";
        $viewData['categorias'] = User::getAllUsers();

        return view("fornecedor.index",[
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
        $viewData['title'] = "Lista de Fornecedores";
        $viewData['categorias_fornecedor'] = categorias_forncedores::all();

        return view("fornecedor.create",[
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
            'nome' => 'required|min:5',
            'password' => 'required',
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // $newCategoria = new categorias_forncedores();
        // $newCategoria->name = $request->categoria;
        // $newCategoria->slug = $request->slug;
        // $newCategoria->descricao = $request->regiao;
        // $newCategoria->save();

        $user = new User();
        $user->name = $request->nome;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->user_id = $request->categoria;
        $user->save();


        return redirect()->route('fornecedores.index')->with('msg',"Fornecedor / Usuário Cadastrado com sucesso!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
        $viewData['title'] = "Lista de Fornecedores";
        $viewData['fornecedor'] = User::getUserById($id);

        $subcategorias = [];

        foreach (categorias_forncedores::all() as $value) {

            $subcategorias[$value->id] = [
                "nome" => $value->name,
                "subcategory" => sub_categoria_fornecedor::getAllCategory($value->id),
            ];
        }

        $viewData['subcategorias'] = $subcategorias;
        $viewData['id'] = $id;

        return view("fornecedor.edit",[
            'viewData' => $viewData,
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
            'email' => 'required|email',
            'nome' => 'required',
            'categoria' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::where('id',$id)->update([
            "name" => $request->nome,
            "user_subcategory" => $request->categoria,
            "password" => bcrypt($request->password),
        ]);

        return redirect()->route('fornecedores.index')->with('msg',"Fornecedor Atualizado com sucesso!");
    }

    public function filtrarPorNome(Request $request) {
        $fornecedores = User::where('name', 'like', '%' . $request->query('name') . '%')->get();
        return response()->json($fornecedores);
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
