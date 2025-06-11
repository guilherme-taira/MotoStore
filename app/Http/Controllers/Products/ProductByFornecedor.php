<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\ManipuladorProdutosIntegrados;
use App\Http\Controllers\MercadoLivreStockController;
use App\Models\categorias;
use App\Models\categorias_forncedores;
use App\Models\logo;
use App\Models\Products;
use App\Models\produtos_integrados;
use App\Models\sub_categoria_fornecedor;
use App\Models\sub_category;
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


    public function update(Request $request)
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

            // // Encontra o produto pelo ID
            $product = produtos_integrados::findOrFail($validated['id']);
            $product->isPorcem = $validated['isPorcem'];

            if($request->filled('valor_tipo')){
                // Atualiza os campos conforme a regra de negócio
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

                $dadosDoProdutoOriginal = Products::where('id',$product->product_id)->first();
                $estoqueNew = new MercadoLivreStockController($product->id_mercadolivre,$dadosDoProdutoOriginal->estoque_afiliado,$validated['active'],$validated['estoque_minimo'],$product->user_id,$dadosDoProdutoOriginal->estoque_minimo_afiliado);
                $estoqueNew->updateStock();

                $precoNew = new ManipuladorProdutosIntegrados($validated['id'],0);
                $precoNew->atualizarOnlyProduct();



            return redirect()->back()->with('msg', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::alert($e->getMessage());
            return redirect()->back()->with('error', 'Erro ao atualizar o produto: ' . $e->getMessage());
        }
    }


}
