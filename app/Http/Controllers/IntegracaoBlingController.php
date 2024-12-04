<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IntegracaoBling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntegracaoBlingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $integracoes = IntegracaoBling::where('user_id', Auth::id())->get();
        return view('bling.index', compact('integracoes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bling.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'link' => 'nullable|url',
        ]);

        IntegracaoBling::create([
            'user_id' => Auth::id(),
            'access_token' => $request->access_token,
            'client_id' => $request->client_id,
            'client_secret' => $request->client_secret,
            'link' => $request->link,
        ]);


        return redirect()->route('bling.index')->with('success', 'Integração adicionada com sucesso!');
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
        $integracaoBling = IntegracaoBling::findOrFail($id);

        return view('bling.edit', compact('integracaoBling'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IntegracaoBling $bling)
    {
        // Validação dos campos
        $request->validate([
            'access_token' => 'required|string',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'link' => 'required|string',
        ]);

        // Atualizando os campos manualmente
        $bling->access_token = $request->access_token;
        $bling->client_id = $request->client_id;
        $bling->client_secret = $request->client_secret;
        $bling->link = $request->link;

        // Salvando no banco de dados
        $bling->save();

        // Redirecionando após o sucesso
        return redirect()->route('bling.index')->with('success', 'Integração atualizada com sucesso!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(IntegracaoBling $integracaoBling)
    {
        $this->authorize('delete', $integracaoBling); // Garantir que o usuário pode deletar
        $integracaoBling->delete();

        return redirect()->route('bling.index')->with('success', 'Integração excluída com sucesso!');
    }
}
