<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateStockJob;
use App\Jobs\UpdateStockJobTiktok;
use App\Models\Products;
use App\Models\produtos_integrados;
use App\Models\SalesReport;
use App\Models\TikTokProduct;
use App\Models\User;
use App\Notifications\StockMinimumReached;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesReportController extends Controller
{
      /**
     * Processa uma venda, atualiza o estoque e registra no relatório de vendas.
     */
    public function processSale($dados)
    {
        // TESTA SE FOR KIT
         $kit = DB::table('kit')
        ->where('product_id', $dados['product_id'])
        ->first();

        if ($kit) {
            $product = Products::findOrFail($kit->id_product_kit);
        } else {
            $product = Products::findOrFail($dados['product_id']);
        }

        $integratedProduct = produtos_integrados::where('id_mercadolivre',$dados['integrated_product_id'])->first();

        // Quantidade anterior
        $quantityBeforeProduct = $product->available_quantity;

        // Atualiza o estoque do produto
        $product->available_quantity -= $dados['quantity_sold'];
        $product->save();


        // Recalcula o estoque do afiliado baseado no percentual configurado no banco
        $percentualEstoque = $product->percentual_estoque; // Certifique-se de que este campo exista na tabela products
        $estoqueAfiliado = floor(($product->available_quantity * $percentualEstoque) / 100);

        // Atualiza o estoque do afiliado no banco
        $product->estoque_afiliado = $estoqueAfiliado;
        $product->save();

        // Verifica se o estoque do afiliado atingiu o limite mínimo
        if ($estoqueAfiliado <= $product->estoque_minimo_afiliado) {
        // Envia a notificação para o usuário
        $users = $product->fornecedor_id; // Ajuste conforme a relação de usuários e produtos
        $user = User::find($users);

            // Verifica o campo `acao`
        if (is_null($product->acao)) {
            // Notifica o usuário caso `acao` seja null
            if ($user) {
                $user->notify(new StockMinimumReached($product, $user));
            }
        } elseif ($product->acao === 'pausar') {
            // Pausa todos os anúncios relacionados
            $this->pausarAnuncios($dados['product_id']);
        }
        }

       try {
         UpdateStockJob::dispatch($product->id,$product->estoque_afiliado,$product->estoque_minimo_afiliado);
       } catch (\Exception $th) {
            Log::alert($th->getMessage());
       }

        // Grava o relatório de vendas
        SalesReport::create([
            'order_site_id' => $dados['order_site_id'],
            'product_id' => $dados['product_id'],
            'integrated_product_type' => 'produtos_integrados',
            'integrated_product_id' => $integratedProduct->id,
            'quantity_sold' => $dados['quantity_sold'],
            'quantity_before' => $quantityBeforeProduct,
            'quantity_after' => $product->available_quantity,
        ]);
 }


     public function processSaleTikTok($dados) {
        // TESTA SE FOR KIT
         $kit = DB::table('kit')
        ->where('product_id', $dados['product_id'])
        ->first();

        if ($kit) {
            $product = Products::findOrFail($kit->id_product_kit);
        } else {
            $product = Products::findOrFail($dados['product_id']);
        }

        $integratedProduct = TikTokProduct::where('local_product_id',$dados['integrated_product_id'])->first();

        // Quantidade anterior
        $quantityBeforeProduct = $product->available_quantity;

        // Atualiza o estoque do produto
        $product->available_quantity -= $dados['quantity_sold'];
        $product->save();


        // Recalcula o estoque do afiliado baseado no percentual configurado no banco
        $percentualEstoque = $product->percentual_estoque; // Certifique-se de que este campo exista na tabela products
        $estoqueAfiliado = floor(($product->available_quantity * $percentualEstoque) / 100);

        // Atualiza o estoque do afiliado no banco
        $product->estoque_afiliado = $estoqueAfiliado;
        $product->save();

        // Verifica se o estoque do afiliado atingiu o limite mínimo
        if ($estoqueAfiliado <= $product->estoque_minimo_afiliado) {
        // Envia a notificação para o usuário
        $users = $product->fornecedor_id; // Ajuste conforme a relação de usuários e produtos
        $user = User::find($users);

            // Verifica o campo `acao`
        if (is_null($product->acao)) {
            // Notifica o usuário caso `acao` seja null
            if ($user) {
                $user->notify(new StockMinimumReached($product, $user));
            }
        } elseif ($product->acao === 'pausar') {
            // Pausa todos os anúncios relacionados
            $this->pausarAnuncios($dados['product_id']);
        }
        }

       try {
         UpdateStockJobTiktok::dispatch($product->id,$product->estoque_afiliado,$product->estoque_minimo_afiliado);
       } catch (\Exception $th) {
            Log::alert($th->getMessage());
       }

        // Grava o relatório de vendas
        SalesReport::create([
            'order_site_id' => $dados['order_site_id'],
            'product_id' => $dados['product_id'],
            'integrated_product_type' => 'tiktok_products',
            'integrated_product_id' => $integratedProduct->id,
            'quantity_sold' => $dados['quantity_sold'],
            'quantity_before' => $quantityBeforeProduct,
            'quantity_after' => $product->available_quantity,
        ]);
 }

    public function pausarAnuncios($integratedProduct){

        $jobs = produtos_integrados::where('product_id',$integratedProduct)->get();

        $estoque_minimo = 0;
        foreach ($jobs as $product) {

            $estoqueNew = new MercadoLivreStockController(
                $product->id_mercadolivre,
                0,
                0,
                $estoque_minimo,
                $product->user_id
            );

            // // Atualiza o estoque
            $estoqueNew->updateStockByDirectProduct();
        }

    }
}
