<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orders = Orders::Ordersjoin();
        $userML = token::where('user_id', Auth::user()->id)->first();

        $viewData = [];
        $viewData['title'] = "AfiliDrop Dashboard";
        $viewData['subtitle'] = "Dashboard";
        $viewData['totalMonth'] = $this->getTotalMonth();
        $viewData['totalDay'] = $this->getTotalDay();
        $viewData['index'] = 0;
        $viewData['orders'] = $orders;
        if($userML){
            $viewData['mercadolivre'] = $userML;
        }
        return view('home')->with('viewData', $viewData);
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
