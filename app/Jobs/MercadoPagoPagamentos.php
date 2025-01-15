<?php

namespace App\Jobs;

use App\Http\Controllers\MercadoPago\Pagamento\getPaymentController;
use App\Models\Tarefa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MercadoPagoPagamentos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order_id;
    public $dados;
    // public string $seller_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id,$dados)
    {
        $this->order_id = $order_id;
        $this->dados = $dados;
        // $this->seller_id = $seller_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::alert($this->order_id);
        $tarefa = Tarefa::create([
            'pagamento_id' => $this->order_id,
            'notificacao' => json_encode($this->dados),
            'finalizado' => false, // Inicialmente marcado como não finalizado
        ]);

        if($tarefa){
            $data = new getPaymentController($this->order_id);
            $data->resource();
        }
    }
}
