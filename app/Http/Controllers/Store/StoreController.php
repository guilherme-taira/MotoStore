<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\banner;
use App\Models\logo;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Embaleme";
        $viewData['subtitle'] = 'Embaleme';
        $viewData['products'] = Products::all();
        $viewData['bannersFix'] = banner::first();
        $viewData['banners'] = banner::where('id', '>', $viewData['bannersFix']->getId())->get();
        $viewData['logo'] = logo::first();

        return view('store.index')->with('viewData',$viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function setUser(Request $request){

        $request->session()->put('user', $request->user);
        $request->session()->put('payment', $request->PaymentId);
        $request->session()->put('datePayment',$request->datePayment);
        return redirect()->route('cart.purchase');
    }

}
