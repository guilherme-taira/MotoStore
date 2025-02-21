<?php

namespace App\Jobs;

use App\Http\Controllers\devolucaoController;
use App\Models\Devolucao;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessDevolucoes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $token;
    protected $userId;
    protected $topic;

    /**
     * Cria uma nova instância do Job.
     */
    public function __construct($token, $userId,$topic)
    {
        $this->token = $token;
        $this->userId = $userId;
        $this->topic = $topic;
    }

    /**
     * Executa o Job.
     */
    public function handle()
    {
        Log::info("Iniciando processamento de devoluções para o usuário ID: {$this->userId}");

        $request = new devolucaoController($this->topic,"v1",$this->token,$this->userId);
        $request->getdataByClaims();

    }
}
