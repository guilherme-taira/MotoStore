<?php

namespace App\Http\Controllers\Kits;

use App\Http\Controllers\Controller;
use App\Models\categorias;
use App\Models\images;
use App\Models\kit;
use App\Models\Products;
use App\Models\sub_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class kitsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kits = Products::getKitByUser(Auth::user()->id);
        $viewData = [];
        $viewData['title'] = "Kits de Produtos";
        $viewData['subtitle'] = "Kits";
        $viewData['kits'] = $kits;

        return view('kits.index')->with([
            'viewData' =>  $viewData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->forget(['_flash', '_token', '_previous', 'auth']);
        $produtos = $request->session()->all();

        $viewData = [];
        $viewData['title'] = "Kits de Produtos";
        $viewData['subtitle'] = "Kits";

        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $viewData['categorias'] = $categorias;

        return view('kits.create')->with([
            'viewData' =>  $viewData,
            'produtos' => $produtos,
            'total' => 0,
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
        echo "<pre>";
        print_r($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function setSessionRoute(Request $request)
    {
        $request->session()->forget(['_flash', '_token', '_previous', 'auth']);

        try {
            $data = $request->session()->all();
            $codigoCliente = $request->input('id');

            $i = 0;
            $chaves = [];

            foreach ($data as $value) {
                $chaves[$i] = isset($value['id']) ? $value['id'] : 0;
                $i++;
            }

            if (!in_array($codigoCliente, $chaves)) {
                $request->session()->put($request->id, ['id' => $request->id, 'nome' => $request->name, 'imagem' => $this->getImageByUrl($request->id), 'price' => $this->getPrice($request->id), 'estoque' => '1']);
            } else {
                $request->session()->forget($codigoCliente);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $sessao = $request->session()->all();

        $viewData = [];
        $viewData['title'] = "Kits de Produtos";
        $viewData['subtitle'] = "Kits";

        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $viewData['categorias'] = $categorias;

        return view('kits.create', [
            'viewData' => $viewData,
            'produtos' => $sessao,
            'total' => 0,
        ]);
    }

    public function getImageByUrl(int $id)
    {
        $data = Products::where('id', $id)->first();
        return $data['image'];
    }


    public function getStock(int $id)
    {
        $data = Products::where('id', $id)->first();
        return $data['available_quantity'];
    }



    public function getPrice(int $id)
    {
        $data = Products::where('id', $id)->first();

        if ($data['pricePromotion']) {
            return $data['pricePromotion'];
        }
        return $data['price'];
    }

    public function StoreRota(Request $request)
    {
        $request->session()->forget(['_flash', '_token', '_previous']);

        $datas = $request->session()->all();
        $id = uniqid('EMBALEME');

        //$lastRemessa = table_rotas::getMaxRemessaID();

        foreach ($datas as $data) {
            // $StoreRota = new table_rotas();
            // $StoreRota->id_rota = $id;
            // $StoreRota->cliente_id = $data['codigo'];
            // $StoreRota->id_motorista = $request->entregador;
            // $StoreRota->remessa = isset($lastRemessa->remessa) ? $lastRemessa->remessa + 1 : 1;
            // $StoreRota->save();
        }

        $datas = $request->session()->flush();

        // $entregadores = entregador::all();
        // return view('view.rotas', [
        //     'pedidos' => $datas,
        //     'entregadores' => $entregadores,
        // ]);
    }

    public function adicionarQuantidade(Request $request)
    {
        $request->session()->forget(['_flash', '_token', '_previous', 'auth']);
        $msg = "";

        try {
            $data = $request->session()->all();
            $codigoCliente = $request->id;

            $i = 0;
            $chaves = [];
            try {
                foreach ($data as $key => $value) {
                    if (is_numeric($key)) {
                        $chaves[$i] = $value['id'];
                        $i++;
                    }
                }

                if (in_array($codigoCliente, $chaves)) {
                    foreach ($data as $key => $value) {
                        if (is_numeric($key)) {
                            if ($data[$key]['id'] == $codigoCliente) {
                                $request->session()->forget($key);
                            }
                        }
                    }

                    if ($this->getStock($request->id) < $request->stock) {
                        $msg = "Quantidade não adicionada por falta de estoque!";
                        $request->session()->put($request->id + 1, ['id' => $request->id, 'nome' => "", 'imagem' => $this->getImageByUrl($request->id), 'price' => ($this->getPrice($request->id) * $request->stock), 'quantidade' => $this->getStock($request->id)]);
                    } else {
                        $request->session()->put($request->id + 1, ['id' => $request->id, 'nome' => "", 'imagem' => $this->getImageByUrl($request->id), 'price' => ($this->getPrice($request->id) * $request->stock), 'quantidade' => $request->stock]);
                    }
                }
            } catch (\Exception $e) {
                //e->getMessage();
            }

            $sessao = $request->session()->all();

            $viewData = [];
            $categorias = [];
            $viewData['title'] = "Kits de Produtos";
            $viewData['subtitle'] = "Kits";

            foreach (categorias::all() as $value) {
                $categorias[$value->id] = [
                    "nome" => $value->nome,
                    "subcategory" => sub_category::getAllCategory($value->id),
                ];
            }

            $viewData['categorias'] = $categorias;

            return view('kits.create', [
                'viewData' => $viewData,
                'produtos' => $sessao,
                'total' => 0,
                'msg' => $msg,
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function DeleteOrderSessionRoute(Request $request)
    {
        $request->session()->forget(['_flash', '_token', '_previous', 'auth', 'url']);

        $msg = "";

        try {
            $data = $request->session()->all();
            $codigoCliente = $request->id;

            $i = 0;
            $chaves = [];

            try {
                foreach ($data as $key => $value) {
                    if (is_numeric($key)) {
                        $chaves[$i] = $value['id'];
                        $i++;
                    }
                }
            } catch (\Exception $e) {
                //e
            }

            if (in_array($codigoCliente, $chaves)) {
                $dados = array_keys($chaves, $codigoCliente);
                foreach ($dados as $pos) {
                    $request->session()->forget($pos);
                    $msg = "Produto retirado do kit com sucesso!";
                }
            }

            $sessao = $request->session()->all();

            $viewData = [];
            $viewData['title'] = "Kits de Produtos";
            $viewData['subtitle'] = "Kits";

            foreach (categorias::all() as $value) {
                $categorias[$value->id] = [
                    "nome" => $value->nome,
                    "subcategory" => sub_category::getAllCategory($value->id),
                ];
            }

            $viewData['categorias'] = $categorias;

            return view('kits.create', [
                'viewData' => $viewData,
                'produtos' => $sessao,
                'total' => 0,
                'msg' => $msg,
            ]);
        } catch (\Exception $e) {

            echo $e->getMessage();
        }
    }

    public function getProductByName(Request $request)
    {
        $datasDB = json_encode(Products::where('id', 'like', '%' . $request->id . '%')->get());
        if ($datasDB) {
            return response()->json(["dados" => $datasDB], 200);
        }
        return response()->json(["dados" => "Não há produtos com esse ID"]);
    }

    // add kit
    public function addKit(Request $request)
    {
        $request->session()->forget(['_flash', '_token', '_previous', 'auth']);

        $products = session()->all();

        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|min:5',
        //     'price' => 'required|numeric|min:1',
        //     // 'photos' => 'required|file',
        //     //"stock" => "required|numeric",
        //     'description' => 'required'
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        $produto = new Products();
        $produto->title = $request->name;
        $produto->price = $request->price;
        $produto->description = $request->description;
        // CATEGORIA REMOVIDA
        $produto->category_id = $request->id_categoria;
        $produto->subcategoria = $request->categoria;
        $produto->iskit = 1;
        $produto->image = "image.png";
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

        //CADASTRA PRODUTO DO KIT
        foreach ($products as $product) {
            $id = isset($product['id']) ? $product['id'] : 0;
            if ($id != 0) {
                $produtoKit = new kit();
                $produtoKit->product_id = $produto->getId();
                $produtoKit->id_product_kit = isset($product['id']) ? $product['id'] : 0;
                $produtoKit->available_quantity = isset($product['estoque']) ? $product['estoque'] : 0;
                $produtoKit->acrescimo = 0;
                $produtoKit->desconto = 0;
                $produtoKit->user_id = Auth::user()->id;
                $produtoKit->save();
            }
        }

        $produto->save();

        $viewData = [];
        $viewData['title'] = "Kits de Produtos";
        $viewData['subtitle'] = "Kits";
        $viewData['categorias'] = categorias::all();
        $viewData['kits'] = kit::getAllKits(Auth::user()->id);

        return redirect()->route(
            'kits.index',
            [
                'viewData' => $viewData,
                'total' => 0,
            ]
        );
    }

    public function VerificaQuantidade($id){

    }
}
