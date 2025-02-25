<?php

namespace App\Http\Controllers\Fornecedor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Yapay\Pagamentos\RenovacaoController;
use App\Models\categorias_forncedores;
use App\Models\financeiro;
use App\Models\StatusApp;
use App\Models\StatusPedido;
use App\Models\sub_categoria_fornecedor;
use App\Models\token;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
class fornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Contas a Receber";
        $viewData['subtitle'] = 'Valores a Receber';
        $viewData['orders'] = financeiro::contareceber(Auth::user()->id);
        $viewData['countData'] = financeiro::contareceberCount(Auth::user()->id);
        $viewData['totalAguardando'] = financeiro::aguardandopagamento(Auth::user()->id);
        $viewData['pago'] = financeiro::pago(Auth::user()->id);

        try {
            foreach ($viewData['orders'] as $key => $value) {
                // Obter o token para a etiqueta com base no shipping_id
                $token = financeiro::join('pivot_site', 'pivot_site.order_id', 'financeiro.order_id')
                ->join('token', 'pivot_site.id_user', 'token.user_id')
                ->where('shipping_id',$value->shipping_id)
                ->first();

            if (!$token) {
                throw new Exception("Token não encontrado para o shipping_id: {$value->shipping_id}");
            }

            $viewData['orders'][$key]['estimated_handling_limit'] = $this->getShipmentDelays($value->shipping_id,$token->access_token);
        }
        }catch(\Exception $th){

        }

        $statusApp = StatusApp::all();
        $viewData['statusApp'] = $statusApp;

        $viewData['contasDia'] = 0;
        $viewData['contasAtrasada'] = 0;
        $viewData['haPagar'] = 0;

        // INCREMENTA A QUANTIDADE DE VENDAS A RECEBER
        foreach ($viewData['countData'] as $order) {

            if($order->status == 3){
                $viewData['haPagar'] += 1;
            }else if($order->isPrinted == 0){
                $viewData['contasDia'] += 1;
            }else if($order->isPrinted == 1){
                $viewData['contasAtrasada'] += 1;
            }
        }

        return view('orders.fornecedor.index',[
            'viewData' => $viewData,
        ]);
    }



    public function haImprimir(Request $request){

        $orders = financeiro::join('order_site', "order_site.id", '=', 'financeiro.order_id')
        ->join('pivot_site','order_site.id', '=', 'pivot_site.order_id')
        ->join('users','pivot_site.id_user','=','users.id')
        ->join('product_site','pivot_site.product_id','=','product_site.id')
        ->join('products','product_site.codigo','=','products.id')
        ->select('financeiro.status as statusf','order_site.*','financeiro.*','order_site.id as id_venda','pivot_site.*','users.*','product_site.*','products.informacaoadicional','financeiro.id as financeiroId')
        ->where('financeiro.user_id', $request->user)
        ->where('status_envio',1)
        ->orderBy('financeiro.id','desc')->paginate(10);

        try {
            foreach ($orders as $key => $value) {

                // Obter o token para a etiqueta com base no shipping_id
                $token = financeiro::join('pivot_site', 'pivot_site.order_id', 'financeiro.order_id')
                ->join('token', 'pivot_site.id_user', 'token.user_id')
                ->where('shipping_id',$value->shipping_id)
                ->first();

            if (!$token) {
                throw new Exception("Token não encontrado para o shipping_id: {$value->shipping_id}");
            }

            $orders['orders'][$key]['estimated_handling_limit'] = $this->getShipmentDelays($value->shipping_id,$token->access_token);
        }
        }catch(\Exception $th){

        }

        Log::alert(json_encode($orders));
        return response()->json($orders);
    }

    function getShipmentDelays($shipment_id, $access_token) {
        // URL da API com o ID do envio
        $url = "https://api.mercadolibre.com/shipments/{$shipment_id}/lead_time";
        // Inicializa o cURL
        $ch = curl_init();

        // Configurações do cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retorna o resultado como string
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$access_token}",
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
        return $dataFormatada;
    }


    public function updateStatusEnvio(Request $request, $id)
    {
        Log::alert($request->all());
        $product = financeiro::findOrFail($id);
        $product->status_envio = $request->status_envio;
        $product->save();

        $msg = "";
        $dadosVenda = financeiro::GetDataByUserApp($product->order_id);

        if($dadosVenda->status_envio == 1){
            $msg = "O produto {$this->getWords($dadosVenda->product_name)}.. esta em preparação!";
        }elseif($dadosVenda->status_envio == 2){
            $msg = "O produto {$this->getWords($dadosVenda->product_name)}.. Foi Despachado!";

            new StatusPedido(
                ['status_app_id' => $request->status_envio, 'order_site_id' => $product->order_id, 'etiqueta' => ""]
            );
        }
        $token = "epf7FGyeQBiX8cpZO3TuQU:APA91bG8CzIPLNvd27JwpKxAtB7eSSDSmx6V57t_GUPeUW5qdFLjr6bcWsxz_iEfMGfjX0hART_BKp_lfkI-k-XzMA-9NYByF8chOyy6bM23vaE2muhGJOQ"; // Pegue do banco de dados ou passe no request

        $factory = (new Factory)
        ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
        $messaging = $factory->createMessaging();

        $message = CloudMessage::withTarget('token', $token)
        ->withNotification(Notification::create("Olá {$dadosVenda->name}", $msg))
        ->withAndroidConfig(AndroidConfig::fromArray([
            'priority' => 'high',
            'notification' => [
                'sound' => 'default',  // Garante que o som será reproduzido
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'  // Caso use Flutter, ou configure para o app específico
            ]
        ]));// Prioridade alta para exibir no modo standby;
        $messaging->send($message);

        return response()->json(['success' => true, 'message' => 'Status de envio atualizado com sucesso!']);
    }


    public function getWords($word){
        // Divide o texto em palavras
            $palavras = explode(' ', $word);
            // Pega as 3 primeiras palavras
            $primeiras_palavras = array_slice($palavras, 0, 3);
            // Junta as palavras de volta em uma string
            $resultado = implode(' ', $primeiras_palavras);
            return  $resultado;  // Saída: Lanterna Pro Titanium
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $viewData = [];
        $viewData['title'] = "Lista de Fornecedores";
        $viewData['categorias_fornecedor'] = categorias_forncedores::all();

        $subcategorias = [];

        foreach (categorias_forncedores::all() as $value) {

            $subcategorias[$value->id] = [
                "id" => $value->id,
                "nome" => $value->name,
                "subcategory" => sub_categoria_fornecedor::getAllCategory($value->id),
            ];
        }

        $viewData['subcategorias'] = $subcategorias;


        return view("fornecedor.create",[
            'viewData' => $viewData
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
