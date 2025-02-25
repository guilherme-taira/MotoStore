<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Http\Controllers\Orders\MercadoLivre\MercadolivreOrderController;
use App\Models\order_site;
use App\Models\Orders;
use App\Models\token;
use App\Models\User;
use App\Notifications\PushNotification;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

            try {
                $dataAtual = new DateTime();
                $userML = token::where('user_id', Auth::user()->id)->first();
                $newToken = new RefreshTokenController($request->access_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $userML->user_id_mercadolivre);
                $newToken->resource();
            } catch (\Exception $e) {
                Log::alert($e->getMessage());
            }

            $viewData = [];
            $viewData['title'] = "Afilidrop Dashboard";
            $viewData['subtitle'] = "Dashboard";
            // Defina a chave do cache
            $cacheKey = 'vendas';

            // Tempo em minutos que o cache será mantido
            $cacheTime = 2;

          // Verifique se já existe um cache
          if (Cache::has($cacheKey)) {
            // Se existir, recupere o resultado do cache
            $orders = Cache::get($cacheKey);
            Log::info('Cache encontrado para a chave: ' . $cacheKey);
        } else {
            Log::info('Cache não encontrado para a chave: ' . $cacheKey);
            // Se não existir, execute a query
            $orders = order_site::Ordersjoin(Auth::user()->id,$request);
            // Armazene o resultado no cache
            Cache::put($cacheKey, $orders, now()->addMinutes($cacheTime));
        }

        $viewData['orders'] = $orders;
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
