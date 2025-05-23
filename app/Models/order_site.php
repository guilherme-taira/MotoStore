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

    protected $fillable = [
        'local',
        'valorVenda',
        'valorProdutos',
        'dataVenda',
        'cliente',
        'status_id',
        'preferenceId',
        'external_reference',
        'status_mercado_livre',
        'id_pagamento',
        'link_pagamento',
        'fee',
        'buyer',
        'numeropedido'
    ];

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
        $last15Days = order_site::selectRaw('order_site.created_at, SUM(valorVenda) AS total_vendas')
        ->join('pivot_site','order_site.id','=','pivot_site.order_id')
        ->where('id_user','=',$id)
        ->whereBetween('order_site.created_at', [now()->subDays(30), now()])
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


    public static function getUserByOrder($orderid){

    }

    public static function Ordersjoin($user_id, Request $request) {

        $data = DB::table('pivot_site')
            ->join('users', 'pivot_site.id_user', '=', 'users.id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('status', 'order_site.status_id', '=', 'status.id')
            ->leftJoin('financeiro', function ($join) {
                $join->on('order_site.external_reference', '=', 'financeiro.token_transaction')
                     ->where('order_site.external_reference', '!=', 'N/D');
            })
            ->select(
                'users.email as email',
                'pivot_site.*',
                'users.id as user_id',
                'users.name as user_name',
                'users.cpf as user_cpf',
                'order_site.*',
                'order_site.id as id_site',
                'order_site.created_at as order_created_at',
                'users.created_at as users_created_at',
                'pivot_site.created_at as pivot_site_created_at',
                'status.nome as status_name', // Substitua 'nome' pelo nome correto
                'financeiro.valor as financeiro_valor'
            )
            ->orderBy('order_site.id', 'desc')
            ->where('users.id', $user_id);

        // Filtro por nome do cliente (campo 'cliente' na 'order_site')
        if ($request->nome) {
            $data->where('order_site.cliente', 'like', '%' . $request->nome . '%');
        }

        // Filtro por CPF (se está na tabela 'users')
        if ($request->cpf) {
            $data->where('users.cpf', 'like', '%' . $request->cpf . '%');
        }

        // Filtro por número do pedido (na tabela 'pivot_site')
        if ($request->npedido) {
            $data->where('order_site.numeropedido', $request->npedido);
        }

        // Filtro por intervalo de datas (campo 'dataVenda' na tabela 'order_site')
        if ($request->datainicial && $request->datafinal) {
            $data->whereBetween('order_site.dataVenda', [$request->datainicial, $request->datafinal]);
        }

        // Filtro por status (se precisar filtrar pelo status)
        if ($request->status) {
            $data->where('order_site.status_id', $request->status);
        }

        // Adiciona paginação e preserva os filtros nas próximas páginas
        $dados = $data->paginate(10)->appends($request->all());

        return $dados;
    }

    public static function getOrderjoin($id)
    {
        $data = DB::table('pivot_site')
            ->join('token', 'pivot_site.id_user', '=', 'token.user_id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
            // ->join('products', 'product_site.seller_sku', '=', 'products.id')
            ->select('*')
            ->where('order_site.id', $id)->get();
        return $data;
    }

    public static function getOrderjoinApi($id)
    {
     $data = DB::table('pivot_site')
                ->join('token', 'pivot_site.id_user', '=', 'token.user_id')
                ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
                ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
                ->join('financeiro', 'order_site.id', '=', 'financeiro.order_id')
                ->select('*','financeiro.id as finId')
                ->where('pivot_site.id_user', $id)
                ->orderBy('pivot_site.created_at','desc')
                ->paginate(10);
            return $data;
    }

    public static function getOrderjoinApiDespachar($id)
    {
        $data = DB::table('pivot_site')
            ->join('token', 'pivot_site.id_user', '=', 'token.user_id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
            ->join('products','product_site.seller_sku','=','products.id')
            ->join('financeiro', 'order_site.id', '=', 'financeiro.order_id')
            ->join('users','pivot_site.id_user','users.id')
            ->select('*','financeiro.id as finId')
            ->where('financeiro.user_id', $id)
            ->where('status_envio','=',1)
            ->orderBy('pivot_site.created_at','desc')
            ->paginate(10);
        return $data;
    }

    public static function getOrderjoinApi5orders($id)
    {
     $data = DB::table('pivot_site')
                ->join('token', 'pivot_site.id_user', '=', 'token.user_id')
                ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
                ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
                // ->join('financeiro', 'order_site.id', '=', 'financeiro.order_id')
                // ->select('*','financeiro.id as finId')
                ->where('pivot_site.id_user', $id)
                ->orderBy('pivot_site.created_at','desc')
                ->limit(5)->get();
            return $data;
    }

    public static function getOrderjoinComplete($id)
    {
        $data = DB::table('pivot_site')
            ->join('token', 'pivot_site.id_user', '=', 'token.user_id')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
            // ->join('contatos','contatos.id_user','=','users.id')
            ->leftJoin('financeiro', function ($join) { $join->on('order_site.external_reference', '=', 'financeiro.token_transaction')->where('order_site.external_reference', '!=', 'N/D');})
            ->select('*')
            ->where('order_site.id', $id)->get();
        return $data;
    }


    public static function getOrderjoinCompleteByApp($id)
    {
        $data = DB::table('pivot_site')
            ->join('order_site', 'order_site.id', '=', 'pivot_site.order_id')
            ->join('product_site', 'pivot_site.product_id', '=', 'product_site.id')
            ->leftJoin('contatos', 'contatos.integracao_bling_id', '=', 'pivot_site.id_user')
            // ->leftJoin('financeiro', function ($join) { $join->on('order_site.external_reference', '=', 'financeiro.token_transaction')->where('order_site.external_reference', '!=', 'N/D');})
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
