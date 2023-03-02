<?php

namespace App\Http\Controllers\Bancario;

use App\Http\Controllers\Controller;
use App\Models\bancario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BancarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Dados Bancários";
        $viewData['subtitle'] = 'Dados Bancários';
        $viewData['bancario'] = bancario::where('user_id', Auth::user()->id)->get();

        return view('bancario.index', [
            'viewData' => $viewData,
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
        $viewData['title'] = "Dados Bancários";
        $viewData['subtitle'] = 'Cadastro Dados Bancários';

        return view('bancario.create', [
            'viewData' => $viewData,
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
            "name" => "required|min:3",
            "bank" => "required",
            "agencia" => "required|min:3",
            "acount" => "required|min:3",
            "cpnj" => "required|min:3",
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $newBancario = new bancario();
        $newBancario->Banco = $request->bank;
        $newBancario->agencia = $request->agencia;
        $newBancario->conta = $request->acount;
        $newBancario->nome = $request->name;
        $newBancario->cpf = $request->cpnj;
        $newBancario->user_id = Auth::user()->id;
        $newBancario->save();

        $viewData = [];
        $viewData['title'] = "Dados Bancários";
        $viewData['subtitle'] = 'Cadastro Dados Bancários';
        $viewData['bancario'] = bancario::where('user_id', Auth::user()->id)->get();

        return redirect()->route('bancario.index')->with([
            'msg_success' => "Dados Bancário Cadastrado com Sucesso!",
            'viewData' => $viewData,
        ]);
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
        $viewData = [];
        $viewData['title'] = "Dados Bancários";
        $viewData['subtitle'] = 'Cadastro Dados Bancários';
        $viewData['bancario'] = bancario::where('id', $id)->first();

        return view('bancario.edit', [
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
            "name" => "required|min:3",
            "bank" => "required",
            "agencia" => "required|min:3",
            "acount" => "required|min:3",
            "cpnj" => "required|min:3",
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        bancario::where('id', $id)->update(['nome' => $request->name, 'Banco' => $request->bank, 'agencia' => $request->agencia, 'conta' => $request->acount]);

        $viewData = [];
        $viewData['title'] = "Dados Bancários";
        $viewData['subtitle'] = 'Dados Bancários';
        $viewData['bancario'] = bancario::where('user_id', Auth::user()->id)->get();

        return redirect()->route('bancario.index')->with([
            'msg_success' => "Dados Bancário atualizado com Sucesso!",
            'viewData' => $viewData,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $flight = bancario::find($id);
        $flight->delete();

        $viewData = [];
        $viewData['title'] = "Dados Bancários";
        $viewData['subtitle'] = 'Dados Bancários';
        $viewData['bancario'] = bancario::where('user_id', Auth::user()->id)->get();

        return redirect()->route('bancario.index')->with([
            'msg_success' => "Dados Bancário apagado com sucesso!",
            'viewData' => $viewData,
        ]);
    }
}
