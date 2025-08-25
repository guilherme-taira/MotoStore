<?php

namespace App\Jobs;

use App\Http\Controllers\TikTokProductController;
use App\Models\SellerAccount;
use App\Models\TikTokProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateProductPriceTikTok implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $sku;
    protected float $newPrice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $sku, float $newPrice)
    {
        $this->sku = $sku;
        $this->newPrice = $newPrice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // Buscar os produtos integrados com o product_id igual ao ID fornecido
        $produtosIntegrados = TikTokProduct::where('local_product_id', $this->sku)->get();

        foreach ($produtosIntegrados as $produtoIntegrado) {
            try {
                $token = SellerAccount::where('user_id','=',$produtoIntegrado->user_id)->first();
                $valor = 0; // Valor final do produto
                $basePrice = $this->newPrice; // Preço base do produto

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
                $TikTokProductController = new TikTokProductController();
                $TikTokProductController->updateOriginalPriceTikTok($produtoIntegrado->tiktok_sku,$produtoIntegrado->tiktok_product_id,$valor,$token);
            } catch (\Exception $e) {
                Log::error("Erro ao atualizar produto integrado ID {$produtoIntegrado->id}: " . $e->getMessage());
            }
        }
    }
}
