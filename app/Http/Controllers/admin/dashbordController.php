<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\token;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class dashbordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userML = token::where('user_id',Auth::user()->id)->first();
        $viewData['mercadolivre'] = $userML;
        $orders = Orders::Ordersjoin();

        $viewData = [];
        $viewData['title'] = "MotoStore Dashboard";
        $viewData['subtitle'] = "Dashboard";
        $viewData['totalMonth'] = $this->getTotalMonth();
        $viewData['totalDay'] = $this->getTotalDay();
        $viewData['index'] = 0;
        $viewData['orders'] = $orders;
        $viewData['mercadolivre'] = $userML;

        return view('home')->with('viewData',$viewData);
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

    public function getTotalMonth()
    {

        $monthCurrent = new DateTime();
        $total = Orders::where('created_at', 'like', '%' . $monthCurrent->format('Y-m') . '%')->sum('total');
        return $total;
    }

    public function getTotalDay()
    {
        $monthCurrent = new DateTime();
        $total = Orders::where('created_at', 'like', '%' . $monthCurrent->format('Y-m-d') . '%')->sum('total');
        return $total;
    }
}
