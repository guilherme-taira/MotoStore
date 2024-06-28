<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Http\Controllers\Orders\MercadoLivre\MercadolivreOrderController;
use App\Models\order_site;
use App\Models\Orders;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
set_time_limit(0);
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
    public function index(Request $request)
    {


        // PEGA AS VENDAS DO SISTEMA
        $orders = order_site::Ordersjoin(Auth::user()->id,$request);
        $viewData = [];
        $viewData['title'] = "Afilidrop Dashboard";
        $viewData['subtitle'] = "Dashboard";
        $viewData['orders'] = $orders;
        // $viewData['totalMonth'] = $this->getTotalMonth(Auth::user()->id);
        // $viewData['totalDay'] = $this->getTotalDay(Auth::user()->id);
        // $viewData['index'] = 0;
        // $viewData['orders'] = $orders;
        // $viewData['mercadolivre'] = $userML;
        return view('home')->with('viewData', $viewData);
    }

    public function getTotalMonth($user)
    {
        $monthCurrent = new DateTime();
        $total = order_site::OrdersMercadoLivreMounth($user,$monthCurrent);
        return $total;
    }

    public function getTotalDay($user)
    {
        $dayCurrent = new DateTime();
        $total = order_site::OrdersMercadoLivreDay($user,$dayCurrent);
        return $total;
    }
}
