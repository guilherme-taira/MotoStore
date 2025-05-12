<?php

namespace App\Http\Controllers\Products;

use App\Events\EventoAfiliado;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Job\getProductDataController;
use App\Http\Controllers\MercadoLivre\ProdutoImplementacao;
use App\Http\Controllers\MercadoLivreHandler\ConcretoDomainController;
use App\Http\Controllers\MercadoLivreHandler\getDomainController;
use App\Http\Controllers\Services\ServicesController;
use App\Models\banner_autokm;
use App\Models\banner_premium;
use App\Models\TokenUpMineracao;
use App\Models\financeiro;
use App\Models\categorias;
use App\Models\categorias_forncedores;
use App\Models\images;
use App\Models\logo;
use App\Models\mercado_livre_history;
use App\Models\Products;
use App\Models\sub_categoria_fornecedor;
use App\Models\sub_category;
use App\Models\token;
use App\Models\User;
use App\Models\log as historico;
use App\Events\EventoNavegacao;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpParser\Parser\Tokens;
use Throwable;
use App\Events\logAlteracao;
use App\Http\Controllers\image\image;
use App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo\handlerBooties;
use App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo\handlerBras;
use App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo\handlerDresses;
use App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo\handlerPants;
use App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo\handlerShoes;
use App\Http\Controllers\MercadoLivre\Generatecharts;
use App\Http\Controllers\MercadoLivre\GeneratechartsSneakers;
use App\Http\Controllers\MercadoLivre\MlbCallAttributes;
use App\Http\Controllers\MercadoLivre\MlbTipos;
use App\Http\Controllers\MercadoLivre\ProdutoConcreto;
use App\Http\Controllers\MercadoLivre\RefreshTokenController;
use App\Http\Controllers\MercadoLivre\updatePriceSiteController;
use App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo\handlerSkirts;
use App\Http\Controllers\MercadoLivre\alteradorCategoriaNovo\handlerSwimwearCreator;
use App\Http\Controllers\MercadoLivre\ManipuladorProdutosIntegrados;
use App\Http\Controllers\MercadoLivreStockController;
use App\Jobs\UpdateStockJob;
use App\Models\kit;
use App\Models\order_site;
use App\Models\produtos_integrados;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use stdClass;
use Illuminate\Support\Facades\Http;

set_time_limit(0);

class productsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $viewData = [];
        $viewData['title'] = "MotoStore Produtos";
        $categoriaKeyCache = 'categoriasProdutos';

        // Tempo em minutos que o cache será mantido
        $cacheTime = 5;

        $viewData['products'] = Products::getResults($request);
        $viewData['token'] = token::where('user_id',Auth::user()->id)->first();

        $images = [];
        foreach ($viewData['products'] as $produto) {
            // Busca todas as imagens do produto
            $fotos = Images::where('product_id', $produto->id)->OrderBy('position','asc')->get();

            // Inicializa um array para armazenar as fotos do produto
            $images[$produto->id] = [
                'fotos' => [] // Definimos um array interno para as fotos
            ];

            foreach ($fotos as $foto) {
                $images[$produto->id]['fotos'][] = [
                    'id' => $foto->id,
                    'foto' => $produto->id . "/" . $foto->url,
                ];
            }
        }

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->name,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        // Verifique se já existe um cache
        if (Cache::has($categoriaKeyCache)) {
            $viewData['categorias'] = Cache::get($categoriaKeyCache);
        } else {
            $viewData['categorias'] = $categorias;
            Cache::put($categoriaKeyCache, $viewData['categorias'], now()->addMinutes($cacheTime));
        }

        $viewData['images'] = $images;
        $viewData['filtro'] = $request->all();

        return view('admin.products', compact('viewData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $viewData = [];
        $categorias = [];
         $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->name,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $token = token::where('user_id',34)->first();
        $subCategoria = [];
        $viewData['fornecedor'] = User::where('forncecedor', 1)->get();
        $viewData['categorias'] = $categorias;
        $viewData['access_token'] = $token->access_token;

        return view('products.add')->with('viewData', $viewData);
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
            'name' => 'required|min:5',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|numeric',
            'description' => 'required',
            'brand' => 'required|min:1',
            'ean' => 'required',
            'termometro' => 'numeric',
            'fee' => 'required|numeric|gt:0',
            'taxaFee' => 'required|numeric|gt:0',
            'PriceWithFee' => 'required|numeric|gt:0',
            'height' => 'required|numeric|gt:0',
            'width' => 'required|numeric|gt:0',
            'length' => 'required|numeric|gt:0',
            'photos' => 'required',
            'id_categoria' => 'required',
            'priceKit' => 'required|numeric|gt:0',
        ], [
            // Mensagens personalizadas
            'name.required' => 'O campo Nome é obrigatório.',
            'name.min' => 'O campo Nome deve ter no mínimo 5 caracteres.',
            'price.required' => 'O campo Preço é obrigatório.',
            'price.numeric' => 'O campo Preço deve ser numérico.',
            'price.min' => 'O campo Preço deve ser maior que zero.',
            'stock.required' => 'O campo Estoque é obrigatório.',
            'stock.numeric' => 'O campo Estoque deve ser numérico.',
            'description.required' => 'O campo Descrição é obrigatório.',
            'brand.required' => 'O campo Marca é obrigatório.',
            'brand.min' => 'O campo Marca deve ter no mínimo 1 caractere.',
            'ean.required' => 'O campo EAN é obrigatório.',
            'termometro.numeric' => 'O campo Termômetro deve ser numérico.',
            'fee.required' => 'O campo Taxa é obrigatório.',
            'fee.numeric' => 'O campo Taxa deve ser numérica.',
            'fee.gt' => 'O campo Taxa deve ser maior que zero.',
            'taxaFee.required' => 'O campo Taxa de Comissão é obrigatório.',
            'taxaFee.numeric' => 'O campo Taxa de Comissão deve ser numérica.',
            'taxaFee.gt' => 'O campo Taxa de Comissão deve ser maior que zero.',
            'PriceWithFee.required' => 'O campo Preço com Taxa é obrigatório.',
            'PriceWithFee.numeric' => 'O campo Preço com Taxa deve ser numérico.',
            'PriceWithFee.gt' => 'O campo Preço com Taxa deve ser maior que zero.',
            'height.required' => 'O campo Altura é obrigatório.',
            'height.numeric' => 'O campo Altura deve ser numérico.',
            'height.gt' => 'O campo Altura deve ser maior que zero.',
            'width.required' => 'O campo Largura é obrigatório.',
            'width.numeric' => 'O campo Largura deve ser numérica.',
            'width.gt' => 'O campo Largura deve ser maior que zero.',
            'length.required' => 'O campo Comprimento é obrigatório.',
            'length.numeric' => 'O campo Comprimento deve ser numérico.',
            'length.gt' => 'O campo Comprimento deve ser maior que zero.',
            'photos.required' => 'É necessário adicionar pelo menos uma foto.',
            'id_categoria.required' => 'O campo Categoria é obrigatório.',
            'priceKit.required' => 'O campo Preço do Kit é obrigatório.',
            'priceKit.numeric' => 'O campo Preço do Kit deve ser numérico.',
            'priceKit.gt' => 'O campo Preço do Kit deve ser maior que zero.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $produto = new Products();
        $produto->price = $request->price;
        $produto->title = $request->name;
        $produto->description = $request->description;
        $produto->available_quantity = $request->stock;
        $produto->priceWithFee = $request->PriceWithFee;
        // Categoria Principal Removido da inserção
        //$produto->categoria = $produto::getIdPrincipal($request->categoria);
        $produto->category_id = $request->id_categoria;
        $produto->subcategoria = $request->categoria;
        $produto->brand = $request->brand;
        $produto->gtin = $request->ean;
        $produto->image = 'image.png';
        $produto->SetBrand($request->input('brand'));
        $produto->SetIsNft($request->input('isNft'));
        //$produto->setCategoria(Products::getIdPrincipal($request->input('categoria')));
        $produto->SetSubCategory_id($request->input('categoria'));
        $produto->SetGtin($request->input('ean'));
        // $produto->setPricePromotion($request->input('pricePromotion'));
        $produto->setDescription($request->input('description'));
        $produto->SetLugarAnuncio($request->input('radio'));
        $produto->setIsPublic($request->input('isPublic'));
        $produto->SetFornecedor($request->input('fornecedor'));
        $produto->SetTermometro($request->input('termometro'));
        $produto->setPriceWithFee($request->input('price'));
        $produto->setFee($request->input('fee'));
        $produto->setHeight($request->input('height'));
        $produto->setWidth($request->input('width'));
        $produto->setLength($request->input('length'));
        $produto->setPriceKit($request->input('priceKit'));

        $produto->save();

        $files = $request->file('photos');


        $i = 0;
        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            $file->storeAs('produtos/' . $produto->getId(), $filename, 's3');
            if ($i == 0) {
                $produto->setImage($filename);
            }
            $image = new images();
            $image->url = $filename;
            $image->product_id = $produto->getId();
            $image->save();
            $i++;
        }

        $produto->save();
        return redirect()->route('allProductsByFornecedor');
    }

    public function getProdutosPaginados(Request $request){
        Log::alert($request->all());
        if($request->fornecedor_id != 'null'){
            Log::alert("1");
            $produtos = Products::where('isKit','=','0')
            ->orderBy('created_at','desc')
            ->where('isPublic','=',1)->where('fornecedor_id','=',$request->fornecedor_id)
            ->paginate(20);
        }else{
            Log::alert("2");
            $produtos = Products::where('isKit','=','0')
            ->orderBy('created_at','desc')
            ->where('isPublic','=',1)
            ->paginate(20);
        }

        // Adiciona o URL completo da imagem para cada produto
        foreach ($produtos as $produto) {
            $produto->imagem_url = Storage::disk('s3')->url('produtos/' . $produto->id . '/' . $produto->image);
        }
        return response()->json($produtos);
    }

    public function getProdutosPaginadosImages(Request $request) {
        Log::alert($request->all());
        if ($request->has('fornecedor')) {
            $produtos = Products::where('isKit', '=', '0')
                ->where('isPublic', '=', 1)
                ->where('fornecedor_id','=', $request->fornecedor)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            // Caso não tenha o parâmetro, você pode definir outra query ou retornar um erro
            $produtos = Products::where('isKit', '=', '0')
            ->where('isPublic', '=', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        }

        foreach ($produtos as $produto) {
            // Obtém todas as imagens relacionadas ao produto
            $imagens = images::where('product_id', $produto->id)->orderBy('position','asc')->get();
            $produto->imagem_url = Storage::disk('s3')->url('produtos/' . $produto->id . '/' . $produto->image);
            // Cria um array para armazenar os URLs das imagens
            $imagem_urls = [];
            foreach ($imagens as $imagem) {
                $imagem_urls[] = Storage::disk('s3')->url('produtos/' . $produto->id . '/' . $imagem->url);
            }
            // Adiciona os URLs das imagens dentro do campo 'imagens' no produto
            $produto->imagens = $imagem_urls;
        }
        return response()->json($produtos);
    }



    public function tradeCategoria(Request $request){
        return $this->getAttributesTrade($request);
    }

    public function tradeCategoriaApiNew(Request $request){

        if($request->via = "alterador"){

             // ENDPOINT PARA REQUISICAO
             $endpoint = 'https://api.mercadolibre.com/items/'.$request->id;

             $ch = curl_init();
             curl_setopt($ch, CURLOPT_URL, $endpoint);
             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             $response = curl_exec($ch);
             $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
             curl_close($ch);
             $data = json_decode($response,true);

             $try = FALSE;

             if($request->categoria){
             // TROCAR A CATEGORIA

             $obj = new stdClass();
             $obj->domain = $request->domain;
             $obj->token = $request->token;
             $obj->data = $request->required;

             $handler = new handlerDresses();
             $handler->setNext(new handlerDresses())
             ->setNext(new handlerShoes())
             ->setNext(new handlerBooties())
             ->setNext(new handlerPants())
             ->setNext(new handlerSkirts())
             ->setNext(new handlerBras())
             ->setNext(new handlerSwimwearCreator());

             $grid = $handler->Manipular($obj);

            if($request->moda){
                    $data_json = json_encode(['category_id' => $request->categoria,'attributes' => $grid]);
            }else{
                if($try){
                    $data_json = json_encode($data);
                }else{
                    $data_json = json_encode(['category_id' => $request->categoria,'attributes' => $request->required]);
                    $try = TRUE;
                }
            }

            $token = token::where('user_id_mercadolivre', $request->user)->first(); // CHAMANDO ANTIGO

            $dataAtual = new DateTime();
            $newToken = new RefreshTokenController($token->refresh_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", $token->user_id_mercadolivre);
            $newToken->resource();
            $token = token::where('user_id_mercadolivre',$request->user)->first(); // CHAMANDO ANTIGO

            Log::alert($data_json);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $json = json_decode($reponse);
            Log::alert($reponse);
            if ($httpCode == '200') {
                logAlteracao::dispatch('TROCA DE CATEGORIA',$request->user,$reponse,true);
                echo "<li class='list-group-item bg-success text-white'><i class='bi bi-check-circle-fill'></i> Alterado com Sucesso</li>";
            }else{

                if(isset($json->cause[0]->message)){
                    echo "<li class='list-group-item bg-danger text-white'>{$json->cause[0]->message}</li>";
                }else{
                    echo "<li class='list-group-item bg-danger text-white'>{$json->message}</li>";
                }

            }
           }
    }
 }

    public function getHistory(Request $request){
        $logs = historico::where('user_id',$request->user)->limit(10)->orderBy('created_at', 'desc')->get();
        $history = [];
        foreach ($logs as $log) {
            array_push($history,['ACAO' => $log->acao, 'PRODUTO' =>json_decode($log->message)->thumbnail, 'TEMPO' => $log->created_at,'SUCESSO'=> $log->sucesso == true ? 'success' : 'danger']);
        }
       return response()->json($history);
    }


    public function teste(Request $request){
        $data = order_site::getOrderByDashboard($request);
        return response()->json($data);
    }

    public function getAttributesTrade(Request $request)
    {
        $token = token::where('user_id_mercadolivre',$request->user)->first();

        if($request->base){
             // ENDPOINT PARA REQUISICAO
        $endpoint = 'https://api.mercadolibre.com/items/'.$request->base;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $data = json_decode($response,true);

        // CADASTRA UM NOVO ANUNCIO
        foreach ($request->id as $id) {
           return $this->refazerRequest($id,$request->user,$data,$data['domain_id'],$request->newtitle);
        }

        }else{
            // ENDPOINT PARA REQUISICAO
            $endpoint = 'https://api.mercadolibre.com/items/'.$request->id;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $data = json_decode($response,true);

            if($request->categoria){
                // TROCAR A CATEGORIA
                $this->TrocarCategoriaRequest($data,FALSE,$data['id'],$request->categoria,$request->user,$request->newtitle,$request->required);
            }
        }

    }

    public function isClother($attr, $auth): bool {
        $context = stream_context_create([
            "http" => [
                "header" => "Authorization: Bearer $auth->access_token"
            ]
        ]);

        $response = file_get_contents(
            'https://api.mercadolibre.com/catalog/charts/MLB/configurations/active_domains',
            false,
            $context
        );

        $decoded = json_decode($response, true);

        if (!isset($decoded['domains']) || !is_array($decoded['domains'])) {
            return false; // resposta inesperada
        }

        foreach ($decoded['domains'] as $value) {
            if (isset($value['domain_id']) && $value['domain_id'] == $attr) {
                return true;
            }
        }

        return false;
    }



    public function refazerRequest($id,$user,$data,$domain,$newtitle) {

        $endpoint = 'https://api.mercadolibre.com/items/'.$id;

        $token = token::where('user_id_mercadolivre',$user)->first();

            unset($data['location'],$data['family_name'],$data['user_product_id'],$data['official_store_id'],$data['original_price']);

            $data_json = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $json = json_decode($reponse);

            Log::emergency($reponse);
            if ($httpCode == '200') {
                logAlteracao::dispatch('TROCA COM BASE',$user,$reponse,true);
                echo 200;
            } else {
                    Log::notice(json_encode($json));
                    Log::notice($data_json);
                try {
                    $domain = new getDomainController('12',$data['attributes']);
                    $concreto = new ConcretoDomainController($domain);
                    $concreto->CallAttributes($data);
                    $data_json = $concreto->CallErrorAttributes($json,$data,false,null,$newtitle);

                    $this->refazerRequest($id,$user,$data_json,$domain,$newtitle);

                } catch (\Throwable $th) {

                    echo "<li class='list-group-item bg-danger text-white'><i class='bi bi-exclamation-circle-fill'></i> Error no Produto..</li>";
                    Log::error($th->getMessage());
                }

            }
    }

    public function TrocarCategoriaRequest($data, $try = FALSE, $id,$categoria,$user,$newtitle,$required) {
        $ids = $id;
        $category = $categoria;
        // NUMERO DE TENTATIVAS
        $endpoint = 'https://api.mercadolibre.com/items/'. $ids;

        $token = token::where('user_id_mercadolivre',$user)->first();

            if($try){
                $data_json = json_encode($data);
            }else{
                $data_json = json_encode(['category_id' => $categoria,'attributes' => $required]);
                $try = TRUE;
            }

            Log::alert($data_json);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $json = json_decode($reponse);
            Log::critical($reponse);
            if ($httpCode == '200') {
                logAlteracao::dispatch('TROCA DE CATEGORIA',$user,$reponse,true);
                echo "<li class='list-group-item bg-success text-white'><i class='bi bi-check-circle-fill'></i> Alterado com Sucesso</li>";
            } else {
                echo "<li class='list-group-item bg-danger text-white'><i class='bi bi-exclamation-circle-fill'></i> Arrumando Pendências..</li>";
                // Log::notice($data_json);
                try {
                    $domain = new getDomainController('12',$data['attributes']);
                    $concreto = new ConcretoDomainController($domain);
                    $concreto->CallAttributes($data);
                    $data_json = $concreto->CallErrorAttributes($json,$data,true,$category,$newtitle,$required,$token);

                    if($json->status == 400){
                        foreach ($json->cause as $value) {
                            if($value->department != "shipping"){
                                echo "<li class='list-group-item bg-danger text-white'><i class='bi bi-exclamation-circle-fill'></i> " . $value->message ."</li>";
                            }
                        }
                   }

                    $this->TrocarCategoriaRequest($data_json,TRUE,$ids,$category,$user,$newtitle,$required);
                } catch (\Throwable $th) {
                    // Log::error($th->getMessage());
                }



            }

    }

    function primeiraMaiuscula($input) {
        if (preg_match('/[A-Z]/', $input, $matches)) {
            return $matches[0];
        }
        return '';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produto = Products::findOrFail($id);
        $fotos = images::where('product_id', $id)->get();
        $token = token::where('user_id', Auth::user()->id)->first();

        $photos = [];
        foreach ($fotos as $foto) {
            array_push($photos, $foto->url);
        }

        if ($produto) {
            $viewData = [];
            $viewData['title'] = "AfiliDrop : " . $produto->name;
            $viewData['categoria_num'] = $produto->categoria;
            $viewData['subtitle'] = $produto->title;
            $viewData['product'] = $produto;
            $viewData['stock'] = $produto->stock;
            $viewData['image'] = $produto->image;
            $viewData['images'] = $photos;
            $viewData['imageJson'] = $produto->imageJson;
            $viewData['kitProducts'] = kit::getProductsByKit($produto->id);

            $categorias = [];
            foreach (categorias::all() as $value) {
                $categorias[$value->id] = [
                    "nome" => $value->nome,
                    "subcategory" => sub_category::getAllCategory($value->id),
                ];
            }

            $subcategorias = [];

            foreach (categorias_forncedores::all() as $value) {

                $subcategorias[$value->id] = [
                    "nome" => $value->name,
                    "subcategory" => sub_categoria_fornecedor::getAllCategory($value->id),
                ];
            }

            $viewData['subcategorias'] = $subcategorias;
            $viewData['categorias'] = $categorias;

            $viewData['fornecedor'] = User::where('forncecedor', 1)->get();
            $viewData['token'] = $token;
            $viewData['categorias'] = $categorias;

            return view('store.show')->with('viewData', $viewData);
        }

        return redirect()->route('store.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $produto = Products::where('id', $id)->first();
        EventoNavegacao::dispatch($produto);
        $fotos = Images::where('product_id', $id)
        ->orderBy('position', 'asc') // Ordena pela posição em ordem crescente
        ->get();

        $photos = [];
        $viewData = [];

        foreach ($fotos as $foto) {

            $photoUrl = "https://afilidrop2.s3.us-east-1.amazonaws.com/produtos/" . $foto->product_id . '/' . $foto->url;
            // Verifica se é a imagem principal
            $isMain = $foto->url === $produto->image;

            array_push($photos, [
                'id' => $foto->id,
                'url' => $photoUrl,
                'isMain' => $isMain // Adiciona flag para identificar a imagem principal
            ]);
        }

        $viewData['photos'] = $photos;
        $viewData['title'] = "Afilidrop" . $produto->getName();
        $viewData['product'] = $produto;
        $viewData['categoriaSelected'] = sub_category::getNameCategory($produto->subcategoria);
        $viewData['kitProducts'] = kit::getProductsByKit($produto->id);

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->name,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $subCategoria = [];

        sub_category::getAllCategory($produto->subcategoria);

        $viewData['fornecedor'] = User::where('forncecedor', 1)->get();
        $viewData['categorias'] = $categorias;
        return view('products.edit')->with('viewData', $viewData);
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


        $request->validate([
            'isPublic' => "required",
            "title" => "required|max:255",
            "description" => "required",
            "available_quantity" => "required|numeric|gt:0",
            "category_id" => "required|max:20",
            'subcategoria' => "required",
            "brand" => "max:100",
            'image' => 'required',
            "gtin" => "required|numeric",
            "tipo_anuncio" => "required|max:50",
            'pricePromotion' => 'numeric',
            'termometro' => 'numeric',
            'taxaFee' => "required|numeric|gt:0",
            'priceWithFee' =>  "required|numeric|gt:0",
            'height' =>  "required|numeric|gt:0",
            'width' =>  "required|numeric|gt:0",
            'length' =>  "required|numeric|gt:0",
            'price' => "required|numeric|gt:0",
            'priceKit' => "required|numeric|gt:0",
            'valorProdFornecedor' => "required|numeric",
            'owner' => 'required',

            // Novos Campos
            'estoque_minimo_afiliado' => "required|numeric|gte:0",
            'percentual_estoque' => "required|numeric|between:0,100",
            'estoque_afiliado' => "required|numeric|gte:0",
            'min_unidades_kit' => "required|numeric|gte:0",
            'acao' => "nullable|string|max:50"
        ], [
            'owner.required' => "O campo owner é obrigatório",
            'subcategoria.required' => "O campo Categoria é obrigatório",
            'isPublic.required' => "O campo ativo é obrigatório.",
            'title.required' => 'O campo Nome é obrigatório.',
            'title.max' => 'O Nome não pode ter mais de 255 caracteres.',
            'description.required' => 'A descrição é obrigatória.',
            'available_quantity.required' => 'O campo Estoque é obrigatório.',
            'available_quantity.numeric' => 'O campo Estoque deve ser um número.',
            'available_quantity.gt' => 'O campo Estoque deve ser maior que 0.',
            'category_id.required' => 'A Categoria Mercado Livre é obrigatória.',
            'brand.max' => 'A Marca não pode ter mais de 100 caracteres.',
            'image.required' => 'O campo Imagem deve ser uma imagem válida.',
            'gtin.required' => 'O código EAN é obrigatório.',
            'gtin.numeric' => 'O campo EAN deve ser numérico.',
            'tipo_anuncio.required' => 'O Tipo de Anúncio é obrigatório.',
            'pricePromotion.numeric' => 'O campo Preço Promocional deve ser numérico.',
            'termometro.numeric' => 'O campo Termômetro deve ser numérico.',
            'taxaFee.required' => 'O campo Taxa Fee é obrigatório.',
            'priceWithFee.required' => 'O campo Preço com Taxa é obrigatório.',
            'height.required' => 'O campo Altura é obrigatório.',
            'width.required' => 'O campo Largura é obrigatório.',
            'length.required' => 'O campo Comprimento é obrigatório.',
            'price.required' => 'O campo Preço é obrigatório.',
            'priceKit.required' => 'O campo Preço Kit é obrigatório.',
            'priceKit.numeric' => 'O campo Preço Kit deve ser numérico.',
            'priceKit.gt' => 'O campo Preço Kit deve ser maior que 0.',
            'priceKit.required' => 'O campo Preço do Kit é obrigatório.',
            'valorProdFornecedor.required' => "O campo acressímo é obrigatório",
            'valorProdFornecedor.numeric' => "O campo acressímo deve ser numerico",
             // Mensagens dos Novos Campos
            'estoque_minimo_afiliado.required' => 'O campo Estoque Mínimo Afiliado é obrigatório.',
            'estoque_minimo_afiliado.numeric' => 'O campo Estoque Mínimo Afiliado deve ser um número.',
            'estoque_minimo_afiliado.gte' => 'O campo Estoque Mínimo Afiliado deve ser maior ou igual a 0.',
            'percentual_estoque.required' => 'O campo Percentual de Estoque é obrigatório.',
            'percentual_estoque.numeric' => 'O campo Percentual de Estoque deve ser numérico.',
            'percentual_estoque.between' => 'O campo Percentual de Estoque deve estar entre 0 e 100.',
            'estoque_afiliado.required' => 'O campo Estoque do Afiliado é obrigatório.',
            'estoque_afiliado.numeric' => 'O campo Estoque do Afiliado deve ser numérico.',
            'estoque_afiliado.gte' => 'O campo Estoque do Afiliado deve ser maior ou igual a 0.',
            'min_unidades_kit.required' => 'O campo Mínimo de Unidades no Kit é obrigatório.',
            'min_unidades_kit.numeric' => 'O campo Mínimo de Unidades no Kit deve ser numérico.',
            'min_unidades_kit.gte' => 'O campo Mínimo de Unidades no Kit deve ser maior ou igual a 0.',
            'acao.string' => 'O campo Ação deve ser um texto.',
            'acao.max' => 'O campo Ação não pode ter mais de 50 caracteres.',
        ]);

        $produto = Products::findOrFail($id);
        $produto->fill($request->except('products')); // Preenche os dados do produto


        // MANIPULA O PREÇO DAS INTEGRAÇÔES
        $precoNew = new ManipuladorProdutosIntegrados($id,number_format($request->priceWithFee,2));
        $precoNew->manipular();

        // Disparando o Job
        UpdateStockJob::dispatch($produto->id,$produto->estoque_afiliado,$produto->estoque_minimo_afiliado);

      // MANIPULA O ESTOQUE DAS INTEGRAÇÕES
        try {
            if ($request->hasFile('photos')) {
                $firstImage = true; // Flag para identificar a primeira imagem nova

                // Obtém a última posição registrada para este produto
                $lastPosition = Images::where('product_id', $produto->getId())
                    ->max('position'); // Pega a maior posição existente

                $newPosition = ($lastPosition !== null) ? $lastPosition + 1 : 1; // Se não houver imagens, começa do 1

                foreach ($request->file('photos') as $photo) {
                    $fileName = $photo->getClientOriginalName();

                    // Salvar a foto no S3
                    $photo->storeAs(
                        'produtos/' . $produto->getId(),
                        $fileName,
                        's3'
                    );

                    // Se for a primeira imagem enviada, define como imagem principal do produto
                    if ($firstImage) {
                        $produto->image = $fileName;
                        $firstImage = false;
                    }

                    // Criar uma instância de imagem e salvar no banco de dados
                    $image = new Images();
                    $image->url = $fileName;
                    $image->product_id = $produto->getId();
                    $image->position = $newPosition; // Salva na posição correta
                    $image->save();

                    $newPosition++; // Incrementa para a próxima imagem
                }
            } else {
                // Nenhuma nova imagem foi enviada, verificar se a ordem foi alterada
                $existingImages = Images::where('product_id', $produto->id)
                    ->orderBy('position', 'asc')
                    ->first(); // Pega a primeira imagem da nova ordem

                if ($existingImages) {
                    $produto->image = $existingImages->url; // Atualiza o campo `image` com a primeira da lista
                } else {
                    $produto->image = null; // Se todas as imagens forem removidas, limpa o campo `image`
                }
            }

            $produto->save();
        } catch (\Exception $th) {
            echo $th->getMessage();
        }

        $products = $request->input('products');

        if ($products) {
            $kitId = $id; // ID do kit
           // Obtem os IDs dos produtos enviados no array
            $productIds = array_column($products, 'id');

            // Busca os produtos existentes no kit na tabela 'kits'
            $existingProducts = DB::table('kit')
                ->where('product_id', $kitId)
                ->pluck('id_product_kit')
                ->toArray();

            // Identifica os produtos que estão no kit, mas não foram enviados
            $productsNotSent = array_diff($existingProducts, $productIds);

            $removedProducts = [];
            if (!empty($productsNotSent)) {
                DB::table('kit')
                    ->where('product_id', $kitId)
                    ->whereIn('id_product_kit', $productsNotSent)
                    ->delete();
            }
            $produto->save();
            // Redireciona de volta com mensagens de sucesso
            return redirect()->back()->with([
                'message' => 'Kit atual com sucesso.',
                'removed_products' => $removedProducts,
            ]);
        }

        $produto->save();
        return redirect()->back()->with('success', 'Atualizado com sucesso!');

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



    // ROUTES API

    public function CartShow($id)
    {
        $product = Products::findOrFail($id);

        if ($product) {
            $viewData = [];
            $viewData['title'] = "MotoStore : " . $product->name;
            $viewData['subtitle'] = $product->name;
            $viewData['product'] = $product;
            $viewData['stock'] = $product->stock;
            $viewData['image'] = $product->image;
            return view('store.singleProductShow')->with('viewData', $viewData);
        }
        return redirect()->route('store.index');
    }

    public function getAllProduct()
    {
        $products = Products::where('isPublic', true)->paginate(10);
        if ($products) {
            return response()->json(["products" => $products]);
        }
    }

    public function getAllProductSearch(Request $request)
    {
        // Obtém o parâmetro de pesquisa enviado na requisição
        $searchTerm = $request->input('q');

        // Cria a query base para produtos públicos
        $query = Products::where('isPublic', true);

        // Adiciona a condição de pesquisa se o termo foi enviado
        if ($searchTerm) {
            $query->where('title', 'LIKE', '%' . $searchTerm . '%') // Busca pelo título
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%'); // Busca pela descrição
        }

        // Paginação dos resultados
        $products = $query->paginate(10);

         // Adiciona o URL completo da imagem para cada produto
         foreach ($products as $produto) {
            $produto->imagem_url = Storage::disk('s3')->url('produtos/' . $produto->id . '/' . $produto->image);
        }

        // Retorna os produtos em formato JSON
        return response()->json(["products" => $products]);
    }



    public function getAttributesForVariations(Request $request)
    {
        // ENDPOINT PARA REQUISICAO
        $endpoint = 'https://api.mercadolibre.com/items/' . $request->base;

        $token = token::where('user_id_mercadolivre', $request->user)->first();

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $dados = json_decode($response);

            if($this->isClother($dados->domain_id,$token)){
                $tipo = new MlbTipos($dados->domain_id);
                $call = (new MlbCallAttributes($tipo))->resource();
                $dadosAttr = $tipo->requiredAtrributes($call,$dados);
                $chart = new GeneratechartsSneakers();
                $newCharts = new Generatecharts("GRADE UNIVERSAL".uniqid('CHART'),$dados->domain_id,$dadosAttr,$chart->getMainAttribute(),$chart->getAttributesSneakers());
                $chart = $newCharts->requestChart($request->user);
                $variacao = $newCharts->insertDataResult($dados,$chart);
                Log::debug(json_encode($chart['rows']));
                if ($httpcode == '200') {
                    $array = [];
                    $array = ["attributes" => [$chart['id']], "pictures" => $dados->pictures, "variations" => $variacao];
                    // ATUALIZA O ANUNCIO
                     return $this->PutAttributesForVariations($request->id, $array, $request->user);
                 }
            }

            if ($httpcode == '200') {
               $array = [];
               $array = ["pictures" => $dados->pictures, "variations" =>  $this->removeCategoryIdFromJson($dados->variations)];
                // ATUALIZA O ANUNCIO
                return $this->PutAttributesForVariations($request->id, $array, $request->user);
            }
        } catch (\Exception $e) {
            // Log::emergency($e->getMessage());
            return response()->json($e->getMessage());
        }
    }

    public function getAttributes(Request $request)
    {
        // ENDPOINT PARA REQUISICAO
        $endpoint = 'https://api.mercadolibre.com/items/' . $request->base;

        $token = token::where('user_id', $request->auth)->first();

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $dados = json_decode($response);

            if ($httpcode == '200') {
                $data = [];
                $fotos = false;
                $info = false;
                $descricao = false;

                foreach ($request->atributos as $atributo) {
                    if ($atributo == "fotos") {
                        array_push($data, [$dados->pictures, $dados->category_id]);
                        $fotos = true;
                    }
                    if ($atributo == "info") {
                        array_push($data, [$dados->attributes]);
                        $info = true;
                    }
                    if ($atributo == "descricao") {
                        $descricao = true;
                    }
                }

                $array = [];
                if (count($data) == 1 && $info == true) {
                    $array = [
                        "attributes" => $dados->attributes, "category_id" => $dados->category_id
                    ];
                } else if (count($data) == 1 && $fotos == true) {
                    $array = [
                        "pictures" => $dados->pictures, "category_id" => $dados->category_id
                    ];
                } else if (count($data) == 2 && $request->tp_cadastro == "variacao") {
                    $array = ["pictures" => $dados->pictures, "category_id" => $dados->category_id, "attributes" => $dados->attributes, "variations" => $this->removeCategoryIdFromJson($dados->variations)];
                } else if (count($data) == 2 && $request->tp_cadastro == "duplicar") {
                    $array = [
                        "pictures" => $dados->pictures,
                        "category_id" => $dados->category_id,
                        "attributes" => $dados->attributes,
                        "price" => $dados->price,
                        "currency_id" => "BRL",
                        "listing_type_id" => "gold_special",
                        "title" => $request->title,
                        "available_quantity" => 0
                    ];

                    // CADASTRA UM NOVO ANUNCIO
                    return $this->cadastrarAnuncio($request->base,$request->auth,$array);

                } else if (count($data) == 2 && $request->tp_cadastro == "N/D") {
                    $array = ["pictures" => $dados->pictures, "category_id" => $dados->category_id, "attributes" => $dados->attributes];
                }
                // ATUALIZA O ANUNCIO
                return $this->PutAttributes($request->id, $array, $request->base, $request->auth, $descricao);
            } else {
                echo $httpcode;
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function removeCategoryIdFromJson($data)
    {
        $removeArray = [];
        $array = json_decode(json_encode($data));

        try {
            foreach ($array as $value) {
                unset($value->catalog_product_id);
                // OBRIGATORIO PASSAR A QUANTIDADE DISPONIVEL DA VARIACAO
                $value->available_quantity = "100";
                array_push($removeArray, $value);
            }
            return $removeArray;
        } catch (\Exception $th) {
            return $th->getMessage();
        }

    }

    public function cadastrarAnuncio($base,$auth,$data)
    {
        $token = token::where('user_id', $auth)->first();
        $endpoint = 'https://api.mercadolibre.com/items/';
        // CONVERTE O ARRAY PARA JSON

        if (isset($data)) {
            $data_json = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
            $reponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $json = json_decode($reponse);
            if ($httpCode == '201') {
                    $this->postDescription($json->id, $this->getDescription($base), $auth);
                    return response()->json(["resposta" => $json->id . " Cadastrado com sucesso!"]);
            } else {
                return response()->json($json);
            }
        }
    }

    public function PutAttributes($ids, $data, $base, $auth, $descricao)
    {
        $token = token::where('user_id', $auth)->first();


        // ENDPOINT PARA REQUISICAO
        if (count($ids) > 1) {
            try {
                $res = [];
                foreach ($ids as $id) {
                    $endpoint = 'https://api.mercadolibre.com/items/' . $id;
                    // CONVERTE O ARRAY PARA JSON
                    if (isset($data)) {
                        $data_json = json_encode($data);

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $endpoint);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
                        $reponse = curl_exec($ch);
                        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);
                        $json = json_decode($reponse);
                        if ($httpCode == '200') {
                            if ($descricao == true) {
                                $this->postDescription($id, $this->getDescription($base), $auth);
                            }
                            array_push($res, ["id" => $json->id, "title" => $json->title]);
                        } else {
                            return response()->json($httpCode);
                        }
                    } else {
                        if ($descricao == true) {
                            $this->postDescription($id, $this->getDescription($base), $auth);
                            return response()->json(["resposta" => $id . " Atualizado com sucesso!"]);
                        }
                    }
                }
                return response()->json($res);
            } catch (\Exception $e) {
                return response()->json($e->getMessage());
            }
        } else {

            $endpoint = 'https://api.mercadolibre.com/items/' . $ids[0];

            try {
                // CONVERTE O ARRAY PARA JSON
                if (count($data) > 0) {
                    $data_json = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $endpoint);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
                    $reponse = curl_exec($ch);
                    Log::alert($reponse);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    $json = json_decode($reponse);
                    if ($httpCode == '200') {
                        if ($descricao == true) {
                            $this->postDescription($ids[0], $this->getDescription($base), $auth);
                        }
                        return response()->json(["resposta" => $json->title . " Atualizado com sucesso!"]);
                    } else {
                        return response()->json($json);
                    }
                } else {
                    if ($descricao == true) {
                        $this->postDescription($ids[0], $this->getDescription($base), $auth);
                        return response()->json(["resposta" => $ids[0] . " Atualizado com sucesso!"]);
                    }
                }
            } catch (\Exception $e) {
                return response()->json($e->getMessage());
            }
        }
    }


    public function PutAttributesForVariations($id, $data, $auth)
    {
        $token = token::where('user_id_mercadolivre', $auth)->first();
        // ENDPOINT PARA REQUISICAO

            try {
                $res = [];

                    $endpoint = 'https://api.mercadolibre.com/items/' . $id;
                    // CONVERTE O ARRAY PARA JSON
                    if (isset($data)) {
                        $data_json = json_encode($data);
                        Log::critical($data_json);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $endpoint);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
                        $reponse = curl_exec($ch);
                        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);

                        $json = json_decode($reponse);
                        if ($httpCode == '200') {
                            return "<li class='list-group-item bg-success py-2 text-white'><i class='bi bi-check-circle-fill'></i> Variações Criada com Sucesso</li>";
                        }else{
                            Log::error($reponse);
                        }
                    }

                return response()->json($res);
            } catch (\Exception $e) {
                // return response()->json($e->getMessage());
            }


    }

    public function getDescription($idproduto)
    {
        //ENDPOINT https://api.mercadolibre.com/items/$ITEM_ID/description
        // ENDPOINT PARA REQUISICAO
        $endpoint = "https://api.mercadolibre.com/items/$idproduto/description";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $dados = json_decode($response);
        if ($httpcode == '200') {
            return $dados->plain_text;
        }
    }

    public function postDescription($idproduto, $descricao, $auth)
    {
        $token = token::where('user_id', $auth)->first();
        // ENDPOINT PARA REQUISICAO
        $endpoint = "https://api.mercadolibre.com/items/$idproduto/description";

        $data_json = [
            "plain_text" => $descricao
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_json));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    }

    public function getProduct(Request $request)
    {
        $product = Products::where('id', '=', $request->id)->first();
        $fotos = images::where('product_id', $request->id)->get();
        $photos = [];
        foreach ($fotos as $foto) {
            array_push($photos, $foto->url);
        }
        $data = [];

        if ($product) {
            $data['id'] = $product->id;
            $data['title'] = $product->title;
            $data['image'] = Storage::disk('s3')->url('produtos/' . $product->getId() . '/' . $product->getImage());
            $data['category_id'] = $product->category_id;
            $data['price'] = $product->price;
            $data['currency_id'] = $product->currency_id;
            $data['available_quantity'] = $product->available_quantity;
            $data['buying_mode'] = $product->buying_mode;
            $data['listing_type_id'] = $product->listing_type_id;
            $data['condition'] = $product->condition;
            $data['description'] = $product->description;
            $data['priceWithFee'] = $product->priceWithFee;
            $data['priceKit'] = $product->priceKit;
            $data['link'] = $product->link;
            $data['ean'] = $product->gtin;
            $data['tags'] = [
                "immediate_payment",
            ];
            $data['fotos_adicionais'] = [
                "fotos" => $photos
            ];
            $data['attributes'] = [
                [
                    "id" => "BRAND",
                    "name" => "Marca",
                    "value_name" => $product->brand
                ],
                [
                    "id" => "GTIN",
                    "name" => "Marca",
                    "value_name" => $product->gtin
                ],
            ];

            $data['pictures'] = [
                [
                    "source" => $product->image,
                ]
            ];

            return response()->json($data);
        }
    }

    public function getAllProductByTipoAnuncio(Request $request)
    {
        $dados = Products::where('colunasAnuncio', $request->id)->get();
        return response()->json(["responseColuna" => $dados]);
    }

    public function getParametersByName(Request $request)
    {
        $dados = json_decode($request->where);
        $datasDB = Products::where('title', 'like', '%' . $dados->title . '%')->get();

        return response()->json(["products" => $datasDB]);
    }

    public function IntegrarProduto(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:60',
            'tipo_anuncio' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $dadosIntegrado = [];
        if (isset($request->precoFixo)) {
            // Mantém o valor fixo se estiver preenchido
            $dadosIntegrado['precofixo'] = $request->precoFixo;
        } else {
            $dadosIntegrado['valor_tipo'] = $request->valor_tipo;
            $dadosIntegrado['isPorcem'] = $request->isPorcem;
            $dadosIntegrado['valor'] = $request->valor_agregado;
        }


        $name = $request->name;
        $tipo_anuncio = $request->tipo_anuncio;
        $price = str_replace(',', '.', $request->input('totalInformado'));
        $id_categoria = $request->id_categoria;
        $id_product = $request->id_prodenv;
        $descricao = $request->editor;
        $valorSemTaxa = 0;
        $totalInformado = 0;

        if($request->category_default && !isset($request->id_categoria)){
            $id_categoria = $request->category_id;
        }

        $array = [];
        // Itera sobre os dados recebidos
        foreach ($request->all() as $key => $value) {
            // Verifica se a chave possui letras maiúsculas
            if (preg_match('/[A-Z]/', $key)) {
                // Adiciona o parâmetro ao array de parâmetros com letras maiúsculas
                array_push($array,["id" => $key,"value" => $value, "values" => [["id" => $value,"name" => $value]]]);
            }
        }

        $factory = new ProdutoImplementacao($name, $tipo_anuncio, $price, $id_categoria, $id_product, Auth::user()->id,$descricao,$array,$valorSemTaxa,$totalInformado,$dadosIntegrado);
        $data = $factory->getProduto();

        if ($data) {
            return redirect()->back()->withErrors($data);
        }

        $request->session()->put('msg', 'Produto Cadstrado com Sucesso!');
        return redirect()->back();
    }

    public function getHistoryById(Request $request)
    {
        $data = mercado_livre_history::where('id_user', $request->id_user)->where('product_id', $request->id)->get();
        return response()->json(["dados" => $data]);
    }

    public function GetPromotionProducts()
    {
        // PRODUTOS EM PROMOÇÂO
        $data = Products::where('pricePromotion', '>', '0')->where('isPublic', true)->paginate(10);

        $viewData = [];
        $viewData['title'] = "Afilidrop";
        $viewData['subtitle'] = 'Promoções';
        $viewData['products'] = $data;
        $viewData['logo'] = logo::first();

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $subcategorias = [];

        foreach (categorias_forncedores::all() as $value) {

            $subcategorias[$value->id] = [
                "nome" => $value->name,
                "subcategory" => sub_categoria_fornecedor::getAllCategory($value->id),
            ];
        }

        $viewData['subcategorias'] = $subcategorias;

        $viewData['categorias'] = $categorias;
        return view('store.index')->with('viewData', $viewData);
    }

    public function GetProductsLancamentos()
    {
        // DATA INCIIAL
        $datainicial = new DateTime();
        $datainicial->modify('-30 days');
        // DATA FINAL
        $datafinal = new DateTime();
        // PRODUTOS EM PROMOÇÂO
        $id = User::getProducts(Auth::user()->user_subcategory);
        $data = Products::getProductByFornecedorLancamentos($id,$datainicial,$datafinal);

        $viewData = [];
        $viewData['title'] = "Afilidrop";
        $viewData['subtitle'] = 'Lançamentos';
        $viewData['lancamentos'] = 1;
        $viewData['products'] = $data;
        $viewData['logo'] = logo::first();

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $subcategorias = [];

        foreach (categorias_forncedores::all() as $value) {

            $subcategorias[$value->id] = [
                "nome" => $value->name,
                "subcategory" => sub_categoria_fornecedor::getAllCategory($value->id),
            ];
        }

        $viewData['subcategorias'] = $subcategorias;


        $viewData['categorias'] = $categorias;

        return view('store.index')->with('viewData', $viewData);
    }

    public function GetProductsKits()
    {
        // PRODUTOS EM PROMOÇÂO
        $data = Products::where('isPublic', true)->where('isKit', true)->paginate(10);

        $viewData = [];
        $viewData['title'] = "Afilidrop";
        $viewData['subtitle'] = 'Kits';
        $viewData['products'] = $data;
        $viewData['logo'] = logo::first();

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $viewData['categorias'] = $categorias;

        return view('store.index')->with('viewData', $viewData);
    }

    public function GetAutoKM()
    {
        // PRODUTOS EM PROMOÇÂO
        $data = Products::where('isPublic', true)->where('colunasAnuncio', 1)->paginate(10);

        $viewData = [];
        $viewData['title'] = "Afilidrop";
        $viewData['subtitle'] = 'AutoKM';
        $viewData['products'] = $data;
        $viewData['logo'] = logo::first();
        $viewData['banners'] = banner_autokm::all();

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $viewData['categorias'] = $categorias;

        return view('autokm.index')->with('viewData', $viewData);
    }

    public function GetPremiumProducts()
    {

        // PRODUTOS EM PROMOÇÂO
        $data = Products::where('isPublic', true)->where('colunasAnuncio', 1)->paginate(10);

        $viewData = [];
        $viewData['title'] = "Afilidrop";
        $viewData['subtitle'] = 'Produtos Premium';
        $viewData['products'] = $data;
        $viewData['logo'] = logo::first();
        $viewData['banners'] = banner_premium::all();

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $viewData['categorias'] = $categorias;
        return view('premium.index')->with('viewData', $viewData);
    }


    public function todosProdutos(Request $request)
    {
        // PRODUTOS EM PROMOÇÂO
        $data = Products::select('products.*')
        ->where('products.fornecedor_id', Auth::user()->id)
        ->where('products.isKit',0)
        ->where(function ($query) {
             $query->where('products.owner', Auth::user()->id)
                   ->orWhereNull('products.owner');
        })
        ->orderBy('products.id', 'desc')
        ->paginate(10);

        $viewData = [];
        $viewData['title'] = "Afilidrop";
        $viewData['subtitle'] = 'AutoKM';
        $viewData['products'] = $data;

        return view('orders.fornecedor.produtos')->with('viewData', $viewData);
    }

    public function updateProduct()
    {
        $produtos = Products::where('isPublic', '1')->where('id_mercadolivre', '!=', "")->get();
        foreach ($produtos as $value) {
            \App\Jobs\getStockPrice::dispatch($value->id_mercadolivre);
        }
    }

    public function getProducts(Request $request){

        try {
            $token = token::where('user_id_mercadolivre', $request->user)->first();
        // ENDPOINT PARA REQUISICAO
        $endpoint = "https://api.mercadolibre.com/users/{$request->user}/items/search?status={$request->status}&search_type=scan&q={$request->item}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $res = json_decode($response);

        $input = [];
        foreach ($res->results as $key => $value) {
            array_push($input,json_decode($this->getDataProducts($value)));

        }

        return response()->json(['results' => $input]);

        } catch (\Exception $e) {
            Log::alert($e->getMessage());
        }

    }


    public function dataHome(Request $request){

        try {
            $dataView = [];
            $order = order_site::OrdersMercadoLivreDay($request->user,"");
            $valorMedioDiario = order_site::OrdersMercadoLivreDayQtd($request->user);
            $qtdVendasMes = order_site::totalVendasMes($request->user);
            $qtdVendaDia = order_site::totalVendasDia($request->user);
            $dataView['totalVendasDia'] = number_format($order,2);
            $dataView['valorMedio'] = number_format($order / $valorMedioDiario,2);
            $dataView['qtdVendasMes'] = number_format($qtdVendasMes,2);
            $dataView['VendasPorDia'] = number_format($qtdVendaDia,2);
            return response()->json($dataView);
        } catch (\DivisionByZeroError  $th) {
            $dataView = [];
            $order = order_site::OrdersMercadoLivreDay($request->user,"");
            $valorMedioDiario = order_site::OrdersMercadoLivreDayQtd($request->user);
            $qtdVendasMes = order_site::totalVendasMes($request->user);
            $qtdVendaDia = order_site::totalVendasDia($request->user);
            $dataView['totalVendasDia'] = number_format($order,2);
            $dataView['valorMedio'] = "0.00";
            $dataView['qtdVendasMes'] = $qtdVendasMes;
            $dataView['VendasPorDia'] = $qtdVendaDia;
            return response()->json($dataView);
        }

    }


    public function getPedidosById(Request $request)
    {
        $order = order_site::getOrderjoinCompleteByApp($request->order);
        return response()->json($order);
    }


    public function getPedidos()
{
    $startDate = Carbon::now()->subDays(6)->startOfDay(); // Começa há 6 dias atrás para incluir hoje
    $endDate = Carbon::now()->endOfDay(); // Hoje como último dia

    // Cria um array com os últimos 7 dias formatados
    $days = collect();
    for ($i = 0; $i < 7; $i++) {
        $days->push([
            'dataVenda' => Carbon::now()->subDays(6 - $i)->format('d/m'), // Exemplo: 10/01
            'valorTotal' => 'R$ 0,00'
        ]);
    }

    // Busca as vendas dos últimos 7 dias agrupadas por data
    $pedidos = order_site::select(
            DB::raw("DATE(dataVenda) as data"),
            DB::raw("SUM(valorVenda) as valorTotal")
        )
        ->whereBetween('dataVenda', [$startDate, $endDate])
        ->groupBy('data')
        ->orderBy('data', 'asc')
        ->get()
        ->keyBy(function ($item) {
            return Carbon::parse($item->data)->format('d/m');
        });

    // Substitui os valores padrão pelos reais, se houver vendas
    $days = $days->map(function ($day) use ($pedidos) {
        if ($pedidos->has($day['dataVenda'])) {
            $day['valorTotal'] = 'R$ ' . number_format($pedidos[$day['dataVenda']]->valorTotal, 2, ',', '.');
        }
        return $day;
    });

    return response()->json([
        'data' => $days
    ]);
}



    public function getSalesData(Request $request)
    {
         // Defina a chave do cache
         $cacheKey = 'metricasVendasHomePage';

         // Tempo em minutos que o cache será mantido
         $cacheTime = 2;

           // Verifique se já existe um cache
           if (Cache::has($cacheKey)) {
             // Se existir, recupere o resultado do cache
             $data = Cache::get($cacheKey);
           } else {
                // Exemplo de dados de vendas e tarifas
                $data = [
                    'labels' => order_site::getOrderByDashboard($request)['dataVenda'],
                    'datasets' => [
                        [
                            'label' => 'R$',
                            'data' => order_site::getOrderByDashboard($request)['valor'],
                            "backgroundColor" => 'rgba(75, 192, 192, 0.2)', // Cor de fundo
                            "borderColor"=> 'rgba(75, 192, 192, 1)', // Cor da linha
                            "pointBackgroundColor"=> 'rgba(75, 192, 192, 1)', // Cor do ponto
                            "pointBorderColor"=> '#fff' // Cor da borda do ponto
                        ],
                        [
                            'label' => 'Tarifa R$',
                            'data' => order_site::getOrderByDashboard($request)['tarifa'],
                            "backgroundColor" => 'rgba(220, 79, 79, 0.8)', // Cor de fundo
                            "borderColor"=> 'red', // Cor da linha
                            "pointBackgroundColor"=> 'red', // Cor do ponto
                            "pointBorderColor"=> '#fff' // Cor da borda do ponto
                        ]
                    ]
                ];
             // Armazene o resultado no cache
             Cache::put($cacheKey, $data, $cacheTime);
         }

        return response()->json($data);
    }


    public function fotoPreview(Request $request)
    {
        $imageUrls = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                // Salva o arquivo e obtém o caminho
                $path = $file->store('uploads', 'public');

                // Adiciona as informações no array, incluindo o nome original
                $imageUrls[] = [
                    'index' => count($imageUrls), // Índice atual
                    'url' => asset('storage/' . $path), // URL da imagem
                    'originalName' => $file->getClientOriginalName(), // Nome original do arquivo
                ];
            }
        }

        return response()->json($imageUrls);
    }


    public function getVisits(Request $request){

        $dataAtual = new DateTime();
        $newToken = new RefreshTokenController($request->access_token, $dataAtual, "3029233524869952", "y5kbVGd5JmbodNQEwgCrHBVWSbFkosjV", '38');
        $newToken->resource();

        // TESTE PARA VER SE O TOKEN ESTA EXPIRADO
        $acesso = token::where('user_id','38')->first();
        try {

            $context = stream_context_create([
                "http" => [
                    "header" => "Authorization: Bearer $acesso->access_token"
                ]
            ]);

            $response = file_get_contents("https://api.mercadolibre.com/items/{$request->item}/visits/time_window?last=60&unit=day&ending={$request->currentDate}",false,$context);

            return response($response)->header('Content-Type', 'application/json');  // Certifique-se de que o PHP retorne JSON puro

            } catch (\Exception $e) {
                return response()->json($e->getMessage());
            }
    }

    public function getVisitsExtension(Request $request){

        Log::alert($request->all());
        // TESTE PARA VER SE O TOKEN ESTA EXPIRADO
        $acesso = token::where('user_id','38')->first();
        try {

            $context = stream_context_create([
                "http" => [
                    "header" => "Authorization: Bearer $acesso->access_token"
                ]
            ]);

            $response = file_get_contents("https://api.mercadolibre.com/items/{$request->item}/visits/time_window?last=60&unit=day&ending={$request->currentDate}",false,$context);

            return response($response)->header('Content-Type', 'application/json');  // Certifique-se de que o PHP retorne JSON puro

            } catch (\Exception $e) {
                return response()->json($e->getMessage());
            }
    }

    public function destroyFotoS3(Request $request){{
        try {
               // // Parte da URL que você deseja remover
               $parteRemover = "https://afilidrop2.s3.us-east-1.amazonaws.com/";
               // // Remove a parte da URL
               $urlSemParte = str_replace($parteRemover, "", $request->imagem);
               $apagarImagem = images::where('url',basename($urlSemParte))->delete();
               if($apagarImagem){
                 Storage::disk('s3')->delete($urlSemParte);
                 return response()->json(["res" => "imagem ". basename($urlSemParte) ." apagada com sucesso"],200);
               }
        } catch (\Exception $th) {
            return response()->json(["res" => $th->getMessage()],400);
        }
     }
   }

    public function getDataProducts($id){

        try {
        // ENDPOINT PARA REQUISICAO
        $endpoint = "https://api.mercadolibre.com/items/{$id}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json']);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $response;

        } catch (\Exception $e) {
            Log::alert($e->getMessage());
        }

    }


    public function addProduct(Request $request)
    {
        try {
            // Valida os dados recebidos
            $validated = $request->validate([
                'product_id'     => 'required|exists:products,id',
                'id_product_kit' => 'required|exists:products,id',
                'quantity'       => 'required|integer|min:1',
                'user_id'        => 'required|exists:users,id',
            ]);

            // Busca o produto base e o produto do kit
            $product = DB::table('products')->where('id', $validated['product_id'])->first();
            $kitProduct = DB::table('products')->where('id', $validated['id_product_kit'])->first();

            // Verifica se os fornecedores são os mesmos
            if ($product->fornecedor_id !== $kitProduct->fornecedor_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'O fornecedor do produto não corresponde ao fornecedor do kit.'
                ], 200);
            }

            // Se os fornecedores forem iguais, cria ou atualiza o registro do kit
            $kit = new Kit();
            $kit->product_id = $validated['product_id'];
            $kit->id_product_kit = $validated['id_product_kit'];
            $kit->available_quantity = $validated['quantity'];
            $kit->user_id = $validated['user_id'];
            $kit->save();

            session()->flash('success', 'Produto adicionado ao kit com sucesso!');
            return response()->json([
                'success' => true,
                'message' => 'Produto adicionado ao kit com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function integrados(){

        $viewData = [];
        $viewData['products'] = produtos_integrados::getProdutos(Auth::user()->id);
        $viewData['title'] = "Produtos Integrados";

        return view('integrados.index',[
            'viewData' => $viewData
        ]);
    }


    public function exclusivos(Request $request)
    {
        $viewData = [];
        $viewData['title'] = "Produtos Exclusivos";
        $categoriaKeyCache = 'categoriasProdutos';

        // Tempo em minutos que o cache será mantido
        $cacheTime = 5;

        $viewData['products'] = Products::getResultsExclusive($request);

        $data = [];
        $categorias = [];
       foreach (categorias::all() as $value) {
           $categorias[$value->id] = [
               "nome" => $value->name,
               "subcategory" => sub_category::getAllCategory($value->id),
           ];
       }

        // Verifique se já existe um cache
        if (Cache::has($categoriaKeyCache)) {
        // Se existir, recupere o resultado do cache
             $viewData['categorias'] = Cache::get($categoriaKeyCache);
        }else{
            $viewData['categorias'] = $categorias;
            Cache::put($categoriaKeyCache, $viewData['categorias'], now()->addMinutes($cacheTime));
        }

        $viewData['filtro'] = $request->all();
        return view('admin.exclusivos', compact('viewData'));
    }


    public function integrarProdutoviaApi(Request $request){
        // Log::alert(($request->all()));

        $isporcem = [2,4];

        $tabela = [
            '1' => 'acrescimo_reais',
            '2' => 'acrescimo_porcentagem',
            '3' => 'desconto_reais',
            '4' => 'desconto_porcentagem'
        ];

        $tipo_anuncio = $request->tipo_anuncio;
        $price = $request->input('preco');

        $dadosIntegrado = [];
        if (isset($request->precoFixo)) {
            // Mantém o valor fixo se estiver preenchido
            $dadosIntegrado['precofixo'] = $request->precoFixo;
            $price = $request->precoFixo;
        } else {
            $dadosIntegrado['valor_tipo'] = $tabela[$request->agregado];
            $dadosIntegrado['isPorcem'] = in_array($request->agregado,$isporcem) ? 1 : 0;
            $dadosIntegrado['valor'] = $request->valor_agregado;
        }

        $name = $request->title;
        $id_categoria = $request->id_categoria;
        $id_product = $request->id_prodenv;
        $descricao = $request->editor;
        $valorSemTaxa = 0;
        $totalInformado = 0;

        if($request->category_default && !isset($request->id_categoria)){
            $id_categoria = $request->category_id;
        }

        $array = [];
        // Itera sobre os dados recebidos
        foreach ($request->all() as $key => $value) {
            // Verifica se a chave possui letras maiúsculas
            if (preg_match('/[A-Z]/', $key)) {
                // Adiciona o parâmetro ao array de parâmetros com letras maiúsculas
                array_push($array,["id" => $key,"value" => $value, "values" => [["id" => $value,"struct" => "null"]]]);
            }
        }

        //** MUDAR O 2 DO USUARIO PARA TESTE  */
        $factory = new ProdutoImplementacao($name, $tipo_anuncio, $price, $id_categoria, $id_product, 1,$descricao,$array,$valorSemTaxa,$totalInformado,$dadosIntegrado);
        $data = $factory->getProdutoByApi();

        return response()->json([
            'message' => $data['message']
        ],$data['statusCode']);
    }


    public function produtosIntegradosMLApi(Request $request){
        Log::alert($request->all());
        $data = produtos_integrados::getProdutosByApi($request->user_id);
        return response()->json([
            'response' => $data
        ]);
    }


    function isZeroOrNull($value) {
        // Se o valor for nulo ou estiver vazio (incluindo "0" ou "0.0") consideramos como zero
        return is_null($value) || floatval($value) == 0;
    }

    public function EnviarDadosIntegradosMLApi(Request $request){

        Log::alert($request->all());
        $isporcem = [2,4];

        try{
            $tabela = [
                '1' => 'acrescimo_reais',
                '2' => 'acrescimo_porcentagem',
                '3' => 'desconto_reais',
                '4' => 'desconto_porcentagem'
            ];

                if ($this->isZeroOrNull($request->preco)
                && $this->isZeroOrNull($request->valor_tipo)
                && $this->isZeroOrNull($request->precoFixo)
                && $this->isZeroOrNull($request->valor_agregado)) {

                $product = produtos_integrados::findOrFail($request->id);
                $product->active = $request->active;
                $product->estoque_minimo = $request->estoque_minimo;
                $product->save();
                $dadosDoProdutoOriginal = Products::where('id',$product->product_id)->first();
                $estoqueNew = new MercadoLivreStockController($product->id_mercadolivre,$dadosDoProdutoOriginal->estoque_afiliado,$request->active,$request->estoque_minimo,$product->user_id,$dadosDoProdutoOriginal->estoque_minimo_afiliado);
                $estoqueNew->updateStatusActive();
                }else{
                // Encontra o produto pelo ID
                $product = produtos_integrados::findOrFail($request->id);
                $product->isPorcem = in_array($request->valor_tipo,$isporcem) ? 1 : 0;

                if($request->valor_tipo != 0){
                    // Atualiza os campos conforme a regra de negócio
                    if ($tabela[$request->valor_tipo] == 'acrescimo_reais') {
                        $product->acrescimo_reais = $request->valor_agregado;
                        $product->acrescimo_porcentagem = null;
                        $product->desconto_reais = null;
                        $product->desconto_porcentagem = null;
                    } elseif ($tabela[$request->valor_tipo] == 'acrescimo_porcentagem') {
                        $product->acrescimo_reais = null;
                        $product->acrescimo_porcentagem = $request->valor_agregado;
                        $product->desconto_reais = null;
                        $product->desconto_porcentagem = null;
                    } elseif ($tabela[$request->valor_tipo] == 'desconto_reais') {
                        $product->acrescimo_reais = null;
                        $product->acrescimo_porcentagem = null;
                        $product->desconto_reais = $request->valor_agregado;
                        $product->desconto_porcentagem = null;
                    } elseif ($tabela[$request->valor_tipo] == 'desconto_porcentagem') {
                        $product->acrescimo_reais = null;
                        $product->acrescimo_porcentagem = null;
                        $product->desconto_reais = null;
                        $product->desconto_porcentagem = $request->valor_agregado;
                    }
                }

                if ($request->filled('precoFixo')) {
                    $product->precofixo = $request->precoFixo;
                    $product->acrescimo_reais = null;
                    $product->acrescimo_porcentagem = null;
                    $product->desconto_reais = null;
                    $product->desconto_porcentagem = null;
                }

                $product->active = $request->active;
                $product->estoque_minimo = $request->estoque_minimo;

                $product->save();
                $dadosDoProdutoOriginal = Products::where('id',$product->product_id)->first();
                $estoqueNew = new MercadoLivreStockController($product->id_mercadolivre,$dadosDoProdutoOriginal->estoque_afiliado,$request->active,$request->estoque_minimo,$product->user_id,$dadosDoProdutoOriginal->estoque_minimo_afiliado);
                $estoqueNew->updateStock();

                $precoNew = new ManipuladorProdutosIntegrados($request->id,0);
                $precoNew->atualizarOnlyProduct();

            }

            return response()->json(['data' => 'atualizado com sucesso'],200);
        } catch (\Exception $e) {
            Log::alert($e->getMessage());
            return response()->json(['message' => $e->getMessage()],400);
        }
    }

    public function salvarOrdem(Request $request)
    {

        $images = $request->input('images');

        foreach ($images as $image) {

            Images::where('id', $image['id'])->update(['position' => $image['position']]);
        }

        return response()->json(['success' => true, 'message' => 'Ordem atualizada com sucesso!']);
    }


    public function getKitsByOwner(Request $request)
    {
        // Obtém o parâmetro 'id' da query string (representa o owner)
        $owner = $request->query('id');

        if (!$owner) {
            return response()->json([
                'error' => 'Parâmetro id (owner) é obrigatório.'
            ], 400);
        }

        // Busque os kits onde a coluna 'owner' é igual ao valor informado
        $kits = Products::where('owner', $owner)->paginate();

        return response()->json($kits);
    }


    public function getComposicaoKit(Request $request){
        // Obtém o parâmetro 'id' da query string (representa o owner)
        $kit = $request->query('id');
        $data = kit::getProductsByKit($kit);
        return response()->json($data);
    }


    public function getInformacoesAdicionais(Request $request){
        $data = Financeiro::findOrFail($request->id);
        Log::alert($data);
        return response()->json(['plain_text' => $data->informacoes]);
    }

    public function recadastrar(Request $request)
    {

        // Valide o campo enviado
        $request->validate([
            'produto_id' => 'required|string',
            'token' => 'required'
        ]);

        $url = "https://api.mercadolibre.com/items/{$request->produto_id}";

        $payload = [
            'status' => 'closed'
        ];



        try {
            // Enviando a requisição para o Mercado Livre
            $response = Http::withToken($request->token)
                ->put($url, $payload);

            Log::alert($response->json());
            // Tratando a resposta
            if ($response->successful()) {

                return response()->json([
                    'success' => true,
                    'message' => 'Finalizado com sucesso!',
                    'data' => $response->json(),
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'erro ao finalizar o produto!',
                    'data' => $response->json(),
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro inesperado ao finalizar o produto',
                'error' => $e->getMessage(),
            ], 500);  // 500 Internal Server Error
        }
    }

    public function recadastrarExtensao(Request $request)
    {
        // Valide o campo enviado
        $request->validate([
            'produto_id' => 'required|string',
            'user_id' => 'required'
        ]);

        $token = TokenUpMineracao::where('user_id',$request->user_id)->first();

        $url = "https://api.mercadolibre.com/items/{$request->produto_id}";

        $payload = [
            'status' => 'closed'
        ];

        try {
            // Enviando a requisição para o Mercado Livre
            $response = Http::withToken($token->access_token)
                ->put($url, $payload);

            Log::alert($response->json());
            // Tratando a resposta
            if ($response->successful()) {

                return response()->json([
                    'success' => true,
                    'message' => 'Finalizado com sucesso!',
                    'data' => $response->json(),
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'erro ao inativar o produto!',
                    'data' => $response->json(),
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro inesperado ao finalizar o produto',
                'error' => $e->getMessage(),
            ], 500);  // 500 Internal Server Error
        }
    }

    public function getVisitas($itemId){

    $token = token::where('user_id',34)->first();

    $response = Http::withToken($token->access_token)
        ->get("https://api.mercadolibre.com/visits/items", [
            'ids' => $itemId
        ]);


    if ($response->successful()) {
        return response()->json($response->json());
    }

    return response()->json([
        'error' => 'Erro ao buscar visitas',
        'status' => $response->status()
    ], $response->status());
}


    public function getCategories()
    {
         $token = token::where('user_id',Auth::user()->id)->first();

        $response = Http::withToken($token->access_token)
            ->get("https://api.mercadolibre.com/sites/MLB/categories");

        return response()->json($response->json(), $response->status());
    }


    public function getCategoryById($category){

           $token = token::where('user_id',Auth::user()->id)->first();

            if (!$token) {
                return response()->json(['error' => 'Token não fornecido'], 400);
            }

            $response = Http::withToken($token->access_token)
                ->get("https://api.mercadolibre.com/categories/" . $category);

            return response()->json($response->json(), $response->status());
    }

        public function getCategoryAttributeById($category){

            $token = token::where('user_id',Auth::user()->id)->first();

            if (!$token) {
                return response()->json(['error' => 'Token não fornecido'], 400);
            }

            $response = Http::withToken($token->access_token)
                ->get("https://api.mercadolibre.com/categories/" . $category."/attributes");
            return response()->json($response->json(), $response->status());
    }

}
