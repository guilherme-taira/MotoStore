<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\produtos_integrados;
use App\Models\token;
use App\Models\Variacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function manipular() {
        // Buscar os produtos integrados com o product_id igual ao ID fornecido
        $produtosIntegrados = produtos_integrados::where('product_id', $this->productId)->get();

        if ($produtosIntegrados->isEmpty()) {
            Log::warning("Nenhum produto integrado encontrado para product_id: {$this->productId}");
            return ['success' => false, 'message' => 'Nenhum produto integrado encontrado.'];
        }

        // ATUALIZA PREÇO DAS VARIAÇÔES COM A NOVA LOGICA
        $variacoesComSku = DB::table('variacoes')
            ->join('produtos_integrados', 'variacoes.id_mercadolivre', '=', 'produtos_integrados.id_mercadolivre')
            ->where('variacoes.sku', $this->productId)
            ->select('*')
            ->get();

        if ($variacoesComSku->isEmpty()) {
            Log::error("Nenhuma variação encontrada com SKU {$this->productId}.");
            return;
        }

        foreach ($variacoesComSku as $variacao) {

            $idMercadoLivre = $variacao->id_mercadolivre;

            $variacoesRelacionadas = DB::table('variacoes')
                ->where('id_mercadolivre', $idMercadoLivre)
                ->first();

                try {
                $token = token::where('user_id','=',$variacao->user_id)->first();
                $valor = 0; // Valor final do produto
                $basePrice = $this->price; // Preço base do produto

                if ($variacao->precofixo) {
                    // Mantém o valor fixo se estiver preenchido
                    $valor = $variacao->precofixo;
                } else {
                    if ($variacao->isPorcem == true || $variacao->isPorcem == 1) {
                        // Se for porcentagem
                        if (!empty($variacao->acrescimo_porcentagem)) {
                            // Aplica acréscimo em porcentagem
                            $valor = $basePrice + ($basePrice * ($variacao->acrescimo_porcentagem / 100));
                        } elseif (!empty($variacao->desconto_porcentagem)) {
                            // Aplica desconto em porcentagem
                            $valor = $basePrice - ($basePrice * ($variacao->desconto_porcentagem / 100));
                        }
                    } else {
                        // Se não for porcentagem (valores absolutos)
                        if (!empty($variacao->acrescimo_reais)) {
                            // Aplica acréscimo em valor absoluto
                            $valor = $basePrice + $variacao->acrescimo_reais;
                        } elseif (!empty($variacao->desconto_reais)) {
                            // Aplica desconto em valor absoluto
                            $valor = $basePrice - $variacao->desconto_reais;
                        } else {
                            // Caso não tenha nenhum acréscimo ou desconto, mantém o preço base
                            $valor = $basePrice;
                        }
                    }
                }

                // Garante que o valor final não seja negativo
                $valor = max($valor, 0);

                $variacoesProds = Variacao::where('id_mercadolivre', $idMercadoLivre)->get();

                $payload = [
                    'variations' => []
                ];

                if(count($variacoesProds) > 0){

                foreach ($variacoesProds as $var) {
                if($var->sku == $variacao->sku){
                        $payload['variations'][] = [
                            'id' => (int) $var->meli_variation_id,
                            'available_quantity' => (int) $variacao->available_quantity,
                            'price' => number_format($valor,2),
                            'picture_ids' => json_decode($var->picture_ids, true),
                        ];
                }else{
                        $payload['variations'][] = [
                            'id' => (int) $var->meli_variation_id,
                            'available_quantity' => (int) $var->available_quantity,
                            'price' => number_format($valor,2),
                            'picture_ids' => json_decode($var->picture_ids, true),
                        ];
                }

                    }
                }
                // Aqui chamamos a classe responsável pela atualização do produto
                \App\Jobs\UpdateMercadoLivrePriceVariations::dispatch($variacoesRelacionadas->id_mercadolivre,$this->price,$token->access_token,$valor,$payload)->delay(now()->addSeconds(3));

            } catch (\Exception $e) {
                Log::error("Erro ao atualizar produto integrado ID {$variacao->id}: " . $e->getMessage());
            }
        }

        foreach ($produtosIntegrados as $produtoIntegrado) {
            try {
                $token = token::where('user_id','=',$produtoIntegrado->user_id)->first();
                $valor = 0; // Valor final do produto
                $basePrice = $this->price; // Preço base do produto

                if ($produtoIntegrado->precofixo) {
                    // Mantém o valor fixo se estiver preenchido
                    $valor = $produtoIntegrado->precofixo;
                } else {
                    if ($produtoIntegrado->isPorcem == true || $produtoIntegrado->isPorcem == 1) {
                        // Se for porcentagem
                        if (!empty($produtoIntegrado->acrescimo_porcentagem)) {
                            // Aplica acréscimo em porcentagem
                            $valor = $basePrice + ($basePrice * ($produtoIntegrado->acrescimo_porcentagem / 100));
                        } elseif (!empty($produtoIntegrado->desconto_porcentagem)) {
                            // Aplica desconto em porcentagem
                            $valor = $basePrice - ($basePrice * ($produtoIntegrado->desconto_porcentagem / 100));
                        }
                    } else {
                        // Se não for porcentagem (valores absolutos)
                        if (!empty($produtoIntegrado->acrescimo_reais)) {
                            // Aplica acréscimo em valor absoluto
                            $valor = $basePrice + $produtoIntegrado->acrescimo_reais;
                        } elseif (!empty($produtoIntegrado->desconto_reais)) {
                            // Aplica desconto em valor absoluto
                            $valor = $basePrice - $produtoIntegrado->desconto_reais;
                        } else {
                            // Caso não tenha nenhum acréscimo ou desconto, mantém o preço base
                            $valor = $basePrice;
                        }
                    }
                }

                // Garante que o valor final não seja negativo
                $valor = max($valor, 0);
                // Aqui chamamos a classe responsável pela atualização do produto
                \App\Jobs\UpdateMercadoLivrePrice::dispatch($produtoIntegrado->id_mercadolivre,$this->price,$token->access_token,$valor)->delay(now()->addSeconds(3));

                // Log::info("Produto integrado atualizado com sucesso: ID {$produtoIntegrado->id}");
            } catch (\Exception $e) {
                Log::error("Erro ao atualizar produto integrado ID {$produtoIntegrado->id}: " . $e->getMessage());
            }
        }

        \App\Jobs\UpdateProductPriceTikTok::dispatch($this->productId,$basePrice);

        return ['success' => true, 'message' => 'Manipulação de produtos concluída.'];
    }


    /**
     * Método para manipular os produtos integrados relacionados ao produto.
     */
    public function atualizarOnlyProduct()
    {
        // Buscar os produtos integrados com o product_id igual ao ID fornecido
        $produtoIntegrado = produtos_integrados::where('id', $this->productId)->first();

        $newPrice = Products::where('id','=',$produtoIntegrado->product_id)->first();

        try {
            $token = token::where('user_id','=',$produtoIntegrado->user_id)->first();
            $valor = 0; // Valor final do produto
            $basePrice = $newPrice->priceWithFee; // Preço base do produto

            if ($produtoIntegrado->precofixo) {
                // Mantém o valor fixo se estiver preenchido
                $valor = $produtoIntegrado->precofixo;
            } else {
                if ($produtoIntegrado->isPorcem == true || $produtoIntegrado->isPorcem == 1) {
                    // Se for porcentagem
                    if (!empty($produtoIntegrado->acrescimo_porcentagem)) {
                        // Aplica acréscimo em porcentagem
                        $valor = $basePrice + ($basePrice * ($produtoIntegrado->acrescimo_porcentagem / 100));
                    } elseif (!empty($produtoIntegrado->desconto_porcentagem)) {
                        // Aplica desconto em porcentagem
                        $valor = $basePrice - ($basePrice * ($produtoIntegrado->desconto_porcentagem / 100));
                    }
                } else {
                    // Se não for porcentagem (valores absolutos)
                    if (!empty($produtoIntegrado->acrescimo_reais)) {
                        // Aplica acréscimo em valor absoluto
                        $valor = $basePrice + $produtoIntegrado->acrescimo_reais;
                    } elseif (!empty($produtoIntegrado->desconto_reais)) {
                        // Aplica desconto em valor absoluto
                        $valor = $basePrice - $produtoIntegrado->desconto_reais;
                    } else {
                        // Caso não tenha nenhum acréscimo ou desconto, mantém o preço base
                        $valor = $basePrice;
                    }
                }
            }

            // Garante que o valor final não seja negativo
            $valor = max($valor, 0);

            // Aqui chamamos a classe responsável pela atualização do produto
            \App\Jobs\UpdateMercadoLivrePrice::dispatch($produtoIntegrado->id_mercadolivre,$valor,$token->access_token,$valor);

            // Log::info("Produto integrado atualizado com sucesso: ID {$produtoIntegrado->id}");
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar produto integrado ID {$produtoIntegrado->id}: " . $e->getMessage());
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
