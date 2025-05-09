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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // Obtém apenas a chave `produtos` da sessão, ou um array vazio se não houver produtos
        $produtos = $request->session()->get('produtos', []);

        $viewData = [];
        $viewData['title'] = "Kits de Produtos";
        $viewData['subtitle'] = "Kits";


        $categorias = [];
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
                $request->session()->put($request->id, ['id' => $request->id, 'nome' => $request->name, 'imagem' => $this->getImageByUrl($request->id), 'price' => $this->getPriceKit($request->id), 'estoque' => '1', 'priceKit' => $this->getPriceKit($request->id), 'fee' => $this->getFeeProduto($request->id)]);
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

    public function getTitle(int $id)
    {
        $data = Products::where('id', $id)->first();
        return $data['title'];
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

    public function getPriceKit(int $id)
    {
        $data = Products::where('id', $id)->first();
        return $data['priceKit'];
    }

    public function getFeeProduto(int $id)
    {
        $data = Products::where('id', $id)->first();
        return $data['fee'];
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

    public function updateQuantidadeNoKit(Request $request, $productId, $kitId){

    $novaQuantidade = $request->input('stock');

    // Localiza o registro certo na tabela `kit`
    $kit = Kit::where('product_id', $productId)
              ->where('id_product_kit', $kitId)
              ->first();

    if (!$kit) {
        return redirect()->back()->with('error', 'Kit não encontrado.');
    }

    // Atualiza a coluna available_quantity
    $kit->available_quantity = $novaQuantidade;
    $kit->save();

    return redirect()->back()->with('success', 'Quantidade atualizada com sucesso!');
    }

    public function adicionarQuantidadeNoKit(Request $request) {

        $request->session()->forget(['_flash', '_token', '_previous', 'auth']);
        $msg = "";

        try {
            // Verifica o estoque disponível
            $availableStock = $this->getStock($request->id);

            // Verifica se a quantidade solicitada é maior do que o estoque disponível
            if ($availableStock < $request->stock) {
                $msg = "Quantidade não adicionada por falta de estoque!";
                $quantidadeAdicionada = $availableStock;
            } else {
                $quantidadeAdicionada = $request->stock;
            }

            // Obtém os produtos já armazenados na sessão (ou um array vazio)
            $produtos = $request->session()->get('produtos', []);

            // Verifica se o produto já está na sessão
            if (isset($produtos[$request->id])) {
                // Produto já existe, atualiza a quantidade
                $produto = $produtos[$request->id];
                $novaQuantidade = $quantidadeAdicionada;

                // Verifica se a nova quantidade não excede o estoque disponível
                if ($novaQuantidade > $availableStock) {
                    $msg = "Quantidade ajustada ao máximo disponível no estoque!";
                    $novaQuantidade = $availableStock; // Ajusta para o estoque máximo
                }

                // Atualiza o produto com a nova quantidade e o preço total
                $produtos[$request->id] = [
                    'id' => $request->id,
                    'nome' => $produto['nome'],
                    'imagem' => $produto['imagem'],
                    'price' => $this->getPriceKit($request->id) * $novaQuantidade, // Preço total
                    'quantidade' => $novaQuantidade,
                    'available_quantity' => $availableStock,
                    'fee' => $this->getFeeProduto($request->id)
                ];
            } else {
                // Produto não existe na sessão, então adiciona como um novo item
                $produtos[$request->id] = [
                    'id' => $request->id,
                    'nome' => $this->getTitle($request->id),
                    'imagem' => $this->getImageByUrl($request->id),
                    'price' => $this->getPriceKit($request->id) * $quantidadeAdicionada, // Preço total
                    'quantidade' => $quantidadeAdicionada,
                    'available_quantity' => $availableStock,
                    'fee' => $this->getFeeProduto($request->id)
                ];
            }

            Log::alert(json_encode($produtos));
            // Salva os produtos atualizados na sessão
            $request->session()->put('produtos', $produtos);

            // Recupera os dados da sessão para exibir na view
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

            // Redireciona para a rota 'kits.create' sem parâmetros na URL
            return redirect()->route('kits.create')->with([
                'viewData' => $viewData,
                'produtos' => $sessao['produtos'],
                'total' => 0,
                'msg' => $msg,
            ]);

        } catch (\Exception $e) {
            Log::alert($e->getMessage());
        }
    }


    public function adicionarQuantidade(Request $request)
    {

        try {
            $products = $request->input('products'); // Recebe o array de produtos enviados
            Log::info(json_encode($products));

            if (!$products || !is_array($products)) {
                return response()->json(['error' => 'Nenhum produto foi enviado.'], 400);
            }

            $produtosSessao = $request->session()->get('produtos', []); // Produtos existentes na sessão
            $produtosInvalidos = []; // Para armazenar produtos com fornecedores diferentes
            $produtosValidos = []; // Produtos válidos enviados na requisição

            // Obtém todos os fornecedores únicos dos produtos na sessão
            $fornecedoresSessao = array_unique(array_map(function ($produto) {
                return $produto['fornecedor'] ?? null;
            }, $produtosSessao));

            foreach ($products as $product) {
                $id = $product['id'];
                $stock = $product['stock'];
                $fornecedorAtual = $product['fornecedor'];

                // Verifica se o fornecedor atual está na lista de fornecedores da sessão
                if (!empty($fornecedoresSessao) && !in_array($fornecedorAtual, $fornecedoresSessao)) {
                    $produtosInvalidos[] = $product['nome'] ?? "Produto ID $id";
                    continue; // Ignora o produto
                }

                // Verifica o estoque disponível
                $availableStock = $this->getStock($id);
                $quantidadeAdicionada = min($stock, $availableStock); // Adiciona o máximo permitido pelo estoque

                // Adiciona ou atualiza o produto na sessão
                if (isset($produtosSessao[$id])) {
                    // Atualiza a quantidade do produto existente
                    $produtosSessao[$id]['quantidade'] = $quantidadeAdicionada;
                    $produtosSessao[$id]['price'] = $this->getPriceKit($id) * $quantidadeAdicionada;
                } else {
                    // Adiciona o novo produto
                    $produtosSessao[$id] = [
                        'id' => $id,
                        'nome' => $product['nome'],
                        'imagem' => $this->getImageByUrl($id),
                        'price' => $this->getPriceKit($id) * $quantidadeAdicionada,
                        'quantidade' => $quantidadeAdicionada,
                        'available_quantity' => $availableStock,
                        'fornecedor' => $fornecedorAtual,
                        'fee' => $this->getFeeProduto($id),
                    ];
                }

                // Adiciona o produto aos válidos
                $produtosValidos[] = $product['nome'] ?? "Produto ID $id";
            }

            // Atualiza a sessão com os produtos válidos
            $request->session()->put('produtos', $produtosSessao);

            // Exemplo para mensagens de sucesso
            if (!empty($produtosValidos)) {
                session()->flash('success', 'Produtos válidos adicionados com sucesso!');
            }


            // Exemplo para mensagens de erro
            if (!empty($produtosInvalidos)) {

                session()->flash('error', 'Os seguintes produtos foram removidos: ' . implode(', ', $produtosInvalidos));
            }

            // Redireciona de volta para a página
            return redirect()->back();

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao adicionar os produtos.');
        }



}



public function DeleteOrderSessionRoute(Request $request, $id)
{
    $msg = "";

    try {
        // Obtém todos os produtos armazenados na sessão
        $produtos = $request->session()->get('produtos', []);

        // Verifica se o produto com o ID especificado existe na sessão
        if (isset($produtos[$id])) {
            // Remove o produto específico
            unset($produtos[$id]);

            // Atualiza a sessão com os produtos restantes
            $request->session()->put('produtos', $produtos);
            $msg = "Produto retirado do kit com sucesso!";
        } else {
            $msg = "Produto não encontrado no kit.";
        }

        // Recupera a sessão atualizada
        $sessao = $request->session()->all();

        $viewData = [];
        $viewData['title'] = "Kits de Produtos";
        $viewData['subtitle'] = "Kits";

        $categorias = [];
        foreach (categorias::all() as $value) {
            $categorias[$value->id] = [
                "nome" => $value->nome,
                "subcategory" => sub_category::getAllCategory($value->id),
            ];
        }

        $viewData['categorias'] = $categorias;

        // Redireciona para a rota 'kits.create' sem parâmetros na URL
        return redirect()->route('kits.create')->with([
            'viewData' => $viewData,
            'produtos' => $sessao['produtos'] ?? [],
            'total' => 0,
            'msg' => $msg,
        ]);

    } catch (\Exception $e) {
        // Captura qualquer erro e exibe a mensagem
        Log::error("Erro ao remover produto da sessão: " . $e->getMessage());
        return redirect()->route('kits.create')->with('msg', 'Erro ao remover o produto do kit.');
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

        $input = $request->all();

        // Converter o valor de precoFinal para o formato numérico adequado (ponto como separador decimal)
        $input['precoFinal'] = str_replace(',', '.', str_replace('.', '', $input['precoFinal']));

        $request->merge($input);

       // Validação do formulário
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    'min:5',
                ],
                'precoFinal' => [
                    'required',
                    'numeric',
                    'min:1',
                ],
                'photos.*' => [
                    'required',
                    'file',
                    'mimes:jpg,jpeg,png',
                ],
                'description' => [
                    'required',
                ],
            ],
            [
                // Mensagens de erro personalizadas
                'name.required' => 'O campo Nome é obrigatório.',
                'name.min' => 'O Nome deve ter no mínimo 5 caracteres.',
                'precoFinal.required' => 'O campo Preço Final é obrigatório.',
                'precoFinal.numeric' => 'O campo Preço Final deve ser um número.',
                'precoFinal.min' => 'O campo Preço Final deve ser maior que zero.',
                'photos.required' => 'É necessário fazer upload de uma imagem.',
                'photos.file' => 'O campo Fotos deve ser um arquivo.',
                'photos.mimes' => 'As imagens devem estar no formato JPG ou PNG.',
                'description.required' => 'O campo Descrição é obrigatório.',
            ]
        );

        // Verifica se a validação falhou
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator) // Adiciona os erros de validação
                ->withInput(); // Mantém os valores preenchidos no formulário
        }


    function calculaKitsPossiveis($produtos) {
        $kitsPossiveis = PHP_INT_MAX; // Inicializa com um valor alto para encontrar o mínimo
        $valorKitWithFee = 0;
        $fee = 0;
        foreach ($produtos as $produto) {
            if ($produto['quantidade'] > 0) {
                $valorKitWithFee += $produto['price'] + ($produto['fee'] * $produto['quantidade']);
                $fee += ($produto['fee'] * $produto['quantidade']);
                // Calcula quantos kits podem ser feitos com base no estoque e na quantidade necessária de cada produto
                $kitsProduto = floor($produto['available_quantity'] / $produto['quantidade']);
                $kitsPossiveis = min($kitsPossiveis, $kitsProduto); // Mantém o menor valor encontrado
            }
        }

        return [
            'kitsPossiveis' => $kitsPossiveis,
            'valorWithFee' => number_format(($valorKitWithFee / 0.95),2),
            'fee' => $fee
        ];
    }

    // Exemplo de uso
    $kitsPossiveis = calculaKitsPossiveis(array_values($products['produtos']));

    if($kitsPossiveis['kitsPossiveis'] > 0){
     $produto = new Products();
        $produto->title = $request->name;
        $produto->price = str_replace(',', '.',$request['precoFinal']);
        $produto->description = $request->description;
        // CATEGORIA REMOVIDA
        $produto->category_id = $request->id_categoria;
        $produto->subcategoria = $request->categoria;
        $produto->fornecedor_id = $this->getUniqueFornecedorId(array_values($products['produtos']));
        $produto->iskit = 1;
        $produto->isPublic = 0;
        $produto->owner = $request->owner;
        $produto->fee = $kitsPossiveis['fee'];
        $produto->priceWithFee = $kitsPossiveis['valorWithFee'];
        $produto->available_quantity = $kitsPossiveis['kitsPossiveis'];
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


        $files = $request->file('photos');

        // Verifica se a ordem foi alterada
        $imageOrder = json_decode($request->imageOrder, true);
        $orderedFiles = [];

        if (!empty($imageOrder)) {
            foreach ($imageOrder as $orderedImage) {
                foreach ($files as $file) {
                    if ($file->getClientOriginalName() === $orderedImage['originalName']) {
                        $orderedFiles[] = $file;
                    }
                }
            }
        } else {
            $orderedFiles = $files; // Usa a ordem original se `imageOrder` estiver vazio
        }

        // Salva as imagens na ordem definida
        $i = 0;
        foreach ($orderedFiles as $file) {
            $filename = $file->getClientOriginalName();
            $file->storeAs('produtos/' . $produto->getId(), $filename, 's3');
            if ($i == 0) {
                $produto->setImage($filename); // Define a imagem principal
            }
            $image = new images();
            $image->url = $filename;
            $image->product_id = $produto->getId();
            $image->save();
            $i++;
        }

        // //CADASTRA PRODUTO DO KIT
        foreach (array_values($products['produtos']) as $key => $product) {

            $id = isset($product['id']) ? $product['id'] : 0;
            if ($id != 0) {
                $produtoKit = new kit();
                $produtoKit->product_id = $produto->getId();
                $produtoKit->id_product_kit = isset($product['id']) ? $product['id'] : 0;
                $produtoKit->available_quantity = isset($product['quantidade']) ? $product['quantidade'] : 0;
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

        return redirect()->route('products.edit', ['id' => $produto->getId()])
        ->with('success', 'Kit cadastrado com sucesso!');


    }else{
          // Redireciona de volta com uma mensagem de erro
        return redirect()->back()->withErrors(['error' => 'Não é possível montar um kit com a quantidade atual de produtos.']);
    }


    }

    /**
     * Retorna o fornecedor_id único se todos os produtos tiverem o mesmo,
     * ou null se houver divergência.
     *
     * @param array $produtos Array de produtos, onde cada produto é um array associativo com a chave 'fornecedor_id'
     * @return mixed|null O fornecedor_id único ou null se houver divergência
     */
    function getUniqueFornecedorId(array $produtos):int
    {
            // Se o array estiver vazio, retorna 1 (ou outro valor padrão)
        if (empty($produtos)) {
            return 1;
        }
        // Cria um array com os IDs dos produtos
        $ids = array_map(function($produto) {
            return $produto['id'];
        }, $produtos);
        // Consulta os produtos no banco utilizando o model Products.
        $products = Products::whereIn('id', $ids)->get();

        // Obtém os fornecedor_id únicos
        $uniqueFornecedorIds = $products->pluck('fornecedor_id')->unique();

        // Se houver exatamente um fornecedor_id, retorna-o, caso contrário, retorna null
        return $uniqueFornecedorIds->count() === 1 ? $uniqueFornecedorIds->first() : 1;

    }


    public function deleteProduct(Request $request, $productId, $kitId)
    {
        try {
            // Remove o registro do kit correspondente ao productId e kitId
            $deleted = DB::table('kit')
                ->where('product_id', $productId)
                ->where('id_product_kit', $kitId)
                ->delete();

            if ($deleted) {
                // Se desejar, adicione o kit removido a um array para enviar na resposta
                return redirect()->back()->with([
                    'message' => 'Produto removido do kit com sucesso.',
                ]);
            } else {
                return redirect()->back()->with([
                    'error' => 'Erro ao remover o produto do kit.',
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => 'Erro: ' . $e->getMessage(),
            ]);
        }
    }




}
