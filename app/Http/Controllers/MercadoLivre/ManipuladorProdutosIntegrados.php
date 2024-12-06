<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;

use App\Models\produtos_integrados;
use App\Models\token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManipuladorProdutosIntegrados extends Controller
{
    protected $productId;
    protected $price;
      /**
     * Construtor que recebe o ID do produto.
     *
     * @param int $productId
     */
    public function __construct(int $productId, $price)
    {
        $this->productId = $productId;
        $this->price = $price;
    }

    /**
     * Método para manipular os produtos integrados relacionados ao produto.
     */
    public function manipular()
    {
        // Buscar os produtos integrados com o product_id igual ao ID fornecido
        $produtosIntegrados = produtos_integrados::where('product_id', $this->productId)->get();

        if ($produtosIntegrados->isEmpty()) {
            Log::warning("Nenhum produto integrado encontrado para product_id: {$this->productId}");
            return ['success' => false, 'message' => 'Nenhum produto integrado encontrado.'];
        }

        foreach ($produtosIntegrados as $produtoIntegrado) {
            try {
                $token = token::where('user_id','=',$produtoIntegrado->user_id)->first();
                // Aqui chamamos a classe responsável pela atualização do produto
                \App\Jobs\UpdateMercadoLivrePrice::dispatch($produtoIntegrado->id_mercadolivre,$this->price,$token->access_token);

                Log::info("Produto integrado atualizado com sucesso: ID {$produtoIntegrado->id}");
            } catch (\Exception $e) {
                Log::error("Erro ao atualizar produto integrado ID {$produtoIntegrado->id}: " . $e->getMessage());
            }
        }

        return ['success' => true, 'message' => 'Manipulação de produtos concluída.'];
    }

    /**
     * Atualizar os dados do produto integrado.
     *
     * @param ProdutosIntegrados $produtoIntegrado
     */
    protected function atualizarProdutoIntegrado(produtos_integrados $produtoIntegrado)
    {
        // Simulação de lógica para atualizar o produto integrado
        // Substitua isso por sua lógica real
        $produtoIntegrado->id_mercadolivre = 'MLB-' . str_pad($produtoIntegrado->id, 8, '0', STR_PAD_LEFT);
        $produtoIntegrado->save();
    }
}
