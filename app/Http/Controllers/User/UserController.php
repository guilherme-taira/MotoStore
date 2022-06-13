<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $viewData = [];
        $viewData['title'] = 'MotoStore Criar Novo Usuário';
        $viewData['subtitle'] = 'Novo Usuário';

        return view('user.create')->with('viewData',$viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(),[
            'name' => 'required|max:255',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:4'
        ]);


        if ($validator->fails()) {
            return redirect('user/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        if($newUser){
            return back()->with('message','Cadastrado com Sucesso!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id',$id)->first();

        if($user){
            print_r($user->name);
        }

        $orders = $user->orders()->get();

        foreach ($orders as $order) {
            echo "<hr>ID : ".$order->id . "TOTAL R$: ". $order->total. "NOME: " . $user->name. "<hr>";
        }

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
