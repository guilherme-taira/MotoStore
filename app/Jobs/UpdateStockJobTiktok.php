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

class UpdateStockJobTiktok implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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


    public function handle() {

        $jobs = TikTokProduct::where('local_product_id',$this->id)->get();
        // 2. Busca as variações com esse mesmo product_id

        try {
            foreach ($jobs as $product) {
            if($this->estoque_afiliado < 0) {
                    $this->estoque_afiliado = 0;
            }
                $estoqueNew = new TikTokProductController();
                // Atualiza o estoque
                $seller = SellerAccount::where('user_id',$product->user_id)->first();
                $estoqueNew->updateInventory($product->tiktok_product_id,$product->tiktok_sku,$this->estoque_afiliado,$seller,"7528080962442594054");
            }
        } catch (\Throwable $th) {
                Log::alert($th->getMessage());
        }
    }
}
