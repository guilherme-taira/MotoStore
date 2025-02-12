<?php

namespace App\Jobs;

use App\Http\Controllers\MercadoLivreStockController;
use App\Models\produtos_integrados;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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

        $estoque_minimo = 0;
        foreach ($jobs as $product) {
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
            // // Atualiza o estoque
            $estoqueNew->updateStockByDirectProduct();
        }

    }
}
