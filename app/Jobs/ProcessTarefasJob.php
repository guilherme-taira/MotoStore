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

class ProcessTarefasJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tarefasPendentes = Tarefa::where('finalizado', 0)->get();

        foreach ($tarefasPendentes as $tarefa) {
            try {
                // Lógica de processamento da tarefa
                $dados = json_decode($tarefa->notificacao, true);

                // Simulação de processamento (substitua pela lógica real)
                if ($dados) {
                    $data = new getPaymentController($tarefa->pagamento_id);
                    $data->resource();
                    $tarefa->update(['finalizado' => 1]);
                }
            } catch (\Exception $e) {
                Log::error("Erro ao processar a tarefa ID: {$tarefa->id}", ['erro' => $e->getMessage()]);
            }
        }
    }
}
