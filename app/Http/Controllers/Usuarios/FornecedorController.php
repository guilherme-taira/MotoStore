<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\categorias_forncedores;
use App\Models\Devolucao;
use App\Models\financeiro;
use App\Models\StatusPedido;
use App\Models\sub_categoria_fornecedor;
use App\Models\token as ModelsToken;
use App\Models\User;
use Aws\Token\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Lista de Fornecedores";
        $viewData['categorias'] = User::getAllUsers();

        return view("fornecedor.index",[
            'viewData' => $viewData
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
        $viewData['title'] = "Lista de Fornecedores";
        $viewData['categorias_fornecedor'] = categorias_forncedores::all();

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
        $validator = Validator::make($request->all(), [
            'nome' => 'required|min:5',
            'password' => 'required',
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // $newCategoria = new categorias_forncedores();
        // $newCategoria->name = $request->categoria;
        // $newCategoria->slug = $request->slug;
        // $newCategoria->descricao = $request->regiao;
        // $newCategoria->save();

        $user = new User();
        $user->name = $request->nome;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->user_id = $request->categoria;
        $user->save();


        return redirect()->route('fornecedores.index')->with('msg',"Fornecedor / Usuário Cadastrado com sucesso!");
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
        $viewData = [];
        $viewData['title'] = "Lista de Fornecedores";
        $viewData['fornecedor'] = User::getUserById($id);

        $subcategorias = [];

        foreach (categorias_forncedores::all() as $value) {

            $subcategorias[$value->id] = [
                "nome" => $value->name,
                "subcategory" => sub_categoria_fornecedor::getAllCategory($value->id),
            ];
        }

        $viewData['subcategorias'] = $subcategorias;
        $viewData['id'] = $id;

        return view("fornecedor.edit",[
            'viewData' => $viewData,
        ]);
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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'nome' => 'required',
            'categoria' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::where('id',$id)->update([
            "name" => $request->nome,
            "user_subcategory" => $request->categoria,
            "password" => bcrypt($request->password),
        ]);

        return redirect()->route('fornecedores.index')->with('msg',"Fornecedor Atualizado com sucesso!");
    }

    public function filtrarPorNome(Request $request) {
        $fornecedores = User::where('name', 'like', '%' . $request->query('name') . '%')->get();
        return response()->json($fornecedores);
    }


    public function getDataCentralFornecedor(Request $request) {
        Log::alert($request->id);
        $dados = financeiro::contareceber($request->id);
        return response()->json($dados);
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

    public function getWords($word){
        // Divide o texto em palavras
            $palavras = explode(' ', $word);
            // Pega as 3 primeiras palavras
            $primeiras_palavras = array_slice($palavras, 0, 3);
            // Junta as palavras de volta em uma string
            $resultado = implode(' ', $primeiras_palavras);
            return  $resultado;  // Saída: Lanterna Pro Titanium
    }

    public function updateStatusEnvio(Request $request){
        Log::alert($request->all());
        $product = financeiro::findOrFail($request->id);
        $product->status_envio = $request->status_envio;
        $product->save();
        $msg = "";
        $dadosVenda = financeiro::GetDataByUserApp($product->order_id);
        Log::alert(json_encode($dadosVenda));
        if($dadosVenda->status_envio == 1){
            $msg = "O produto {$this->getWords($dadosVenda->product_name)}.. esta em preparação!";
        }elseif($dadosVenda->status_envio == 2){
            $msg = "O produto {$this->getWords($dadosVenda->product_name)}.. Foi Despachado!";

            $data = new StatusPedido(
                ['status_app_id' => $request->status_envio, 'order_site_id' => $product->order_id, 'etiqueta' => $request->rastreio]
            );
            $data->save();
        }

       try {
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
        Log::alert(json_encode($messaging));
       } catch (\Exception $th) {
        Log::alert($th->getMessage());
       }

        return response()->json(['success' => true, 'message' => 'Status de envio atualizado com sucesso!']);
    }


    public function getDevolucoesByFornecedor(Request $request){

        $data = Devolucao::getData($request->user_id);
        return response()->json($data);
    }

    public function getMessageMediation(Request $request){

            $token = ModelsToken::where('user_id',$request->user_id)->first();

            try {
                $url = "https://api.mercadolibre.com/post-purchase/v1/claims/{$request->mediation}/messages";

                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [
                        "Authorization: Bearer {$token->access_token}",
                        "x-format-new: true"
                    ]
                ]);

                $response = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);

        Log::alert($response);
                if ($httpCode >= 200 && $httpCode < 300) {
                    $data = json_decode($response, true); // Decodifica como array associativo
                    return response()->json($data);
                }
            } catch (\Exception $e) {
                // Tratamento de erro
                Log::alert($e->getMessage());
            }


    }

    public function setShippingMediation(Request $request) {
        Log::alert($request->all());

        $updated = DB::table('devolucoes')
            ->where('rastreio', $request->id)
            ->update([
                // 'dados' => json_encode($request->input('dados')), // Atualiza os dados como JSON
                'bipado_por' => $request->input('user_id'), // Atualiza normalmente
                'data_recebimento' => DB::raw("CASE WHEN '{$request->input('user_id')}' != '' THEN NOW() ELSE data_recebimento END")
            ]);

        if($updated){
            return true;
        }

        return false;
    }


}
