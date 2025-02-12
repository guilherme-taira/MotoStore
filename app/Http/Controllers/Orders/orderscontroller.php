<?php

namespace App\Http\Controllers\Orders;
use App\Events\sendProduct;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\controlerMercadoLivreItems;
use App\Http\Controllers\MercadoLivre\Printer\PrinterController;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Http\Controllers\Yapay\Pagamentos\RenovacaoController;
use App\Models\Contato;
use App\Models\financeiro;
use App\Models\Items;
use App\Models\order_site;
use App\Models\Orders;
use App\Models\Products;
use App\Models\StatusApp;
use App\Models\token;
use DateTime;
use DateTimeZone;
use Exception;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Karriere\PdfMerge\PdfMerge;

class orderscontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $viewData = [];
        $viewData['title'] = "Tela de Vendas";
        $viewData['subtitle'] = "Vendas";
        $viewData['pedidos'] = [];

        $orders = order_site::Ordersjoin(Auth::user()->id,$request);
        $viewData['orders'] = $orders;


        foreach ($orders as $order) {
            array_push($viewData['pedidos'], ['pedido' => $order, 'produtos' => order_site::getOrderjoin($order->id_site)]);
        }

        return view('orders.index', [
            'viewData' => $viewData,
            'orders' => $orders
        ]);
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

    public function brod($id){
         Broadcast(new sendProduct($id));
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
        $order = order_site::getOrderjoinComplete($id);
        // GET TOKEN
        $userML = token::where('user_id_mercadolivre', $order[0]->user_id_mercadolivre)->first();

        $dataAtual = new DateTime();
        // GET NEW TOKEN
        $newToken = new RefreshTokenController($userML->refresh_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $userML->user_id_mercadolivre);
        $newToken->resource();
        $userML = token::where('user_id_mercadolivre', $userML->user_id_mercadolivre)->first();
        $data = (new controlerMercadoLivreItems("orders/".$order[0]->numeropedido,$userML->access_token))->resource();

        $viewData = [];
        $viewData['title'] = "Pedido";
        $viewData['subtitle'] = "$id";
        $viewData['order'] = $order;
        $viewData['pedidos'] = [];
        $viewData['dados'] = $data;
        $viewData['shipping_cost'] = 0;
        $viewData['shipping'] = [];


        $contato = Contato::where('integracao_bling_id','=',$order[0]->id_user)->first();
        if(isset($contato)){
            $viewData['contato'] = $contato;
        }

        if(isset($data->shipping)){
            $shipping = (new controlerMercadoLivreItems("shipments/".$data->shipping->id,$userML->access_token))->resource();
            $viewData['shipping'] = $shipping;
            $viewData['shipping_cost'] = $shipping->shipping_option->list_cost;
        }

        $i = 0;
        foreach ($order as $key => $product) {

            if (isset($product->isKit)) {
                foreach (Products::getProducts($product->id) as $produto) {
                    array_push($viewData['pedidos'], ['produto' => $produto, 'venda' => $order[$i]]);
                }
                $i++;
            } else {
                array_push($viewData['pedidos'], ['produto' => $product, 'venda' => $order[$i]]);
            }
        }


        return view('orders.show')->with('viewData', $viewData);
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

    public function Areceber()
    {
        $viewData = [];
        $viewData['title'] = "Contas a Receber";
        $viewData['subtitle'] = 'Valores a Receber';
        $viewData['orders'] = financeiro::contareceber(Auth::user()->id);
        $viewData['countData'] = financeiro::contareceberCount(Auth::user()->id);

        $viewData['contasDia'] = 0;
        $viewData['contasAtrasada'] = 0;
        $viewData['haPagar'] = 0;

        // DATA ATUAL
        $dataNow = new DateTime();

        // INCREMENTA A QUANTIDADE DE VENDAS A RECEBER
        foreach ($viewData['countData'] as $order) {
            if ($order->status == 4) {
                $viewData['haPagar'] += 1;
            } else if ($order->status == 6) {
                $viewData['contasDia'] += 1;
            } else if ($order->status == 7) {
                $viewData['contasAtrasada'] += 1;
            }
        }
        return view('orders.areceber', [
            'viewData' => $viewData,
        ]);
    }

    public function baixarvenda(Request $request)
    {
        // BAIXA O PEDIDO
        Orders::BaixarVenda($request->id);
        return redirect()->route('orders.areceber')->with('msg', 'Pedido Baixado Com Sucesso!');
    }


    public function ImprimirEtiqueta(Request $request)
    {

        $token = financeiro::join('pivot_site', 'pivot_site.order_id', 'financeiro.order_id')
        ->join('token', 'pivot_site.id_user', 'token.user_id')
        ->where('shipping_id',$request->shipping_id)->first();

        // IMPRIME ETIQUETA
        $data = new PrinterController($request->shipping_id, $token->access_token);
        $dados = $data->resource();
          // Verifica se houve erro no retorno dos dados
        if (isset($dados['error'])) {
            // Retorna com os dados de erro para a view
            return back()->with('error', $dados['error']);
        }

    }

    public function mergeLabels(Request $request)
    {
        $pdfLinks = $request->input('pdf_links');

        Log::alert(json_encode($pdfLinks));
        if (empty($pdfLinks)) {
            return response()->json(['error' => 'Nenhum link foi enviado.'], 400);
        }

        $mergedPdf = new Merger();
        // Caminho base onde os PDFs estão sendo salvos

        try {

            foreach ($pdfLinks as $link) {
                // Obter o token para a etiqueta com base no shipping_id
                $token = financeiro::join('pivot_site', 'pivot_site.order_id', 'financeiro.order_id')
                ->join('token', 'pivot_site.id_user', 'token.user_id')
                ->where('shipping_id', $link)
                ->first();

                if (!$token) {
                    throw new Exception("Token não encontrado para o shipping_id: $link");
                }

                // Gera a etiqueta usando o token
                $data = new PrinterController($link, $token->access_token);
                $dados = $data->resource();
                $pdfContent = file_get_contents($dados);
                // Adiciona apenas a primeira página ao Merger
                $mergedPdf->addRaw($pdfContent, new Pages('1-2')); // Captura apenas a primeira página
                unlink($dados);
            }

           // Mescla todos os PDFs
           $output = $mergedPdf->merge();

            // Retorna o PDF unificado como resposta
            return response($output, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="etiquetas_unificadas.pdf"');
        } catch (Exception $e) {
            Log::error('Erro ao mesclar PDFs: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao mesclar os PDFs.'], 500);
        }
    }

    public function UpdateNewPayment(Request $request)
    {
        $token = token::where('user_id', Auth::user()->id)->first(); // CHAMANDO ANTIGO
        $newPayment = new RenovacaoController;
        $data = $newPayment->UpdatePayment($request->id, $token->access_token);

        $viewData = [];
        $viewData['title'] = "Contas a Receber";
        $viewData['subtitle'] = 'Valores a Receber';
        $viewData['orders'] = financeiro::contareceber(Auth::user()->id);
        $viewData['countData'] = financeiro::contareceberCount(Auth::user()->id);

        $viewData['contasDia'] = 0;
        $viewData['contasAtrasada'] = 0;
        $viewData['haPagar'] = 0;

        // DATA ATUAL
        $dataNow = new DateTime();

        // INCREMENTA A QUANTIDADE DE VENDAS A RECEBER
        foreach ($viewData['countData'] as $order) {
            if ($order->status == 4) {
                $viewData['haPagar'] += 1;
            } else if ($order->status == 6) {
                $viewData['contasDia'] += 1;
            } else if ($order->status == 7) {
                $viewData['contasAtrasada'] += 1;
            }
        }
        return view('orders.areceber', [
            'viewData' => $viewData,
        ]);
    }

    public function feedback(Request $request){
        $data = json_decode(json_encode($request->all()));
        echo "<pre> ";
        print_r($data);
        echo "<hr>merchant_order_id : $data->merchant_order_id , external_reference: $data->external_reference , payment_id : $data->payment_id ";
    }


    public function getVendasApi(Request $request){
        Log::alert($request->all());

        if($request->filterOrder == 1){
            $orders = ['data' => order_site::getOrderjoinApi5orders($request->userId)];
        }else{
            $orders = order_site::getOrderjoinApi($request->userId);
        }
        return response()->json($orders);
    }


    public function getDataEnvio(Request $request){


            // Obter o token para a etiqueta com base no shipping_id
            $token = financeiro::join('pivot_site', 'pivot_site.order_id', 'financeiro.order_id')
            ->join('token', 'pivot_site.id_user', 'token.user_id')
            ->where('shipping_id',$request->shipping_id)
            ->first();

            if (!$token) {
                throw new Exception("Token não encontrado para o shipping_id: {$request->shipping_id}");
            }

         // URL da API com o ID do envio
         $url = "https://api.mercadolibre.com/shipments/{$request->shipment_id}/lead_time";
         // Inicializa o cURL
         $ch = curl_init();

         // Configurações do cURL
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retorna o resultado como string
         curl_setopt($ch, CURLOPT_HTTPHEADER, [
             "Authorization: Bearer {$token->access_token}",
             "x-format-new: true" // Cabeçalho adicional exigido pela API
         ]);

         // Executa a requisição
         $response = curl_exec($ch);

         // Verifica erros na requisição
         if(curl_errno($ch)) {
             echo 'Erro na requisição: ' . curl_error($ch);
             curl_close($ch);
             return false;
         }
         // Fecha a conexão cURL
         curl_close($ch);
         // Converte o JSON para array associativo
         $data = json_decode($response, true);
         // Cria um objeto DateTime a partir da string original
         $data = new DateTime($data['estimated_handling_limit']['date']);

         // Define o timezone para o Brasil, se necessário
         $data->setTimezone(new DateTimeZone('America/Sao_Paulo'));
         // Formata a data no padrão brasileiro (dia/mês/ano horas:minutos:segundos)
         $dataFormatada = $data->format('d/m/Y');

        return response()->json($dataFormatada);
    }

}
