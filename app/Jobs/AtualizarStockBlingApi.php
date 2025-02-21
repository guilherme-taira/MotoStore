<?php

namespace App\Jobs;

use App\Models\Products;
use App\Models\User;
use App\Notifications\StockMinimumReached;
use AWS\CRT\HTTP\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AtualizarStockBlingApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Verifica se $this->data contém o índice 'retorno'
        if (!isset($this->data['retorno'])) {
            return response()->json(['error' => 'Dados inválidos ou ausentes.'], 400);
        }

        // Decodifique o campo 'retorno' (presume-se que seja JSON)
        $retorno = $this->data['retorno'];
        $type = array_key_first($retorno); // Obtém a primeira chave do array

        // Executa o comportamento com base no tipo
        switch ($type) {
            case 'estoques':
                    $estoques = $retorno['estoques'];
                    foreach ($estoques as $estoque) {
                        $estoqueAtual = $estoque['estoque']['estoqueAtual'];
                        $id_bling = $estoque['estoque']['id'];
                        $produto = Products::where('id_bling',$id_bling)->first();
                         // Busca os produtos no banco
                        $product = Products::findOrFail($produto->id);
                        // Atualiza o estoque do produto
                        $product->available_quantity = $estoqueAtual;
                        $product->save();

                        // // Recalcula o estoque do afiliado baseado no percentual configurado no banco
                        $percentualEstoque = $product->percentual_estoque; // Certifique-se de que este campo exista na tabela products
                        $estoqueAfiliado = floor(($estoqueAtual * $percentualEstoque) / 100);

                        // Atualiza o estoque do afiliado no banco
                        $product->estoque_afiliado = $estoqueAfiliado;
                        $product->save();

                        // Disparando o Job
                        UpdateStockJob::dispatch($produto->id,$estoqueAfiliado,$produto->estoque_minimo_afiliado);

                        // Verifica se o estoque do afiliado atingiu o limite mínimo
                        if ($estoqueAfiliado <= $product->estoque_minimo_afiliado) {
                        // Envia a notificação para o usuário
                        $users = $product->fornecedor_id; // Ajuste conforme a relação de usuários e produtos
                        $user = User::find($users);

                            // Verifica o campo `acao`
                        if (is_null($product->acao)) {
                            // Notifica o usuário caso `acao` seja null
                            if ($user) {
                                $user->notify(new StockMinimumReached($product, $user));
                            }
                        } elseif ($product->acao === 'pausar') {
                            // Pausa todos os anúncios relacionados
                            // $this->pausarAnuncios($produto->id);
                        }

                    }
                }

            break;

            case 'nota fiscal':
                if (isset($this->data['notas'])) {
                    // Implemente a lógica para tratar os dados de nota fiscal
                    $notas = $this->data['notas'];

                    foreach ($notas as $notaItem) {
                        // Exemplo: trate os dados da nota fiscal
                        Log::info("Nota fiscal processada: " . json_encode($notaItem));
                    }

                    return response()->json(['message' => 'Dados de nota fiscal processados com sucesso.']);
                }
                break;

            default:
                return response()->json(['error' => 'Tipo inválido fornecido.'], 400);
        }
    }
}
