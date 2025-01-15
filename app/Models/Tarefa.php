<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'tarefas';

    protected $fillable = [
        'notificacao',
        'finalizado',
        'pagamento_id'
    ];


    public static function processarPendentes()
    {
        $tarefas = Tarefa::where('finalizado', 0)->get();

        foreach ($tarefas as $tarefa) {
            try {
                // Aqui você processa a notificação
                $notificacao = $tarefa->notificacao;

                // Exemplo: registrar no log (substitua isso com seu processamento real)
                log::info('Processando notificação: ', $notificacao);

                // Após processar, marca como finalizado
                $tarefa->finalizado = 1;
                $tarefa->save();
            } catch (\Exception $e) {
                Log::error('Erro ao processar tarefa ID: ' . $tarefa->id, [
                    'mensagem' => $e->getMessage(),
                ]);
            }
        }
    }
}
