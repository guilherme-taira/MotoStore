<?php

namespace App\Http\Controllers\Configuracao;

use App\Http\Controllers\Controller;
use App\Models\endereco;
use App\Models\logo;
use App\Models\token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use MercadoPago\SDK as ML;
use MercadoPago\Preference as MercadoPreference;
use MercadoPago\Item as MercadoItem;

class configuracaoController extends Controller
{

    public function Configuracoes()
    {

        $viewData = [];
        $viewData['title'] = "Configurações";
        $viewData['subtitle'] = "Configurações do Usuário";
        $viewData['user'] = User::where('id', Auth::user()->id)->first();
        $viewData['logo'] = logo::first();


        return view('configuracao.index', [
            'viewData' => $viewData
        ]);
    }

    public function editarPerfil(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'telefone' => 'required|numeric',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::where('id', Auth::user()->id)->update([
            'name' => $request->nome,
            'phone' => $request->telefone,
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('settings')->with('msg', 'Perfil Editado com sucesso!');
    }

    public function address()
    {
        $viewData = [];
        $viewData['title'] = "Endereço";
        $viewData['subtitle'] = "Endereços do Usuário";
        $viewData['user'] = User::where('id', Auth::user()->id)->first();
        $viewData['logo'] = logo::first();
        $viewData['enderecos'] = endereco::where('user_id', Auth::user()->id)->paginate(10);

        return view('configuracao.address', [
            'viewData' => $viewData
        ]);
    }

    public function create()
    {
        $viewData = [];
        $viewData['title'] = "Novo Endereço";
        $viewData['subtitle'] = "Novo Endereço do Usuário";
        $viewData['user'] = User::where('id', Auth::user()->id)->first();
        $viewData['logo'] = logo::first();

        return view('configuracao.create', [
            'viewData' => $viewData
        ]);
    }

    public function edit(Request $request, $id)
    {
        $viewData = [];
        $viewData['title'] = "Editar Endereço";
        $viewData['subtitle'] = "editar Endereço do Usuário";
        $viewData['user'] = endereco::where('id', $id)->first();
        $viewData['logo'] = logo::first();

        return view('configuracao.edit', [
            'viewData' => $viewData
        ]);
    }

    public function deletar(Request $request, $id)
    {
        $data = endereco::findOrFail($id);
        $data->delete();
        return redirect()->route('address')->with('msg', 'Endereço deletado com sucesso!');
    }

    public function atualizar(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'cep' => 'required|min:8|max:8',
            'logradouro' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'numero' => 'required|numeric',
            'userid' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        endereco::where('id', $id)->update([
            'cep' => $request->cep,
            'address' => $request->logradouro,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'numero' => $request->numero
        ]);

        return redirect()->route('address')->with('msg', 'Endereço Editado com sucesso!');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cep' => 'required|min:8|max:8',
            'logradouro' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'numero' => 'required|numeric',
            'userid' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $Endereco = new endereco();
        $Endereco->cep = $request->cep;
        $Endereco->address = $request->logradouro;
        $Endereco->bairro = $request->bairro;
        $Endereco->cidade = $request->cidade;
        $Endereco->numero = $request->numero;
        $Endereco->complemento = isset($request->complemento) ? $request->complemento : "N/D";
        $Endereco->user_id = $request->userid;
        $Endereco->save();

        return redirect()->route('address')->with('msg', 'Endereço Cadastrada com sucesso!');
    }


    public function integracaoMeli(){
        $viewData = [];
        $viewData['title'] = "Integração Mercado Livre";
        $viewData['integrado'] = token::where('user_id',Auth::user()->id)->first();
        return view('mercadolivre.integracao',[
            'viewData' => $viewData
        ]);
    }
}
