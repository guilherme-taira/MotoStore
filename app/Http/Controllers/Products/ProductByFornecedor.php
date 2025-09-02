<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\ManipuladorProdutosIntegrados;
use App\Http\Controllers\MercadoLivreStockController;
use App\Http\Controllers\TikTokProductController;
use App\Models\categorias;
use App\Models\categorias_forncedores;
use App\Models\logo;
use App\Models\Products;
use App\Models\produtos_integrados;
use App\Models\SellerAccount;
use App\Models\sub_categoria_fornecedor;
use App\Models\sub_category;
use App\Models\TikTokProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductByFornecedor extends Controller
{
    public function getProductsByFornecedor(Request $request,$id){

        $title = sub_categoria_fornecedor::where('id',$id)->first();
        $viewData = [];
        $viewData['title'] = "Produtos de Fornecedores";
        $viewData['subtitle'] = "Fornecedores : " .  $title->name;
        $viewData['products'] =  User::getProductByFornecedor($id);
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

        if(Auth::user()->user_subcategory == $id){
        $viewData['bloqueado'] = 1;
       }else{
        $viewData['bloqueado'] = 0;
       }

       return view('store.index')->with('viewData', $viewData);

    }


public function update(Request $request, $marketplace)
{
    try {
        // Validação dos dados
        $validated = $request->validate([
            'id' => 'required',
            'valor_tipo' => 'nullable|in:acrescimo_reais,acrescimo_porcentagem,desconto_reais,desconto_porcentagem',
            'isPorcem' => 'required',
            'valor_agregado' => 'numeric',
            'precoFixo' => 'nullable',
            'active' => 'nullable',
            'estoque_minimo' => 'nullable'
        ]);

             // Lógica específica para cada marketplace
        switch ($marketplace) {
            case 'mercadolivre':
                // Encontra o produto pelo ID
                $product = produtos_integrados::findOrFail($validated['id']);
                break;
            case 'tiktok':
                // Encontra o produto pelo ID
                $product = TikTokProduct::findOrFail($validated['id']);
                break;
            default:
                // Caso o marketplace não seja suportado
                return redirect()->back()->with('error', 'Marketplace não suportado.');
        }


        $product->isPorcem = $validated['isPorcem'];

        if ($request->filled('valor_tipo')) {
            // Atualiza os campos conforme a regra de negócio
            // ... (A sua lógica de atualização de valores agregados) ...
            if ($validated['valor_tipo'] == 'acrescimo_reais') {
                $product->acrescimo_reais = $validated['valor_agregado'];
                $product->acrescimo_porcentagem = null;
                $product->desconto_reais = null;
                $product->desconto_porcentagem = null;
            } elseif ($validated['valor_tipo'] == 'acrescimo_porcentagem') {
                $product->acrescimo_reais = null;
                $product->acrescimo_porcentagem = $validated['valor_agregado'];
                $product->desconto_reais = null;
                $product->desconto_porcentagem = null;
            } elseif ($validated['valor_tipo'] == 'desconto_reais') {
                $product->acrescimo_reais = null;
                $product->acrescimo_porcentagem = null;
                $product->desconto_reais = $validated['valor_agregado'];
                $product->desconto_porcentagem = null;
            } elseif ($validated['valor_tipo'] == 'desconto_porcentagem') {
                $product->acrescimo_reais = null;
                $product->acrescimo_porcentagem = null;
                $product->desconto_reais = null;
                $product->desconto_porcentagem = $validated['valor_agregado'];
            }
        }

        if ($request->filled('precoFixo')) {
            $product->precofixo = $request->precoFixo;
            $product->acrescimo_reais = null;
            $product->acrescimo_porcentagem = null;
            $product->desconto_reais = null;
            $product->desconto_porcentagem = null;
        }

        $product->active = $validated['active'];
        $product->estoque_minimo = $validated['estoque_minimo'];

        $product->save();
        $response = [];
        // Lógica específica para cada marketplace
        switch ($marketplace) {
            case 'mercadolivre':
                $response = [];
                // Lógica de atualização de estoque e preço para o Mercado Livre
                $dadosDoProdutoOriginal = Products::where('id', $product->product_id)->first();
                $estoqueNew = new MercadoLivreStockController($product->id_mercadolivre, $dadosDoProdutoOriginal->estoque_afiliado, $validated['active'], $validated['estoque_minimo'], $product->user_id, $dadosDoProdutoOriginal->estoque_minimo_afiliado);
                $estoqueNew->updateStock();

                $precoNew = new ManipuladorProdutosIntegrados($validated['id'], 0);
                $response[] = $precoNew->atualizarOnlyProduct();
                return response()->json($response);
                break;
                case 'tiktok':
                // CÓDIGO CORRIGIDO
                $seller = SellerAccount::where('user_id', Auth::user()->id)->first();
                // Lógica de atualização de estoque e preço para o TikTok
                $dadosDoProdutoOriginal = Products::where('id', $product->local_product_id)->first();
                // A classe e os métodos abaixo podem precisar ser ajustados para a API do TikTok
                $TikTokProductController = new TikTokProductController();
                if($validated['active'] == 0){
                    $response[] = $TikTokProductController->updateInventory($product->tiktok_product_id,$product->tiktok_sku,0,$seller,'7528080962442594054');
                }else{
                   $response[] = $TikTokProductController->updateInventory($product->tiktok_product_id,$product->tiktok_sku,$dadosDoProdutoOriginal->estoque_afiliado,$seller,'7528080962442594054');
                }

                 $response[] = $TikTokProductController->updateOriginalPrice($product->tiktok_product_id,$product->tiktok_sku,$seller);
                 return response()->json($response);
                break;
            default:
                // Caso o marketplace não seja suportado
                return redirect()->back()->with('error', 'Marketplace não suportado.');
        }

        return redirect()->back()->with('msg', 'Produto atualizado com sucesso!');
    } catch (\Exception $e) {
        Log::alert($e->getMessage());
        return redirect()->back()->with('error', 'Erro ao atualizar o produto: ' . $e->getMessage());
    }
}
}
