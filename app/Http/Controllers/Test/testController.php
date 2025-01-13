<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Bling\BlingContatos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MercadoLivre\Generatecharts;
use App\Http\Controllers\MercadoLivre\GeneratechartsSneakers;
use App\Http\Controllers\MercadoLivre\MlbCallAttributes;
use App\Http\Controllers\MercadoLivre\MlbTipos;
use App\Http\Controllers\MercadoLivreHandler\ConcretoDomainController;
use App\Http\Controllers\MercadoLivreHandler\getDomainController;
use App\Http\Controllers\SaiuPraEntrega\SaiuPraEntregaService;
use App\Http\Controllers\SaiuPraEntrega\SendNotificationPraEntregaController;
use App\Http\Controllers\SaiuPraEntrega\TypeMessageController;
use App\Http\Controllers\Shopify\LineItem;
use App\Http\Controllers\Shopify\Order;
use App\Http\Controllers\Shopify\SendOrder;
use App\Http\Controllers\Shopify\ShippingAddress;
use App\Http\Controllers\Shopify\ShopifyProduct;
use App\Models\BlingCreateUserByFornecedor;
use App\Models\Contato;
use App\Models\IntegracaoBling;
use App\Models\order_site;
use App\Models\ShippingUpdate;
use App\Models\Shopify;
use App\Models\token;
use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class testController extends Controller
{

    function getFormattedDate($date) {
        // Converte a data para o formato 'Y-m-d' se necessário
        $formattedDate = date('Y-m-d', strtotime($date));

        // Obtém o mês abreviado
        $monthAbbreviated = date('M', strtotime($formattedDate));

        $meses = [
            "January" => "Janeiro",
            "February" => "Fevereiro",
            "March" => "Março",
            "April" => "Abril",
            "May" => "Maio",
            "June" => "Junho",
            "July" => "Julho",
            "August" => "Agosto",
            "September" => "Setembro",
            "October" => "Outubro",
            "November" => "Novembro",
            "December" => "Dezembro"
        ];

        if(isset($meses[$monthAbbreviated])){
            $mesTraduzido = $meses[$monthAbbreviated];
        }
        // Obtém o dia do mês
        $dayOfMonth = date('j', strtotime($formattedDate));

        // Formata a data como "M DIA"
        $formattedDate = $mesTraduzido . ',' . $dayOfMonth;

        return $formattedDate;
    }


    public function teste(Request $request){
           // ENVIAR VENDA BLING
           try {
            $contato = Contato::where('integracao_bling_id',59)->first();
            $auth = IntegracaoBling::where('user_id',16)->first();
            $contatoEfornecedor = BlingCreateUserByFornecedor::ifExistFornecedor(16,$contato->id);

            echo "<pre>";
            if($contatoEfornecedor){

            }else{
                  $blingData = [
                    'nome' => $contato['nome'],
                    'tipo' => $contato['tipo'],
                    'numeroDocumento' => $contato['numeroDocumento'],
                    'situacao' => $contato['situacao'],
                    'celular' => $contato['celular'],
                    'email' => $contato['email'],
                    'rg' => $contato['rg'] ?? null,
                    'endereco' => [
                        'geral' => [
                            'endereco' => $contato['endereco'],
                            'cep' => $contato['cep'],
                            'bairro' => $contato['bairro'],
                            'municipio' => $contato['municipio'],
                            'uf' => $contato['uf'],
                            'numero' => $contato['numero'],
                            'complemento' => $contato['complemento'] ?? null,
                        ],
                    ],
                ];

                $BlingContatos = new BlingContatos($auth->access_token,$contato->id,16);
                $BlingContatos->enviarContato($blingData);
            }

        } catch (\Throwable $th) {
            Log::alert($th->getMessage());
        }
    }

}
