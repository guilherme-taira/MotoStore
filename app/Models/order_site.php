<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class order_site extends Model
{
    use HasFactory;

    protected $table = "order_site";

    public static function VerificarVenda($numero): bool
    {
        $data = order_site::where('numeropedido', '=', $numero)->first();
        if ($data) {
            return true;
        } else {
            return false;
        }
    }

    public static function getDataLast6Mounth($id){

        $last6Months = order_site::selectRaw('DATE_FORMAT(dataVenda, "%Y-%m") AS mes, SUM(valorVenda) AS total_vendas')
        ->join('pivot_site', 'order_site.id', '=', 'pivot_site.order_id')
        ->where('id_user', '=', $id)
        ->whereBetween('dataVenda', [Carbon::now()->subMonths(6), Carbon::now()])
        ->orderByRaw('YEAR(dataVenda), MONTH(dataVenda)') // Ordena por ano e mês
        ->groupByRaw('YEAR(dataVenda), MONTH(dataVenda)')
        ->get();

    // Formata os resultados para representar os meses por extenso
    $resultadosFormatados = $last6Months->map(function ($item) {
        // Obtem o nome do mês por extenso
        $nomeMes = Carbon::createFromFormat('Y-m', $item->mes)->format('F');
        return $nomeMes;
    });

        $dias = [];
        $valor = [];
        $all = [];
        array_push($dias,$resultadosFormatados->toArray());
        foreach ($last6Months as $key => $value) {
            array_push($valor,  str_replace(',','.',number_format($value->total_vendas, 2, ',', '')));
        }

        array_push($all,$dias[0],$valor,max($valor));
        return $all;
    }



    public static function getLast15() {
        $daysArray = [];
        for ($i = 0; $i < 15; $i++) {
            $day = date('Y-m-d', strtotime("-$i days"));
            array_push($daysArray, $day);
        }
        return $daysArray;
    }

    public static function getDataValues($id){
        $last15Days = order_site::selectRaw('created_at, SUM(valorVenda) AS total_vendas')
        ->join('pivot_site','order_site.id','=','pivot_site.order_id')
        ->where('id_user','=',$id)
        ->whereBetween('created_at', [now()->subDays(5), now()])
        ->groupBy('dataVenda')
        ->get();

        print_r($last15Days);
        // $dias = [];
        // $valor = [];
        // $all = [];

        // foreach ($last15Days as $key => $value) {
        //     array_push($dias,$value->dataVenda);
        //     array_push($valor,  str_replace(',','.',number_format($value->total_vendas, 2, ',', '')));
        // }

        // array_push($all,$dias,$valor);
        // return $all;
    }

    public static function getFormattedDate($date) {
        // Converte a data para o formato 'Y-m-d' se necessário
        $formattedDate = date('Y-m-d', strtotime($date));

        // Obtém o mês abreviado
        $monthAbbreviated = date('M', strtotime($formattedDate));

        $meses = [
            "January" => "Janeiro",
            "February" => "Fevereiro",
            "March" => "Março",
            "April" => "Abril",
            "May" => "Maio",
            "June" => "Junho",
            "July" => "Julho",
            "August" => "Agosto",
            "September" => "Setembro",
            "October" => "Outubro",
            "November" => "Novembro",
            "December" => "Dezembro"
        ];

        if(isset($meses[$monthAbbreviated])){
            $mesTraduzido = $meses[$monthAbbreviated];
        }
        // Obtém o dia do mês
        $dayOfMonth = date('j', strtotime($formattedDate));

        // Formata a data como "M DIA"
        $formattedDate = $mesTraduzido . ',' . $dayOfMonth;

        return $formattedDate;
    }

    public static function Ordersjoin($user_id, Request $request)
    {

        $data = DB::table('pivot_site')
            ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('status', 'order_site.status_id', '=', 'status.id')
            // ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
            // ->join('products', 'product_site.seller_sku', '=', 'products.id')
            ->select("*")
            ->orderBy('order_site.id', 'desc')
            ->where('users.id', $user_id);

        if ($request->nome) {
            $data->where('order_site.cliente', 'like', '%' . $request->nome . '%');
        }

        if ($request->cpf) {
            $data->where('users.cpf', 'like', '%' . $request->cpf . '%');
        }

        if ($request->npedido) {
            $data->where('pivot_site.order_id', $request->npedido);
        }

        if ($request->datainicial && $request->datafinal) {
            $data->whereBetween('order_site.dataVenda', [$request->datainicial, $request->datafinal]);
        }
        $dados = $data->paginate(10)->appends($request->all());
        return $dados;
    }

    public static function getOrderjoin($id)
    {
        $data = DB::table('pivot_site')
            // ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
            // ->join('products', 'product_site.seller_sku', '=', 'products.id')
            ->select('*')
            ->where('order_site.id', $id)->get();
        return $data;
    }

    public static function OrdersMercadoLivreMounth($user, $monthCurrent)
    {
        $data = DB::table('pivot_site')
            ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('product_site','pivot_site.product_id','=','product_site.id')
            ->where('id_user', $user)
            ->where('order_site.created_at', 'like', '%' . now()->format('Y-m') . '%')->sum('valorVenda');
        return $data;
    }

    public static function totalVendasMes($user)
    {
        $data = DB::table('pivot_site')
            // ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            // ->join('product_site','pivot_site.product_id','=','product_site.id')
            ->where('id_user', $user)
            ->where('order_site.created_at', 'like', '%' . now()->format('Y-m') . '%')->get();
        return count($data);
    }


    public static function totalVendasDia($user)
    {
        $data = DB::table('pivot_site')
            // ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            // ->join('product_site','pivot_site.product_id','=','product_site.id')
            ->where('id_user', $user)
            ->where('order_site.created_at', 'like', '%' . now()->format('Y-m-d') . '%')->get();
        return count($data);
    }

    public static function getOrderByDashboard(Request $request){

        $data = DB::table('pivot_site')
        ->join('users', 'pivot_site.id_user', '=', 'users.id')
        ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
        ->select(DB::raw('SUM(order_site.valorVenda) as totalValorVenda'),DB::raw('SUM(order_site.fee) as totalValorTarifa'),'dataVenda')
        ->orderBy('order_site.dataVenda', 'asc')
        ->where('users.id', $request->user_id);

        if ($request->dataInicial && $request->dataFinal) {
            $data->whereBetween('order_site.dataVenda', [$request->dataInicial, $request->dataFinal]);
        }

        $data->groupBy('order_site.dataVenda');
        $dados = $data->get();

        $valor = [];
        $tarifa = [];
        $datavenda = [];
        $viewData = [];
        // Formatar o número

        foreach ($dados as $dado) {
            array_push($valor,round($dado->totalValorVenda));
            array_push($tarifa,round($dado->totalValorTarifa));
            array_push($datavenda,$dado->dataVenda);
        }

        $viewData['valor'] = $valor;
        $viewData['tarifa'] = $tarifa;
        $viewData['dataVenda'] = $datavenda;

        return $viewData;
    }

    public static function OrdersMercadoLivreDay($user, $monthCurrent)
    {
        $data = DB::table('pivot_site')
            // ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->where('id_user', $user)
            ->where('order_site.created_at', 'like', '%' . now()->format('Y-m-d') . '%')->sum('valorVenda');
        return $data;
    }



    public static function OrdersMercadoLivreDayQtd($user)
    {
        $data = DB::table('pivot_site')
            // ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->where('id_user', $user)
            ->where('order_site.created_at', 'like', '%' . now()->format('Y-m-d') . '%')
            ->get();
        return count($data);
    }

    public static function OrdersMercadoLivreDayValorMedio($user)
    {
        $data = DB::table('pivot_site')
            // ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->where('id_user', $user)
            ->where('order_site.created_at', 'like', '%' . now()->format('Y-m-d') . '%')->sum('valorVenda');

        return $data;
    }
}
