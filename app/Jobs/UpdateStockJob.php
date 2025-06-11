<?php

namespace App\Jobs;

use App\Http\Controllers\MercadoLivreStockController;
use App\Http\Controllers\MercadoLivreStockVariationsController;
use App\Models\produtos_integrados;
use App\Models\Variacao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateStockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id;
    public $estoque_afiliado;
    public $estoque_minimo_afiliado;
    public $percentual_estoque;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id,$estoque_afiliado = 0,$estoque_minimo_afiliado = 0,$percentual_estoque = 0)
    {
        $this->id = $id;
        $this->estoque_afiliado = $estoque_afiliado;
        $this->estoque_minimo_afiliado = $estoque_minimo_afiliado;
        $this->percentual_estoque = $percentual_estoque;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $jobs = produtos_integrados::where('product_id',$this->id)->get();
        // 2. Busca as variações com esse mesmo product_id
        $variacoes = DB::table('variacoes')
        ->Join('produtos_integrados', 'variacoes.id_mercadolivre', '=', 'produtos_integrados.id_mercadolivre')
        ->where('variacoes.sku', $this->id)
        ->select('*') // ou adicione campos específicos
        ->get();


        $estoque_minimo = 0;
        foreach ($variacoes as $product) {
            // Instancia o controlador
            $ativo = $product->active;
              // Busca todas as variações com o mesmo ID do anúncio
            foreach ($variacoes as $produto) {

                $variacoesProds = Variacao::where('id_mercadolivre', $produto->id_mercadolivre)->get();

                $payload = [
                    'variations' => []
                ];

                if(count($variacoes) > 0){

                foreach ($variacoesProds as $var) {
                if($var->sku == $this->id){
                        $payload['variations'][] = [
                            'id' => (int) $var->meli_variation_id,
                            'available_quantity' => (int) $this->estoque_afiliado,
                            // 'attribute_combinations' => json_decode($var->attribute_combinations, true),
                            'picture_ids' => json_decode($var->picture_ids, true),
                        ];
                }else{
                        $payload['variations'][] = [
                            'id' => (int) $var->meli_variation_id,
                            'available_quantity' => (int) $var->available_quantity,
                            // 'attribute_combinations' => json_decode($var->attribute_combinations, true),
                            'picture_ids' => json_decode($var->picture_ids, true),
                        ];
                }

                }
                    // Para visualizar ou usar com Guzzle/cURL/etc.
                    $jsonPayload = $payload;
                }
            }

            $estoqueNew = new MercadoLivreStockVariationsController(
                $product->id_mercadolivre,
                $this->estoque_afiliado,
                $ativo,
                $this->estoque_minimo_afiliado,
                $product->user_id,
                $product->estoque_minimo,
                $jsonPayload
            );
            // // Atualiza o estoque
            $estoqueNew->updateStock();
        }

        foreach ($jobs as $product) {
           if($this->estoque_afiliado < 0) {
                $this->estoque_afiliado = 0;
           }
            // Instancia o controlador
            $ativo = $product->active;
            $estoqueNew = new MercadoLivreStockController(
                $product->id_mercadolivre,
                $this->estoque_afiliado,
                $ativo,
                $this->estoque_minimo_afiliado,
                $product->user_id,
                $product->estoque_minimo
            );
            // Atualiza o estoque
            $estoqueNew->updateStockByDirectProduct();
        }

    }
}
