<?php

namespace App\Http\Controllers\Logo;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Yapay\Pagamentos\AtualizaPagamento;
use App\Http\Controllers\Yapay\Pix;
use App\Http\Controllers\Yapay\ProdutoMercadoLivre;
use App\Models\logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class logoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Logo Edit";
        $viewData['subtitle'] = "Logo da Sua Marca";
        $viewData['logos'] = logo::all();

        return view('marketing.logo.index', [
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
        $viewData['title'] = "Logo Edit";
        $viewData['subtitle'] = "Logo da Sua Marca";

        return view('marketing.logo.create', [
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
            'name' => 'required|min:5',
            'image' => 'required|file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $logo = new logo();
        $logo->name = $request->name;
        $logo->image = 'image.png';
        $logo->save();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $file->storeAs('LogoEmbaleme/' . $logo->getId(), $filename, 's3');
            $logo->setImage($filename);
            $logo->save();
        }

        return redirect()->route('logos.index');
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
