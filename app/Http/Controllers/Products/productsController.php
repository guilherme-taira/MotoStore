<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Job\getProductDataController;
use App\Http\Controllers\MercadoLivre\ProdutoImplementacao;
use App\Http\Controllers\MercadoLivreHandler\ConcretoDomainController;
use App\Http\Controllers\MercadoLivreHandler\getDomainController;
use App\Http\Controllers\Services\ServicesController;
use App\Models\banner_autokm;
use App\Models\banner_premium;
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
use App\Http\Controllers\MercadoLivre\Generatecharts;
use App\Http\Controllers\MercadoLivre\GeneratechartsSneakers;
use App\Http\Controllers\MercadoLivre\MlbCallAttributes;
use App\Http\Controllers\MercadoLivre\MlbTipos;
use Illuminate\Support\Facades\Auth;

ini_set('max_execution_time', 30); //300 seconds = 5 minutes

class productsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "MotoStore Produtos";
        $viewData['products'] = Products::where('iskit', '0')->paginate(10);
        return view('admin.products')->with('viewData', $viewData);
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
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $viewData['categorias'] = $categorias;
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
        // SERVICE TRATA PREÇO
        $TrataPreco = new ServicesController();

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5',
            'price' => 'required|min:1',
            "stock" => "required|numeric",
            'description' => 'required',
            'brand' => 'required|min:3',
            'id_categoria' => 'required',
            'ean' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $produto = new Products();
        $produto->title = $request->name;
        $produto->price = $TrataPreco->RegexPrice($request->price);
        $produto->description = $request->description;
        $produto->available_quantity = 10;
        // Categoria Principal Removido da inserção
        //$produto->categoria = $produto::getIdPrincipal($request->categoria);
        $produto->category_id = $request->id_categoria;
        $produto->subcategoria = $request->categoria;
        $produto->pricePromotion = $TrataPreco->RegexPrice($request->pricePromotion);
        $produto->brand = $request->brand;
        $produto->gtin = $request->ean;
        $produto->image = 'image.png';
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
        return redirect()->route('products.store');
    }


    public function tradeCategoria(Request $request){
        return $this->getAttributesTrade($request);
    }

    public function getHistory(Request $request){
        $logs = historico::where('user_id',$request->user)->limit(10)->orderBy('created_at', 'desc')->get();
        $history = [];
        foreach ($logs as $log) {
            array_push($history,['ACAO' => $log->acao, 'PRODUTO' =>json_decode($log->message)->thumbnail, 'TEMPO' => $log->created_at,'SUCESSO'=> $log->sucesso == true ? 'success' : 'danger']);
        }
       return response()->json($history);
    }

    public function getAttributesTrade(Request $request)
    {

        Log::critical(json_encode($request->all()));
        if($request->base){
             // ENDPOINT PARA REQUISICAO
        $endpoint = 'https://api.mercadolibre.com/items/'.$request->base;

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
                $this->TrocarCategoriaRequest($data,FALSE,$data['id'],$request->categoria,$request->user,$request->newtitle);
            }
        }

    }

    public function isClother($attr,$auth):bool {

        $context = stream_context_create([
            "http" => [
                "header" => "Authorization: Bearer $auth->access_token"
            ]
        ]);

        $dominios = file_get_contents('https://api.mercadolibre.com/catalog/charts/MLB/configurations/active_domains',false,$context);

        foreach (array_values(json_decode($dominios,true)['domains']) as $value) {
            if($value['domain_id'] == $attr){
                return true;
            }
        }
        return false;
    }


    public function refazerRequest($id,$user,$data,$domain,$newtitle) {

        $endpoint = 'https://api.mercadolibre.com/items/'.$id;

        $token = token::where('user_id_mercadolivre',$user)->first();

            unset($data['location']);

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

    public function TrocarCategoriaRequest($data, $try = FALSE, $id,$categoria,$user,$newtitle) {
        $ids = $id;
        $category = $categoria;
        // NUMERO DE TENTATIVAS
        $endpoint = 'https://api.mercadolibre.com/items/'. $ids;

        $token = token::where('user_id_mercadolivre',$user)->first();

            if($try){
                $data_json = json_encode($data);
            }else{
                $data_json = json_encode(['category_id' => $categoria]);
                $try = TRUE;
            }

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
                logAlteracao::dispatch('TROCA DE CATEGORIA',$user,$reponse,true);
                echo "<li class='list-group-item bg-success text-white'><i class='bi bi-check-circle-fill'></i> Alterado com Sucesso</li>";
            } else {
                echo "<li class='list-group-item bg-danger text-white'><i class='bi bi-exclamation-circle-fill'></i> Arrumando Pendências..</li>";
                Log::notice(json_encode($json));
                Log::notice($data_json);
                try {
                    $domain = new getDomainController('12',$data['attributes']);
                    $concreto = new ConcretoDomainController($domain);
                    $concreto->CallAttributes($data);
                    $data_json = $concreto->CallErrorAttributes($json,$data,true,$category,$newtitle);

                    $this->TrocarCategoriaRequest($data_json,TRUE,$ids,$category,$user,$newtitle);
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }

            }

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

        $viewData = [];
        $viewData['title'] = "Afilidrop" . $produto->getName();
        $viewData['product'] = $produto;
        $viewData['categoriaSelected'] = sub_category::getNameCategory($produto->subcategoria);
        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
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
            "name" => "required|max:255",
            "description" => "required",
            "price" => "required|numeric|gt:0",
            "stock" => "required|numeric|gt:0",
            "categoria_mercadolivre" => "required|max:20",
            "brand" => "max:100",
            'image' => 'image',
            "ean" => "required|numeric",
            "tipo_anuncio" => "required|max:50",
            'pricePromotion' => 'numeric',
            'termometro' => 'numeric'
        ]);

        $produto = Products::findOrFail($id);
        $produto->setTitle($request->input('name'));
        $produto->setPrice($request->input('price'));
        $produto->setStock($request->input('stock'));
        $produto->SetCategory_id($request->input('categoria_mercadolivre'));
        $produto->SetListing_type_id($request->input('tipo_anuncio'));
        $produto->SetBrand($request->input('brand'));
        $produto->SetIsNft($request->input('isNft'));
        //$produto->setCategoria(Products::getIdPrincipal($request->input('categoria')));
        $produto->SetSubCategory_id($request->input('categoria'));
        $produto->SetGtin($request->input('ean'));
        $produto->setPricePromotion($request->input('pricePromotion'));
        $produto->setDescription($request->input('description'));
        $produto->SetLugarAnuncio($request->input('radio'));
        $produto->setIsPublic($request->input('isPublic'));
        $produto->SetFornecedor($request->input('fornecedor'));
        $produto->SetTermometro($request->input('termometro'));

        if ($request->hasFile('image')) {
            $imageName = $produto->getId() . "." . $request->file('image')->extension();
            Storage::disk('public')->put(
                $imageName,
                file_get_contents($request->file('image')->getRealPath())
            );
            $produto->setImage($imageName);
        }
        $produto->save();
        return redirect()->route('products.index');
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
            $data['title'] = $product->title;
            $data['category_id'] = $product->category_id;
            $data['price'] = $product->price;
            $data['currency_id'] = $product->currency_id;
            $data['available_quantity'] = $product->available_quantity;
            $data['buying_mode'] = $product->buying_mode;
            $data['listing_type_id'] = $product->listing_type_id;
            $data['condition'] = $product->condition;
            $data['description'] = $product->description;
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
        Log::debug(json_encode($request->all()));
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:60',
            'tipo_anuncio' => 'required',
            'price' => 'required',
            //'id_categoria' => 'required|max:10'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $name = $request->name;
        $tipo_anuncio = $request->tipo_anuncio;
        $price = $request->price;
        $id_categoria = $request->id_categoria;
        $id_product = 156;

        $factory = new ProdutoImplementacao($name, $tipo_anuncio, $price, $id_categoria, $id_product, Auth::user()->id);
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


    public function todosProdutos()
    {
        // PRODUTOS EM PROMOÇÂO
        $data = Products::where('fornecedor_id', Auth::user()->id)->paginate(10);

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

        Log::info(json_encode($request->all()));
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
}
